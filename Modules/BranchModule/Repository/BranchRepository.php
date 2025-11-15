<?php

namespace Modules\BranchModule\Repository;

use Modules\BranchModule\app\Http\Models\Branch;

class BranchRepository
{

    function create($data)
    {
        $branch = Branch::create($data);
        return $branch;
    }

    function update($data)
    {
        $branch = Branch::where('id', $data['id'])->first();
        $branch->update($data);
        return $branch;
    }

    function findAll()
    {
        return Branch::all();
    }

    function findOne($id)
    {
        return Branch::where('id', $id)->first();
    }

    public function delete($id)
    {
        Branch::where('id', $id)->delete();
        return true;
    }

    function getByIds($ids)
    {
        return Branch::whereIN('id', $ids)->get();
    }

    function getField($id, $field)
    {
        $branch =  Branch::where('id', $id)->first();
        return $branch[$field];
    }
}
