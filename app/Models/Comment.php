<?php

namespace App\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'task_id',
        'name',
        'description',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y \a\t H:i:s',
        'updated_at' => 'datetime:d/m/Y \a\t H:i:s',
    ];

    /**
     * Get task relationship
     */
    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }

    /**
     * Get user relationship
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
