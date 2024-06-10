<?php

namespace App\Controller\api;

use App\Entity\Brand;
use OpenApi\Attributes as OA;
use App\Repository\BrandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class BrandController extends AbstractController
{

    #[OA\Response(
        response: 200,
        description: 'Retourne toutes les marques',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Brand::class, groups: ['pen:read']))
        )
    )]
    #[Route('/brands', name: 'app_brand', methods: ['GET'])]
    #[OA\Tag(name: 'Marques')]
    public function index(BrandRepository $brandRepository): Response
    {
        $brand = $brandRepository->findAll();

        return $this->json([
            'brand' => $brand,
        ], context: [
            'groups' => ['pen:read']
        ]);
    }

    
    #[Route('/brand/{id}', name: 'app_brand_get', methods: ['GET'])]
    #[OA\Tag(name: 'Marques')]
    public function get(Brand $brand): JsonResponse
    {
        return $this->json($brand, context: [
            'groups' => ['pen:read']
        ]);
    }

    #[Route('/brand/{id}', name: 'app_brand_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Marques')]
    public function delete(Brand $brand, EntityManagerInterface $em) {
        $em->remove($brand);
        $em->flush();
        return $this->json([
            'code' => 200,
            'message' => "Supression réussie"
        ]);
    }

    #[Route('/brand', name: 'app_brand_add', methods: ['POST'])]
    #[OA\Tag(name: 'Marques')]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Brand::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    public function add(Request $request, EntityManagerInterface $em, BrandRepository $BrandRepository)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $brand = new Brand();
            $brand->setName($data['name']);
            
            $em->persist($brand);
            $em->flush();
            return $this->json($brand, context: [
                'groups' => ['pen:read']
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/brand/{id}', name: 'app_brand_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Marques')]
    #[OA\Put(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Brand::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    #[OA\Patch(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Brand::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    public function update(Brand $brand, Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $brand->setName($data['name']);
            
            $em->persist($brand);
            $em->flush();
            return $this->json($brand, context: [
                'groups' => ['pen:read']
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
