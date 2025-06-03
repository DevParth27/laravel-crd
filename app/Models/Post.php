<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'author'
    ];

    // Optional: Define relationships, scopes, etc.
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }
}