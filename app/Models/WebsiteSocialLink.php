<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSocialLink extends Model
{
    use HasFactory;

    protected $fillable = ['personal_website_id', 'platform', 'url'];

    public function personalWebsite()
    {
        return $this->belongsTo(PersonalWebsite::class);
    }
}
