<?php

namespace App\Models;

use App\Collections\BlogCollection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Blog
 *
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
 * @property-read int|null $comments_count
 * @property-read int|null $posts_count
 * @property-read int|null $subscribers_count
 *
 * @method static BlogCollection<int, static> all($columns = ['*'])
 * @method static \Database\Factories\BlogFactory factory($count = null, $state = [])
 * @method static BlogCollection<int, static> get($columns = ['*'])
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog query()
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereDisplayName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Blog whereUserId($value)
 *
 * @mixin \Eloquent
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
     */
    public function getRouteKeyName(): string
    {
        return 'name';
    }

    /**
     * Create a new Eloquent Collection instance.
     */
    public function newCollection(array $models = []): Collection
    {
        return new BlogCollection($models);
    }

    /**
     * 블로그 소유자
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 내 구독자
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->as('subscription');
    }

    /**
     * 글
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * 댓글
     */
    public function comments(): HasManyThrough
    {
        return $this->hasManyThrough(Comment::class, Post::class, secondKey: 'commentable_id')
            ->where('commentable_type', Post::class);
    }
}
