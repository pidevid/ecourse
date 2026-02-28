<?php

namespace App\Http\Controllers\Member;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\PersonalWebsite;
use App\Models\WebsiteSkill;
use App\Models\WebsiteService;
use App\Models\WebsiteExperience;
use App\Models\WebsiteEducation;
use App\Models\WebsitePortfolio;
use App\Models\WebsiteTestimonial;

class WebsiteController extends Controller
{
    private function getOrCreateWebsite()
    {
        return PersonalWebsite::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'theme'        => 'minimal',
                'accent_color' => '#14b8a6',
                'font_family'  => 'sans',
                'is_published' => true,
            ]
        );
    }

    // ─── Dashboard ────────────────────────────────────────────────────────────

    public function index()
    {
        $website = $this->getOrCreateWebsite();
        $website->load('profile', 'skills', 'services', 'experiences', 'educations', 'portfolios', 'testimonials', 'socialLinks');

        return view('member.website.index', compact('website'));
    }

    // ─── Settings (theme, color, font, SEO) ─────────────────────────────────

    public function settings()
    {
        $website = $this->getOrCreateWebsite();
        return view('member.website.settings', compact('website'));
    }

    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'theme'            => 'required|in:minimal,creative,professional',
            'accent_color'     => 'required|string|max:20',
            'font_family'      => 'required|in:sans,serif,mono',
            'meta_title'       => 'nullable|string|max:100',
            'meta_description' => 'nullable|string|max:255',
            'is_published'     => 'nullable|boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $website = $this->getOrCreateWebsite();
        $website->update($validated);

        return back()->with('toast_success', 'Pengaturan berhasil disimpan.');
    }

    // ─── Profile ─────────────────────────────────────────────────────────────

    public function profile()
    {
        $website = $this->getOrCreateWebsite();
        $profile = $website->profile;
        return view('member.website.profile', compact('website', 'profile'));
    }

    public function saveProfile(Request $request)
    {
        $validated = $request->validate([
            'full_name'  => 'required|string|max:100',
            'role_title' => 'nullable|string|max:100',
            'short_bio'  => 'nullable|string|max:255',
            'about_me'   => 'nullable|string',
            'avatar'     => 'nullable|image|max:2048',
            'cv_file'    => 'nullable|file|mimes:pdf|max:5120',
            'email'      => 'nullable|email|max:100',
            'phone'      => 'nullable|string|max:30',
            'location'   => 'nullable|string|max:100',
        ]);

        $website = $this->getOrCreateWebsite();
        $profile = $website->profile ?? $website->profile()->make();

        if ($request->hasFile('avatar')) {
            if ($profile->getRawOriginal('avatar')) {
                Storage::disk('public')->delete('website/avatars/' . $profile->getRawOriginal('avatar'));
            }
            $validated['avatar'] = $request->file('avatar')->store('website/avatars', 'public');
            $validated['avatar'] = basename($validated['avatar']);
        }

        if ($request->hasFile('cv_file')) {
            if ($profile->getRawOriginal('cv_file')) {
                Storage::disk('public')->delete('website/cv/' . $profile->getRawOriginal('cv_file'));
            }
            $validated['cv_file'] = $request->file('cv_file')->store('website/cv', 'public');
            $validated['cv_file'] = basename($validated['cv_file']);
        }

        $validated['personal_website_id'] = $website->id;
        $profile->fill($validated)->save();

        return back()->with('toast_success', 'Profil berhasil disimpan.');
    }

    // ─── Skills ──────────────────────────────────────────────────────────────

    public function skills()
    {
        $website = $this->getOrCreateWebsite();
        $skills  = $website->skills;
        return view('member.website.skills', compact('website', 'skills'));
    }

    public function storeSkill(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:60',
            'level'      => 'required|in:beginner,intermediate,expert',
            'percentage' => 'required|integer|min:0|max:100',
        ]);

        $website = $this->getOrCreateWebsite();
        $validated['personal_website_id'] = $website->id;
        WebsiteSkill::create($validated);

        return back()->with('toast_success', 'Skill berhasil ditambahkan.');
    }

    public function destroySkill(WebsiteSkill $skill)
    {
        $this->authorizeWebsiteItem($skill->personal_website_id);
        $skill->delete();
        return back()->with('toast_success', 'Skill berhasil dihapus.');
    }

    // ─── Services ────────────────────────────────────────────────────────────

    public function services()
    {
        $website  = $this->getOrCreateWebsite();
        $services = $website->services;
        return view('member.website.services', compact('website', 'services'));
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:100',
            'description' => 'required|string',
            'icon'        => 'nullable|string|max:60',
        ]);

        $website = $this->getOrCreateWebsite();
        $validated['personal_website_id'] = $website->id;
        WebsiteService::create($validated);

        return back()->with('toast_success', 'Layanan berhasil ditambahkan.');
    }

    public function destroyService(WebsiteService $service)
    {
        $this->authorizeWebsiteItem($service->personal_website_id);
        $service->delete();
        return back()->with('toast_success', 'Layanan berhasil dihapus.');
    }

    // ─── Experience ──────────────────────────────────────────────────────────

    public function experience()
    {
        $website     = $this->getOrCreateWebsite();
        $experiences = $website->experiences;
        return view('member.website.experience', compact('website', 'experiences'));
    }

    public function storeExperience(Request $request)
    {
        $validated = $request->validate([
            'company'     => 'required|string|max:100',
            'position'    => 'required|string|max:100',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'is_current'  => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_current'] = $request->has('is_current');
        if ($validated['is_current']) {
            $validated['end_date'] = null;
        }

        $website = $this->getOrCreateWebsite();
        $validated['personal_website_id'] = $website->id;
        WebsiteExperience::create($validated);

        return back()->with('toast_success', 'Pengalaman berhasil ditambahkan.');
    }

    public function destroyExperience(WebsiteExperience $experience)
    {
        $this->authorizeWebsiteItem($experience->personal_website_id);
        $experience->delete();
        return back()->with('toast_success', 'Pengalaman berhasil dihapus.');
    }

    // ─── Education ───────────────────────────────────────────────────────────

    public function education()
    {
        $website    = $this->getOrCreateWebsite();
        $educations = $website->educations;
        return view('member.website.education', compact('website', 'educations'));
    }

    public function storeEducation(Request $request)
    {
        $validated = $request->validate([
            'institution' => 'required|string|max:150',
            'degree'      => 'required|string|max:100',
            'field'       => 'required|string|max:100',
            'start_year'  => 'required|integer|min:1970|max:2099',
            'end_year'    => 'nullable|integer|min:1970|max:2099',
            'description' => 'nullable|string',
        ]);

        $website = $this->getOrCreateWebsite();
        $validated['personal_website_id'] = $website->id;
        WebsiteEducation::create($validated);

        return back()->with('toast_success', 'Pendidikan berhasil ditambahkan.');
    }

    public function destroyEducation(WebsiteEducation $education)
    {
        $this->authorizeWebsiteItem($education->personal_website_id);
        $education->delete();
        return back()->with('toast_success', 'Pendidikan berhasil dihapus.');
    }

    // ─── Portfolio ───────────────────────────────────────────────────────────

    public function portfolio()
    {
        $website    = $this->getOrCreateWebsite();
        $portfolios = $website->portfolios;
        return view('member.website.portfolio', compact('website', 'portfolios'));
    }

    public function storePortfolio(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:3072',
            'url'         => 'nullable|url|max:255',
            'tech_stack'  => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('website/portfolio', 'public');
            $validated['image'] = basename($path);
        }

        $website = $this->getOrCreateWebsite();
        $validated['personal_website_id'] = $website->id;
        WebsitePortfolio::create($validated);

        return back()->with('toast_success', 'Portfolio berhasil ditambahkan.');
    }

    public function destroyPortfolio(WebsitePortfolio $portfolio)
    {
        $this->authorizeWebsiteItem($portfolio->personal_website_id);
        if ($portfolio->image) {
            Storage::disk('public')->delete('website/portfolio/' . $portfolio->image);
        }
        $portfolio->delete();
        return back()->with('toast_success', 'Portfolio berhasil dihapus.');
    }

    // ─── Testimonials ────────────────────────────────────────────────────────

    public function testimonials()
    {
        $website      = $this->getOrCreateWebsite();
        $testimonials = $website->testimonials;
        return view('member.website.testimonials', compact('website', 'testimonials'));
    }

    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:100',
            'client_role' => 'nullable|string|max:100',
            'content'     => 'required|string',
            'avatar'      => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('website/testimonials', 'public');
            $validated['avatar'] = basename($path);
        }

        $website = $this->getOrCreateWebsite();
        $validated['personal_website_id'] = $website->id;
        WebsiteTestimonial::create($validated);

        return back()->with('toast_success', 'Testimoni berhasil ditambahkan.');
    }

    public function destroyTestimonial(WebsiteTestimonial $testimonial)
    {
        $this->authorizeWebsiteItem($testimonial->personal_website_id);
        $testimonial->delete();
        return back()->with('toast_success', 'Testimoni berhasil dihapus.');
    }

    // ─── Social Links ────────────────────────────────────────────────────────

    public function socialLinks()
    {
        $website = $this->getOrCreateWebsite();
        $links   = $website->socialLinks->keyBy('platform');
        return view('member.website.social', compact('website', 'links'));
    }

    public function saveSocialLinks(Request $request)
    {
        $platforms = ['linkedin', 'github', 'dribbble', 'behance', 'instagram', 'twitter', 'website'];

        $rules = [];
        foreach ($platforms as $platform) {
            $rules[$platform] = 'nullable|url|max:255';
        }

        $validated = $request->validate($rules);

        $website = $this->getOrCreateWebsite();

        foreach ($platforms as $platform) {
            $url = $validated[$platform] ?? null;

            if ($url) {
                $website->socialLinks()->updateOrCreate(
                    ['platform' => $platform],
                    ['url' => $url]
                );
            } else {
                $website->socialLinks()->where('platform', $platform)->delete();
            }
        }

        return back()->with('toast_success', 'Social links berhasil disimpan.');
    }

    // ─── Helper ──────────────────────────────────────────────────────────────

    private function authorizeWebsiteItem($personalWebsiteId)
    {
        $website = $this->getOrCreateWebsite();
        abort_if($website->id !== $personalWebsiteId, 403);
    }
}
