<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'sub_title',
        'body',
        'status',
        'published_at',
        'cover_photo_path',
        'photo_alt_text',
        'user_id',
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::creating(function ($post) {
            // Générer le slug à partir du titre si pas déjà défini
            if (empty($post->slug) && !empty($post->title)) {
                $post->slug = Str::slug($post->title);
            }

            // Assigner l'utilisateur connecté si pas déjà défini
            if (empty($post->user_id) && Auth::check()) {
                $post->user_id = Auth::id();
            }
        });
    }

    /**
     * Post belongs to a user (author).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Post belongs to many categories.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Categorie::class, 'category_post', 'post_id', 'category_id');
    }

    public function coverPhotoPath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset('storage/'.$value),
        );
    }
}
