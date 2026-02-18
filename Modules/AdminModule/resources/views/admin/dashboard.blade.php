@extends('layoutmodule::admin.main')

@section('title')
    لوحة تحكم الأختبارات
@endsection

@section('content')
    <div class="content-wrapper container-fluid">

        @include('layoutmodule::admin.flash')

        <div class="content-body">
            <div class="row">

                <!-- Total Exams -->
                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="fa fa-file teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $totalExams }}</h3>
                                        <h5>عدد الأختبارات الكلي</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Exams -->
                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="fa fa-calendar-plus-o teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $upcomingExams }}</h3>
                                        <h5>الأختبارات القادمة</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ongoing Exams -->
                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="fa fa-hourglass-half teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $ongoingExams }}</h3>
                                        <h5>الأختبارات الجارية</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Finished Exams -->
                <div class="col-xl-3 col-lg-6 col-xs-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-block">
                                <div class="media">
                                    <div class="media-left media-middle">
                                        <i class="fa fa-check-square-o teal font-large-2 float-xs-right"></i>
                                    </div>
                                    <div class="media-body text-xs-right">
                                        <h3 class="teal">{{ $finishedExams }}</h3>
                                        <h5>الأختبارات المنتهية</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- You can add more exam-related widgets here -->

        </div>
    </div>
@endsection
