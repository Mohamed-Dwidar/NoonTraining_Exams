<?php

namespace Modules\TypingTestModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TypingText extends Model {
    protected $table = 'typing_texts';

    protected $fillable = [
        'title',
        'content',
        'words_count',
        'is_active',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'words_count' => 'integer',
    ];
}
