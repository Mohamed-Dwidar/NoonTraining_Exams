<?php

namespace Modules\TypingTestModule\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\TypingTestModule\Services\TypingTestSettingService;
use Modules\TypingTestModule\Services\TypingTextService;

/** Admin + staff: bank of texts the typing test draws from. */
class TypingTextController extends Controller {
    private $textService;
    private $settingService;

    public function __construct(TypingTextService $textService, TypingTestSettingService $settingService) {
        $this->textService = $textService;
        $this->settingService = $settingService;
    }

    public function index() {
        $texts = $this->textService->findAll();
        $settings = $this->settingService->get();
        return view('typingtestmodule::admin.texts.index', compact('texts', 'settings'));
    }

    public function create() {
        $settings = $this->settingService->get();
        return view('typingtestmodule::admin.texts.create', compact('settings'));
    }

    public function store(Request $request) {
        $this->validateText($request);
        $text = $this->textService->create($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.typing.texts.index')
            ->with('success', 'تم إضافة النص بنجاح')
            ->with('warning', $this->shortTextWarning($text->words_count));
    }

    public function edit($id) {
        $text = $this->textService->find($id);
        $settings = $this->settingService->get();
        return view('typingtestmodule::admin.texts.edit', compact('text', 'settings'));
    }

    public function update(Request $request) {
        $request->validate(['id' => 'required|exists:typing_texts,id']);
        $this->validateText($request);
        $this->textService->update($request->all());
        $text = $this->textService->find($request->id);

        return redirect()->route(Auth::getDefaultDriver() . '.typing.texts.index')
            ->with('success', 'تم تحديث النص بنجاح')
            ->with('warning', $this->shortTextWarning($text->words_count));
    }

    public function delete($id) {
        $this->textService->delete($id);
        return redirect()->route(Auth::getDefaultDriver() . '.typing.texts.index')->with('success', 'تم حذف النص');
    }

    private function validateText(Request $request): void {
        $request->validate([
            'title'   => 'nullable|string|max:255',
            'content' => 'required|string',
        ], [
            'title.max'        => 'العنوان طويل جداً',
            'content.required' => 'نص الاختبار مطلوب',
        ]);
    }

    private function shortTextWarning(int $wordsCount): ?string {
        $required = $this->settingService->get()->word_count;
        return $wordsCount < $required
            ? "تنبيه: عدد كلمات النص ($wordsCount) أقل من عدد الكلمات المطلوب في الاختبار ($required)."
            : null;
    }
}
