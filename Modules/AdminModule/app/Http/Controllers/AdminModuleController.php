<?php

namespace Modules\AdminModule\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\CourseModule\Services\CourseRegService;
use Modules\CourseModule\Services\CourseService;
use Modules\StudentModule\Services\StudentService;

class AdminModuleController extends Controller
{
    private $courseService;
    private $studentService;
    private $courseRegService;

    public function __construct(CourseService $courseService, StudentService $studentService, CourseRegService $courseRegService)
    {
        $this->courseService = $courseService;
        $this->studentService = $studentService;
        $this->courseRegService = $courseRegService;
    }

    public function dashboard()
    {
        $students = $courses = $tot_amounts = $rest_amounts = 0;
        
        $data = [
            'students' => $students,
            'courses' => $courses,
            'tot_amounts' => $tot_amounts,
            'rest_amounts' => $rest_amounts
        ];
        return view('adminmodule::admin.dashboard', $data);
    }
}
