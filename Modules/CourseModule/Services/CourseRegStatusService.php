<?php

namespace Modules\CourseModule\Services;

use Modules\CourseModule\Repository\CourseRegStatusRepository;

class CourseRegStatusService
{
    private $courseRegStatusRepository;

    public function __construct(CourseRegStatusRepository $courseRegStatusRepository)
    {
        $this->courseRegStatusRepository = $courseRegStatusRepository;
    }

    public function findAll()
    {
        return $this->courseRegStatusRepository->get();
    }
}
