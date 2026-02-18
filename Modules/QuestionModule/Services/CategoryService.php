<?php

namespace Modules\QuestionModule\Services;


use Illuminate\Validation\ValidationException;
use Modules\QuestionModule\Repository\CategoryRepository;

class CategoryService {
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function findAll() {
        return $this->categoryRepository->all();
    }

    public function paginate($perPage = 15) {
        return $this->categoryRepository->paginate($perPage);
    }

    public function find($id) {
        return $this->categoryRepository->find($id);
    }

    /**
     * Create a single category
     */
    public function create($data) {
        $category_date = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ];
        return $this->categoryRepository->create($category_date);
    }

    /**
     * Update a category
     */
    public function update($data) {
        $category_date = [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ];
        return $this->categoryRepository->update($category_date, $data['id']);
    }


    public function delete($id) {
        return $this->categoryRepository->delete($id);
    }
}
