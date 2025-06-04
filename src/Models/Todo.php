<?php

namespace App\Models;

use App\Core\Model;

class Todo extends Model
{
    protected string $table = 'todos';

    protected array $fillable = [
        'title',
        'description',
        'deadline',
        'is_done',
    ];

    protected array $casts = [
        'is_done' => 'bool',
        'deadline' => 'datetime',
    ];
}
