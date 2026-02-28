<?php

namespace App\Http\Controllers\Landing;

use App\Models\User;
use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        $website = $user->personalWebsite;

        if (!$website || !$website->is_published) {
            abort(404);
        }

        $website->load(
            'profile',
            'socialLinks',
            'skills',
            'services',
            'experiences',
            'educations',
            'portfolios',
            'testimonials'
        );

        $theme = $website->theme ?? 'minimal';

        return view("portfolio.themes.{$theme}", compact('website', 'user'));
    }
}
