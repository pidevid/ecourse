<?php

namespace App\Http\Controllers\Member;

use Midtrans\Snap;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class WebsitePaymentController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey    = config('services.midtrans.serverKey');
        \Midtrans\Config::$isProduction = config('services.midtrans.isProduction');
        \Midtrans\Config::$isSanitized  = config('services.midtrans.isSanitized');
        \Midtrans\Config::$is3ds        = config('services.midtrans.is3ds');
    }

    public function index()
    {
        if (Auth::user()->has_website_access) {
            return redirect()->route('member.website.index')
                ->with('toast_info', 'Anda sudah memiliki akses Personal Website.');
        }

        return view('member.website.buy');
    }

    public function checkout(Request $request)
    {
        $user = $request->user();

        if ($user->has_website_access) {
            return redirect()->route('member.website.index');
        }

        $random     = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        $no_invoice = 'WEB-' . $random;
        $price      = 100000;

        $invoice = Transaction::create([
            'invoice'     => $no_invoice,
            'user_id'     => $user->id,
            'name'        => $user->name,
            'grand_total' => $price,
            'status'      => 'pending',
        ]);

        $payload = [
            'transaction_details' => [
                'order_id'     => $invoice->invoice,
                'gross_amount' => $invoice->grand_total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
            'item_details' => [
                [
                    'id'       => 'WEBSITE-ACCESS',
                    'price'    => $price,
                    'quantity' => 1,
                    'name'     => 'Personal Website Access',
                ],
            ],
        ];

        $snapToken         = Snap::getSnapToken($payload);
        $invoice->snap_token = $snapToken;
        $invoice->save();

        return view('member.website.checkout', compact('snapToken'));
    }

    public function callback(Request $request)
    {
        $payload      = $request->getContent();
        $notification = json_decode($payload);

        $signatureKey = hash(
            'sha512',
            $notification->order_id .
            $notification->status_code .
            $notification->gross_amount .
            config('services.midtrans.serverKey')
        );

        if ($notification->signature_key !== $signatureKey) {
            return response(['message' => 'Invalid signature'], 403);
        }

        $orderId    = $notification->order_id;
        $txStatus   = $notification->transaction_status;

        // Only handle WEB- invoices here
        if (!str_starts_with($orderId, 'WEB-')) {
            return response(['message' => 'Not a website payment'], 200);
        }

        $transaction = Transaction::where('invoice', $orderId)->first();

        if (!$transaction) {
            return response(['message' => 'Transaction not found'], 404);
        }

        if ($txStatus === 'settlement' || ($txStatus === 'capture' && $notification->payment_type === 'credit_card')) {
            $transaction->update(['status' => 'success']);
            $transaction->user()->update(['has_website_access' => true]);
        } elseif ($txStatus === 'pending') {
            $transaction->update(['status' => 'pending']);
        } elseif (in_array($txStatus, ['deny', 'expire', 'cancel'])) {
            $transaction->update(['status' => 'failed']);
        }

        return response(['message' => 'OK'], 200);
    }
}
