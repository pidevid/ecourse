<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WebsiteProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_website_id', 'full_name', 'role_title', 'short_bio',
        'about_me', 'avatar', 'cv_file', 'email', 'phone', 'location',
    ];

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? asset('storage/website/avatars/' . $value) : null
        );
    }

    protected function cvFile(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? asset('storage/website/cv/' . $value) : null
        );
    }

    public function personalWebsite()
    {
        return $this->belongsTo(PersonalWebsite::class);
    }
}
