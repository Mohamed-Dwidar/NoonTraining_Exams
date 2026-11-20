<?php

namespace Modules\TypingTestModule\Services;

/**
 * Word-by-word, position-based comparison between the source text and what the student typed.
 *
 * Rules (mirrored in resources/views/student/partials/typing_js.blade.php):
 * - Typed word #i is compared with source word #i. A skipped word therefore shifts every
 *   following word, so all of them become wrong.
 * - Diacritics (tashkeel) and punctuation are part of the word.
 * - أ / إ / آ / ٱ / ا are the same letter, ة = ه, ى = ي.
 * - Any run of whitespace separates words (a double space between words is not an error;
 *   a space inside a word splits it into two words, which makes them wrong).
 * - Words not reached are not counted. The last typed word, if it is only an unfinished
 *   prefix of its source word (no trailing space), is treated as not reached.
 */
class TypingEvaluator {
    public const CORRECT = 'correct';
    public const WRONG = 'wrong';
    public const PENDING = 'pending';

    public static function words(?string $text): array {
        $text = preg_replace('/[\x{200B}\x{200E}\x{200F}\x{FEFF}]/u', '', (string) $text);
        $words = preg_split('/[\s\x{00A0}]+/u', trim($text), -1, PREG_SPLIT_NO_EMPTY);
        return $words ?: [];
    }

    public static function normalize(string $word): string {
        return str_replace(['أ', 'إ', 'آ', 'ٱ', 'ة', 'ى'], ['ا', 'ا', 'ا', 'ا', 'ه', 'ي'], $word);
    }

    /**
     * @return array{typed_words:int, correct:int, wrong:int, source:array, statuses:array, extra:array}
     *   statuses[i] is the status of source word i (correct|wrong|pending);
     *   extra holds typed words beyond the end of the source (all wrong).
     */
    public static function evaluate(?string $source, ?string $typed): array {
        $sourceWords = self::words($source);
        $typedWords = self::words($typed);
        $typedCount = count($typedWords);
        $endsWithSpace = (bool) preg_match('/[\s\x{00A0}]$/u', (string) $typed);

        $statuses = array_fill(0, count($sourceWords), self::PENDING);
        $extra = [];
        $correct = 0;
        $wrong = 0;

        foreach ($typedWords as $i => $typedWord) {
            if (!isset($sourceWords[$i])) {
                $extra[] = $typedWord;
                $wrong++;
                continue;
            }

            $s = self::normalize($sourceWords[$i]);
            $t = self::normalize($typedWord);

            if ($s === $t) {
                $statuses[$i] = self::CORRECT;
                $correct++;
            } elseif ($i === $typedCount - 1 && !$endsWithSpace && mb_strpos($s, $t) === 0) {
                // Unfinished last word: not counted as right or wrong
                $statuses[$i] = self::PENDING;
            } else {
                $statuses[$i] = self::WRONG;
                $wrong++;
            }
        }

        return [
            'typed_words' => $typedCount,
            'correct'     => $correct,
            'wrong'       => $wrong,
            'source'      => $sourceWords,
            'statuses'    => $statuses,
            'extra'       => $extra,
        ];
    }

    /** First $count words of $text joined by single spaces. */
    public static function firstWords(string $text, int $count): string {
        return implode(' ', array_slice(self::words($text), 0, $count));
    }
}
