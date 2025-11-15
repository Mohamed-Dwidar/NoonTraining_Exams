@extends('layoutmodule::admin.main')

@section('title')
الفروع
@endsection


@section('content')

<div class="content-wrapper container-fluid">
    <div class="content-header">
        <div class="content-header-left mb-2 breadcrumb-new col">
            <h3>
                <i class="icon-grid2"></i>
                &nbsp;
                الفروع
            </h3>
        </div>
    </div>

    @include('layoutmodule::admin.flash')

    <div class="content-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            {{-- <div class="col-5">
                                <h2> الفروع</h2>
                            </div> --}}
                            <div class="col-lg-10"></div>
                            <div class="col-lg-2">
                                <a class="btn btn-success round btn-min-width mr-1 mb-1"
                                    href="{{route(Auth::getDefaultDriver().'.branchs.add')}}" role="button">أضف فرع جديد</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr class="head">
                                        <th>اسم الفرع</th>
                                        {{-- <th>العنوان</th> --}}
                                        {{-- <th>&nbsp;</th> --}}
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(!empty($branchs))
                                    @foreach ($branchs as $branch)
                                    <tr>
                                        <td class="strong">{{$branch->name}}</td>
                                        {{-- <td>
                                            @if($branch->is_available == 1) 
                                            نعم
                                            @else
                                            لا
                                             @endif
                                        </td> --}}
                                        {{-- <td>{{$branch->address}}</td> --}}
                                        <td class="action">
                                            <a class="btn btn-warning"
                                                href="{{route(Auth::getDefaultDriver().'.branchs.edit',$branch->id)}}"
                                                role="button">تعديل</a>

                                            {{-- <a class="btn btn-danger"
                                                href="{{route(Auth::getDefaultDriver().'.branchs.delete',$branch->id)}}"
                                                onclick="return confirm('هل انت متأكد انك تريد حذف هذا الفرع ؟')"
                                                role="button">حذف</a> --}}
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection