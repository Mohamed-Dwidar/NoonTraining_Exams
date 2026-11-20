<?php

namespace Modules\TypingTestModule\Repository;

use Modules\TypingTestModule\App\Http\Models\TypingTestSetting;
use Prettus\Repository\Eloquent\BaseRepository;

class TypingTestSettingRepository extends BaseRepository {
    function model() {
        return TypingTestSetting::class;
    }

    public function current(): TypingTestSetting {
        return TypingTestSetting::query()->orderBy('id')->firstOrFail();
    }
}
