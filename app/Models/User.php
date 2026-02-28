<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\ExamScore;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Import trait HasScope
use App\Traits\HasScope;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasScope; // Tambahkan HasScope di sini

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'username', 'avatar', 'github', 'instagram', 'about', 'has_website_access'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected function name(): Attribute
    {
        return Attribute::make(set: fn($value) => strtolower($value));
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(get: fn($avatar) => $avatar != '' ? asset('/storage/avatar/' . $avatar) : 'https://ui-avatars.com/api/?name=' . str_replace(' ', '+', $this->name) . '&background=4e73df&color=ffffff&size=100');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function showcases()
    {
        return $this->hasMany(Showcase::class);
    }
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }
    public function examScores()
    {
        return $this->hasMany(ExamScore::class);
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function personalWebsite()
    {
        return $this->hasOne(PersonalWebsite::class);
    }
}
