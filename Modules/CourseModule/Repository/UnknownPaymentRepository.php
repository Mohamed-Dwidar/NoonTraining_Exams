<?php

namespace Modules\CourseModule\Repository;

use Modules\CourseModule\app\Http\Models\UnknownPayment;
use Prettus\Repository\Eloquent\BaseRepository;
 

class UnknownPaymentRepository extends BaseRepository
{
    function model()
    {
        return UnknownPayment::class;
    }

    public function filter()
    {
        return UnknownPayment::filter()->get();
    }
    
}
