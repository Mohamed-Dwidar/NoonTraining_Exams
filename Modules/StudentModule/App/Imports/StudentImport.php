<?php

namespace Modules\StudentModule\App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use Modules\StudentModule\App\Http\Controllers\StudentModuleController;
use Modules\StudentModule\Services\StudentService;

class StudentImport implements ToCollection {
    // protected $controller;
    protected $studentService;

    // public function __construct(StudentModuleController $controller) {
    //     $this->controller = $controller;
    // }
    public function __construct(StudentService $studentService) {
        $this->studentService = $studentService;
    }

    public function collection(Collection $rows) {
        foreach ($rows as $key => $row) {
            // Skip the header row
            if ($key === 0) {
                continue;
            }

            $data = [
                'name'         => $row[0],
                'national_id'  => $row[1],
                'phone'        => $row[2],
                'email'        => $row[3],
                // 'gender'       => $row[4],
                'password'     =>  $row[1]
            ];
            //check if student already exists by national_id
            $existingStudent = $this->studentService->findByNationalId($data['national_id']);
            if ($existingStudent) {
                continue; // Skip this row if student already exists
            }

            $this->studentService->create($data);
        }
    }
}
