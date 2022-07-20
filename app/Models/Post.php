<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $fillable = [
        'title',
        'content',
    ];

    /**
     * 블로그
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * 댓글
     *
     * @return mixed
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->withTrashed();
    }

    /**
     * 첨부파일
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}
