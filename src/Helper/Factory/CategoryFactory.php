<?php

namespace App\Helper\Factory;

use App\Entity\Category;
use App\Repository\CategoryRepository;

class CategoryFactory
{
    protected $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $categoryData) : Category
    {
        $id = intval($categoryData['id']);
        
        $category = $id > 0 ? $this->repository->find($id) : new Category();
        $category->setName($categoryData['name']);

        return $category;
    }
}