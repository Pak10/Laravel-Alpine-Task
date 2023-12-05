<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['comment', 'task_id', 'user_id'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
