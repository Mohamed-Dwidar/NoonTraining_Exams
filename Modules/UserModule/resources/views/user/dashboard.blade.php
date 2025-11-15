@extends('layoutmodule::admin.main')

@section('title')
{{ __('messages.admin') }}
@endsection

@section('content')

<div class="content-wrapper container-fluid">
    {{-- <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3>
                <i class="fa fa-list"></i>
                &nbsp;
                طلبات التسجيل في الدورات
            </h3> 
        </div>
    </div> --}}

    @include('layoutmodule::admin.flash')

    <div class="content-body">
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <div class="media">
                                <div class="media-left media-middle">
                                    <i class="fa fa-desktop teal font-large-2 float-xs-right"></i>
                                </div>
                                <div class="media-body text-xs-right">
                                    {{-- <h3 class="teal">{{$courses_regs->count()}}</h3>  --}}
                                    <h3 class="teal">0000000</h3>
                                    <h5>الدورات</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <div class="media">
                                <div class="media-left media-middle">
                                    <i class="fa fa-graduation-cap teal font-large-2 float-xs-right"></i>
                                </div>
                                <div class="media-body text-xs-right">
                                    {{-- <h3 class="teal">{{$courses_regs->sum('price')}}</h3>  --}}
                                    <h3 class="teal">0000000</h3>
                                    
                                    <h5>الطلاب</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <div class="media">
                                <div class="media-left media-middle">
                                    <i class="icon icon-money1 teal font-large-2 float-xs-right"></i>
                                </div>
                                <?php
                                    // $paid = 0 ;
                                    // if(!empty($courses_regs)){
                                    //     foreach ($courses_regs as $one_reg) {
                                    //         $paid = $paid + $one_reg->payments->sum('amount');
                                    //     }
                                    // }
                                ?>
                                <div class="media-body text-xs-right">
                                    {{-- <h3 class="teal">{{$paid}} --}}
                                        <h3 class="teal">0000000</h3>
                                    <h5>الإيراد</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-block">
                            <div class="media">
                                <div class="media-left media-middle">
                                    <i class="fa fa-money teal font-large-2 float-xs-right"></i>
                                </div>
                                <div class="media-body text-xs-right">
                                    {{-- <h3 class="teal">{{$courses_regs->sum('price') - $paid}}</h3> --}}
                                    <h3 class="teal">0000000</h3>
                                    <h5>المبالغ غير مسددة</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 




        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                   
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

 