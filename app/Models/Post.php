<?php

namespace App\Models;

use Filament\Resources\Concerns\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = ['title', 'content', 'status'];
    public $translatable = ['title', 'content'];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }
}
