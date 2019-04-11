<?php

namespace App\Controller;

use App\Entity\Category;
use App\Helper\Factory\CategoryFactory;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoriesController extends AbstractController
{
    protected $entityManager;

    protected $repository;

    protected $factory;

    public function __construct(
        EntityManagerInterface $entityManager,
        CategoryRepository $repository,
        CategoryFactory $factory 
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @Route("/categories", methods={"GET"}, name="categories")
     */
    public function index()
    {
        $categories = $this->repository->findAll();

        return $this->render('categories/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/categories/new", methods={"GET"}, name="newCategory")
     */
    public function new()
    {
        return $this->render('categories/form.html.twig', [
            'category' => new Category()
        ]);
    }

    /**
     * @Route("/categories/edit/{id}", methods={"GET"}, name="editCategory")
     */
    public function edit($id)
    {
        $category = $this->repository->find($id);

        return $this->render('categories/form.html.twig', [
            'category' => $category
        ]);
    }
    
    /**
     * @Route("/categories/save", methods={"POST"}, name="saveCategory")
     */
    public function save(Request $request)
    {
        $form = $request->request->all();
        $category = $this->factory->create($form);
        
        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $this->get('session')->getFlashBag()->clear();
        $this->addFlash('success', 'Categoria salva com sucesso!');

        return $this->redirectToRoute('categories');
    }

    /**
     * @Route("/categories/delete/{id}", methods={"GET"}, name="deleteCategory")
     */
    public function delete(int $id)
    {
        $category = $this->repository->find($id);
        
        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $this->get('session')->getFlashBag()->clear();
        $this->addFlash('success', 'Categoria removida com sucesso!');

        return $this->redirectToRoute('categories');
    }
}
