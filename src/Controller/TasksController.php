<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Task;
use App\Helper\Factory\TaskFactory;
use App\Repository\TaskRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TasksController extends AbstractController
{
    protected $entityManager;

    protected $repository;

    protected $factory;

    protected $categoryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TaskRepository $repository,
        TaskFactory $factory,
        CategoryRepository $categoryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->categoryRepository = $categoryRepository;
    }
    
    /**
     * @Route("/tasks", methods={"GET"}, name="tasks")
     */
    public function index()
    {
        $tasks = $this->repository->findAll();

        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/tasks/new", methods={"GET"}, name="newTask")
     */
    public function new()
    {
        $task = new Task();
        $task->setCategory(new Category());
        return $this->render('tasks/form.html.twig', [
            'task' => $task,
            'categories' => $categories = $this->categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/tasks/edit/{id}", methods={"GET"}, name="editTask")
     */
    public function edit($id)
    {
        return $this->render('tasks/form.html.twig', [
            'task' => $this->repository->find($id),
            'categories' => $categories = $this->categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/tasks/save", methods={"POST"}, name="saveTask")
     */
    public function save(Request $request)
    {
        $form = $request->request->all();
        $task = $this->factory->create($form);
        
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->get('session')->getFlashBag()->clear();
        $this->addFlash('success', 'Tarefa salva com sucesso!');

        return $this->redirectToRoute('tasks');
    }

    /**
     * @Route("/tasks/delete/{id}", methods={"GET"}, name="deleteTask")
     */
    public function delete(int $id)
    {
        $task = $this->repository->find($id);
        
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        $this->get('session')->getFlashBag()->clear();
        $this->addFlash('success', 'Tarefa removida com sucesso!');

        return $this->redirectToRoute('tasks');
    }
}
