<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteSkill extends Model
{
    use HasFactory;

    protected $fillable = ['personal_website_id', 'name', 'level', 'percentage'];

    public function personalWebsite()
    {
        return $this->belongsTo(PersonalWebsite::class);
    }
}
