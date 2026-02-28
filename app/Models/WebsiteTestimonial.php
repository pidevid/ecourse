<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteTestimonial extends Model
{
    use HasFactory;

    protected $fillable = ['personal_website_id', 'client_name', 'client_role', 'content', 'avatar'];

    public function personalWebsite()
    {
        return $this->belongsTo(PersonalWebsite::class);
    }
}
