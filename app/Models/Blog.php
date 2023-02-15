<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Collections\BlogCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property int $user_id
 * @property User $user
 * @property \Illuminate\Database\Eloquent\Collection $subscribers
 * @property \Illuminate\Database\Eloquent\Collection $posts
 * @property \Illuminate\Database\Eloquent\Collection $comments
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'display_name',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'name';
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = []): Collection
    {
        return new BlogCollection($models);
    }

    /**
     * 블로그 소유자
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 내 구독자
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->as('subscription');
    }

    /**
     * 글
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * 댓글
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function comments(): HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, Post::class, secondKey: 'commentable_id')
            ->where('commentable_type', Post::class);
    }
}
