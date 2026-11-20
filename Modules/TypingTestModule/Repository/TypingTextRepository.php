<?php

namespace Modules\TypingTestModule\Repository;

use Modules\TypingTestModule\App\Http\Models\TypingText;
use Prettus\Repository\Eloquent\BaseRepository;

class TypingTextRepository extends BaseRepository {
    function model() {
        return TypingText::class;
    }

    public function latestFirst() {
        return TypingText::query()->orderByDesc('id')->get();
    }

    /**
     * Random active text with at least $minWords words; falls back to the
     * longest active text when none is long enough. Null when the bank is empty.
     */
    public function randomActive(int $minWords): ?TypingText {
        $text = TypingText::query()
            ->where('is_active', true)
            ->where('words_count', '>=', $minWords)
            ->inRandomOrder()
            ->first();

        return $text ?: TypingText::query()
            ->where('is_active', true)
            ->orderByDesc('words_count')
            ->first();
    }
}
