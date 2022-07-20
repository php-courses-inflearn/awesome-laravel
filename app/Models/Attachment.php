<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory, Prunable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'original_name',
        'name'
    ];

    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        return static::whereNull('post_id');
    }

    /**
     * Prepare the model for pruning.
     *
     * @return void
     */
    public function pruning()
    {
        if ($this->external) {
            return;
        }

        Storage::disk('public')->delete($this->path);
    }

    /**
     * 글
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * 파일이 링크인가?
     *
     * @return Attribute
     */
    public function external(): Attribute
    {
        return Attribute::make(
            get: fn () => preg_match('/^https?/', $this->name)
        );
    }

    /**
     * 파일의 경로
     *
     * @return Attribute
     */
    public function path(): Attribute
    {
        return Attribute::make(
            get: fn () => join('/', ['attachments', $this->name])
        );
    }

    /**
     * 다운로드 링크
     *
     * @return Attribute
     */
    public function link(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->external
                ? $this->name
                : Storage::disk('public')->url($this->path)
        );
    }
}
