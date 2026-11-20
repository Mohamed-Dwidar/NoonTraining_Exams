<?php

namespace Modules\TypingTestModule\App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TypingTestSetting extends Model {
    protected $table = 'typing_test_settings';

    // `title` is intentionally not fillable: the test name is fixed.
    protected $fillable = [
        'description',
        'duration_minutes',
        'word_count',
        'success_grade',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'word_count'       => 'integer',
        'success_grade'    => 'integer',
    ];
}
