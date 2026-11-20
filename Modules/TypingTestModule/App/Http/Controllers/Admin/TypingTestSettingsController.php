<?php

namespace Modules\TypingTestModule\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TypingTestModule\Services\TypingTestSettingService;

/** Main admin only: description, duration, word count and pass mark. */
class TypingTestSettingsController extends Controller {
    private $settingService;

    public function __construct(TypingTestSettingService $settingService) {
        $this->settingService = $settingService;
    }

    public function edit() {
        $settings = $this->settingService->get();
        return view('typingtestmodule::admin.settings', compact('settings'));
    }

    public function update(Request $request) {
        $request->validate([
            'description'      => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1|max:600',
            'word_count'       => 'required|integer|min:1|max:5000',
            'success_grade'    => 'required|integer|min:1|lte:word_count',
        ], [
            'duration_minutes.required' => 'مدة الاختبار مطلوبة',
            'duration_minutes.integer'  => 'مدة الاختبار يجب أن تكون رقماً صحيحاً',
            'duration_minutes.min'      => 'مدة الاختبار يجب أن تكون دقيقة واحدة على الأقل',
            'duration_minutes.max'      => 'مدة الاختبار كبيرة جداً',
            'word_count.required'       => 'عدد الكلمات مطلوب',
            'word_count.integer'        => 'عدد الكلمات يجب أن يكون رقماً صحيحاً',
            'word_count.min'            => 'عدد الكلمات يجب أن يكون كلمة واحدة على الأقل',
            'word_count.max'            => 'عدد الكلمات كبير جداً',
            'success_grade.required'    => 'درجة النجاح مطلوبة',
            'success_grade.integer'     => 'درجة النجاح يجب أن تكون رقماً صحيحاً',
            'success_grade.min'         => 'درجة النجاح يجب أن تكون 1 على الأقل',
            'success_grade.lte'         => 'درجة النجاح لا يمكن أن تتجاوز عدد الكلمات',
        ]);

        $this->settingService->update($request->all());

        return redirect()->route('admin.typing.settings.edit')->with('success', 'تم حفظ إعدادات أختبار سرعة الكتابة');
    }
}
