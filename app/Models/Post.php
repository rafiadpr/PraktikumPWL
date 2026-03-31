<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'color',
        'image',
        'body',
        // 'tags',
        'published',
        'published_at',
    ];

    protected $casts = [
        // 'tags' => 'array',
        'published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Relasi BelongsTo: Setiap Post dimiliki oleh satu Category.
     * Laravel otomatis mencari kolom 'category_id' di tabel posts
     * dan mencocokkannya dengan 'id' di tabel categories.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
