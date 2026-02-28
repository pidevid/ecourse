<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use App\Models\PersonalWebsite;
use Illuminate\Http\Request;

class WebsiteApiController extends Controller
{
    private function website(Request $request): ?PersonalWebsite
    {
        return $request->user()->personalWebsite;
    }

    public function show(Request $request)
    {
        $website = $this->website($request);

        if (! $website) {
            return response()->json(['message' => 'Website belum dibuat.'], 404);
        }

        $website->load([
            'profile',
            'socialLinks',
            'skills',
            'services',
            'experiences',
            'educations',
            'portfolios',
            'testimonials',
        ]);

        return response()->json($website);
    }

    public function profile(Request $request)
    {
        $website = $this->website($request);

        if (! $website) {
            return response()->json(['message' => 'Website belum dibuat.'], 404);
        }

        return response()->json($website->profile);
    }

    public function skills(Request $request)
    {
        $website = $this->website($request);

        if (! $website) {
            return response()->json(['data' => []]);
        }

        return response()->json(['data' => $website->skills]);
    }

    public function services(Request $request)
    {
        $website = $this->website($request);

        if (! $website) {
            return response()->json(['data' => []]);
        }

        return response()->json(['data' => $website->services]);
    }

    public function experiences(Request $request)
    {
        $website = $this->website($request);

        if (! $website) {
            return response()->json(['data' => []]);
        }

        return response()->json(['data' => $website->experiences]);
    }

    public function educations(Request $request)
    {
        $website = $this->website($request);

        if (! $website) {
            return response()->json(['data' => []]);
        }

        return response()->json(['data' => $website->educations]);
    }

    public function portfolios(Request $request)
    {
        $website = $this->website($request);

        if (! $website) {
            return response()->json(['data' => []]);
        }

        return response()->json(['data' => $website->portfolios]);
    }

    public function testimonials(Request $request)
    {
        $website = $this->website($request);

        if (! $website) {
            return response()->json(['data' => []]);
        }

        return response()->json(['data' => $website->testimonials]);
    }

    public function socialLinks(Request $request)
    {
        $website = $this->website($request);

        if (! $website) {
            return response()->json(['data' => []]);
        }

        return response()->json(['data' => $website->socialLinks]);
    }
}
