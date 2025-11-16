<?php

namespace Modules\AdminModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ExamModule\Services\ExamService;

class AdminModuleController extends Controller
{
    private $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }


    public function dashboard()
    {
        $totalExams = $this->examService->countAll();
        $upcomingExams = $this->examService->countUpcoming();
        $ongoingExams = $this->examService->countOngoing();
        $finishedExams = $this->examService->countFinished();

        $data = [
            'totalExams' => $totalExams,
            'upcomingExams' => $upcomingExams,
            'ongoingExams' => $ongoingExams,
            'finishedExams' => $finishedExams,
        ];

        return view('adminmodule::admin.dashboard', $data);
    }
}
