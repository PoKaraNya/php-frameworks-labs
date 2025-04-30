<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 *
 */
#[Route('api/category', name: 'category_routes')]
class CategoryController extends AbstractController
{
    public const ITEMS_PER_PAGE = 2;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[IsGranted("ROLE_USER")]
    #[Route('/', name: 'get_categories', methods: ['GET'])]
    public function getCategories(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $itemsPerPage = isset($requestData['itemsPerPage']) ? (int)$requestData['itemsPerPage'] : self::ITEMS_PER_PAGE;
        $page = isset($requestData['page']) ? (int)$requestData['page'] : 1;

        $data = $this->categoryRepository->getAllByFilter($requestData, $itemsPerPage, $page);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[IsGranted("ROLE_MANAGER")]
    #[Route('/', name: 'create_category', methods: ['POST'])]
    public function createCategory(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $category = new Category();
        $category->setName($data['name'] ?? '');
        $category->setDescription($data['description'] ?? '');

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return new JsonResponse($category->jsonSerialize(), Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[IsGranted("ROLE_USER")]
    #[Route('/{id}', name: 'get_category', methods: ['GET'])]
    public function getCategory(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'],   Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($category->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    #[IsGranted("ROLE_MANAGER")]
    #[Route('/{id}', name: 'update_category', methods: ['PATCH'])]
    public function updateCategory(Request $request, int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $category->setName($data['name']);
        }

        if (isset($data['description'])) {
            $category->setDescription($data['description']);
        }

        $this->entityManager->flush();

        return new JsonResponse($category->jsonSerialize(), Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/{id}', name: 'delete_category', methods: ['DELETE'])]
    public function deleteCategory(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);

        if (!$category) {
            return new JsonResponse(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Category deleted successfully'], Response::HTTP_OK);
    }
}
