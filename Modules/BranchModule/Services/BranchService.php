<?php

namespace Modules\BranchModule\Services;

use App\Helpers\UploaderHelper;
use Modules\BranchModule\Repository\BranchRepository;

class BranchService
{
    private $branchRepository;
    use UploaderHelper;

    public function __construct(BranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function create($data)
    {
        $branch_data = [
            'name' => $data->name,
            'address' => $data->address,
            'is_available' => 1,
            // 'is_available' => $data->is_available,
        ];
        return $this->branchRepository->create($branch_data);
    }

    public function update($data)
    {
        $branch_data = [
            'id' => $data->id,
            'name' => $data->name,
            'address' => $data->address,
            'is_available' => 1,
            // 'is_available' => $data->is_available,
        ];

        return $this->branchRepository->update($branch_data);
    }

    public function findAll()
    {
        return $this->branchRepository->findAll();
    }

    public function findOne($id)
    {
        return $this->branchRepository->findOne($id);
    }

    public function deleteOne($id)
    {
        return $this->branchRepository->delete($id);
    }

    public function deleteMany($arr_ids)
    {
        if (!empty($arr_ids)) {
            foreach ($arr_ids as $id) {
                return $this->branchRepository->delete($id);
            }
        }
    }
}
