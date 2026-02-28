<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsitePortfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'personal_website_id', 'title', 'description',
        'image', 'url', 'tech_stack',
    ];

    public function personalWebsite()
    {
        return $this->belongsTo(PersonalWebsite::class);
    }
}
