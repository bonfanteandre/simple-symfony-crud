<?php

namespace App\Helper\Factory;

use App\Entity\Category;
use App\Entity\Task;
use App\Repository\CategoryRepository;
use App\Repository\TaskRepository;

class TaskFactory
{
    protected $repository;

    protected $categoryRepository;

    public function __construct(
        TaskRepository $repository, 
        CategoryRepository $categoryRepository
    ) {
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
    }

    public function create(array $taskData) : Task
    {
        $id = intval($taskData['id']);
        
        $task = $id > 0 ? $this->repository->find($id) : new Task();
        $task->setTitle($taskData['title']);
        $task->setDescription($taskData['description']);
        
        $category = $this->categoryRepository->find($taskData['category_id']);
        $task->setCategory($category);

        return $task;
    }
}