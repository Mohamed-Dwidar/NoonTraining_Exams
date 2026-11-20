<?php

namespace Modules\TypingTestModule\Services;

use Modules\TypingTestModule\Repository\TypingTestSettingRepository;

class TypingTestSettingService {
    private $settingRepository;

    public function __construct(TypingTestSettingRepository $settingRepository) {
        $this->settingRepository = $settingRepository;
    }

    public function get() {
        return $this->settingRepository->current();
    }

    public function update(array $data) {
        $setting = $this->settingRepository->current();

        return $this->settingRepository->update([
            'description'      => $data['description'] ?? null,
            'duration_minutes' => (int) $data['duration_minutes'],
            'word_count'       => (int) $data['word_count'],
            'success_grade'    => (int) $data['success_grade'],
        ], $setting->id);
    }
}
