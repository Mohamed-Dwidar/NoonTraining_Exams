<?php

namespace Modules\TypingTestModule\App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\TypingTestModule\Services\TypingTestService;

/** Admin + staff: results list, attempt details, assign/unassign the standalone test. */
class TypingResultController extends Controller {
    private $typingTestService;

    public function __construct(TypingTestService $typingTestService) {
        $this->typingTestService = $typingTestService;
    }

    public function index(Request $request) {
        $attempts = $this->typingTestService->results($request->all())->paginate(25)->withQueryString();
        return view('typingtestmodule::admin.results.index', compact('attempts'));
    }

    public function show($id) {
        $attempt = $this->typingTestService->find($id);
        $evaluation = $this->typingTestService->evaluation($attempt);
        return view('typingtestmodule::admin.results.show', compact('attempt', 'evaluation'));
    }

    public function assign(Request $request) {
        $request->validate(['student_id' => 'required|exists:students,id']);

        $attempt = $this->typingTestService->assignToStudent((int) $request->student_id, Auth::id());

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'message'    => 'تم تعيين الاختبار بنجاح',
                'attempt_id' => $attempt->id,
            ]);
        }

        return back()->with('success', 'تم ربط الطالب بأختبار سرعة الكتابة');
    }

    public function unassign(Request $request, $id) {
        $done = $this->typingTestService->unassign((int) $id);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $done,
                'message' => $done ? 'تم إلغاء تعيين الاختبار بنجاح' : 'لا يمكن إلغاء تعيين اختبار تم إجراؤه بالفعل',
            ], $done ? 200 : 400);
        }

        if (!$done) {
            return back()->withErrors(['typing' => 'لا يمكن إلغاء محاولة بدأها الطالب بالفعل']);
        }
        return back()->with('success', 'تم إلغاء ربط أختبار سرعة الكتابة');
    }
}
