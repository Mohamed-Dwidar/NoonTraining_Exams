<?php

namespace Modules\BranchModule\app\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\BranchModule\Services\BranchService;

class BranchAdminController extends Controller
{
    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $branchs = $this->branchService->findAll();
        return view('branchmodule::admin.index', compact('branchs'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('branchmodule::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ],
            [
                'name.required' => 'يجب ادخال اسم الفرع',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $this->branchService->create($request);

        return redirect()->route(Auth::getDefaultDriver().'.branchs')
            ->with('success', 'تم الاضافه بنجاح.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $branch = $this->branchService->findOne($id);
        return view('branchmodule::admin.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $branch = $this->branchService->findOne($id);
        return view('branchmodule::admin.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                // 'details' => 'required'
            ],
            [
                'name.required' => 'يجب ادخال اسم الفرع',
                // 'details.required' => 'يجب ادخال تفاصيل الفرع'
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $this->branchService->update($request);

        return redirect()->route(Auth::getDefaultDriver().'.branchs')
            ->with('success', 'تم التعديل بنجاح.');
            // return redirect()->route(Auth::getDefaultDriver().'.branchs.view', $request->id)
            //     ->with('success', 'تم التعديل بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->branchService->deleteOne($id);
        return redirect()->route(Auth::getDefaultDriver().'.branchs')
            ->with('success', 'حذف الفرع بنجاح.');
    }
}
