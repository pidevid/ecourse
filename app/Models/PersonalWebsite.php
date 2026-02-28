<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalWebsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'theme', 'accent_color', 'font_family',
        'meta_title', 'meta_description', 'is_published',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->hasOne(WebsiteProfile::class);
    }

    public function socialLinks()
    {
        return $this->hasMany(WebsiteSocialLink::class);
    }

    public function skills()
    {
        return $this->hasMany(WebsiteSkill::class);
    }

    public function services()
    {
        return $this->hasMany(WebsiteService::class);
    }

    public function experiences()
    {
        return $this->hasMany(WebsiteExperience::class)->orderBy('start_date', 'desc');
    }

    public function educations()
    {
        return $this->hasMany(WebsiteEducation::class)->orderBy('start_year', 'desc');
    }

    public function portfolios()
    {
        return $this->hasMany(WebsitePortfolio::class);
    }

    public function testimonials()
    {
        return $this->hasMany(WebsiteTestimonial::class);
    }
}
