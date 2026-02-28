<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NewTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Midtrans\Snap;

class CheckoutApiController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey    = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is3ds');
    }

    // GET /api/me/cart — isi keranjang
    public function cart(Request $request)
    {
        $carts = Cart::with('course.category')
            ->where('user_id', $request->user()->id)
            ->get()
            ->map(fn($c) => [
                'id'         => $c->id,
                'course_id'  => $c->course_id,
                'name'       => $c->course?->name,
                'image'      => $c->course?->image,
                'category'   => $c->course?->category?->name,
                'price'      => $c->price,
            ]);

        return response()->json([
            'data'        => $carts,
            'grand_total' => $carts->sum('price'),
        ]);
    }

    // POST /api/me/cart/{courseId} — tambah ke keranjang
    public function cartAdd(Request $request, $courseId)
    {
        $user = $request->user();

        // Cek sudah di cart
        if (Cart::where('user_id', $user->id)->where('course_id', $courseId)->exists()) {
            return response()->json(['message' => 'Kursus sudah ada di keranjang.'], 409);
        }

        $course = \App\Models\Course::findOrFail($courseId);

        $price = $course->discount > 0
            ? $course->price - ($course->price * $course->discount / 100)
            : $course->price;

        Cart::create([
            'user_id'   => $user->id,
            'course_id' => $courseId,
            'price'     => $price,
        ]);

        return response()->json(['message' => 'Kursus ditambahkan ke keranjang.'], 201);
    }

    // DELETE /api/me/cart/{cartId} — hapus dari keranjang
    public function cartRemove(Request $request, $cartId)
    {
        $cart = Cart::where('user_id', $request->user()->id)->findOrFail($cartId);
        $cart->delete();

        return response()->json(['message' => 'Item dihapus dari keranjang.']);
    }

    // POST /api/me/checkout — buat transaksi & dapatkan snap_token
    public function checkout(Request $request)
    {
        $user  = $request->user();
        $carts = Cart::with('course')->where('user_id', $user->id)->get();

        if ($carts->isEmpty()) {
            return response()->json(['message' => 'Keranjang kosong.'], 422);
        }

        try {
            $result = DB::transaction(function () use ($user, $carts, $request) {
                $length = 6;
                $random = '';
                for ($i = 0; $i < $length; $i++) {
                    $random .= rand(0, 1) ? rand(0, 9) : chr(rand(ord('a'), ord('z')));
                }
                $noInvoice = 'LD-' . Str::upper($random);

                $invoice = Transaction::create([
                    'invoice'     => $noInvoice,
                    'user_id'     => $user->id,
                    'name'        => $request->input('name', $user->name),
                    'grand_total' => $carts->sum('price'),
                    'status'      => 'pending',
                ]);

                foreach ($carts as $cart) {
                    $invoice->details()->create([
                        'course_id' => $cart->course_id,
                        'price'     => $cart->price,
                    ]);
                }

                $payload = [
                    'transaction_details' => [
                        'order_id'     => $invoice->invoice,
                        'gross_amount' => $invoice->grand_total,
                    ],
                    'customer_details' => [
                        'first_name' => $invoice->name,
                        'email'      => $user->email,
                    ],
                    'item_details' => $carts->map(fn($c) => [
                        'id'       => $c->id,
                        'price'    => $c->price,
                        'quantity' => 1,
                        'name'     => Str::limit($c->course?->name ?? 'Course', 40),
                    ])->toArray(),
                ];

                $snapToken = Snap::getSnapToken($payload);
                $invoice->update(['snap_token' => $snapToken]);

                // Hapus cart setelah transaksi dibuat
                Cart::where('user_id', $user->id)->delete();

                // Notifikasi admin
                Notification::send(User::role('admin')->get(), new NewTransaction($invoice));

                return [
                    'invoice'    => $invoice->invoice,
                    'snap_token' => $snapToken,
                    'grand_total'=> $invoice->grand_total,
                ];
            });

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Checkout gagal: ' . $e->getMessage()], 500);
        }
    }
}
