<?php
namespace App\Models;

use App\Models\User;
use App\Models\Category;
use App\Traits\HasScope;
use App\Traits\HasSlug;
use App\Models\ExamScore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory, HasScope, HasSlug;

    protected $fillable = ['name', 'image', 'slug', 'category_id', 'user_id', 'certificates_id', 'demo', 'description', 'discount', 'price', 'status'];

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    protected function image(): Attribute
    {
        return Attribute::make(get: fn($image) => asset('storage/course/' . $image));
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function examScores()
    {
        return $this->hasMany(ExamScore::class);
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
