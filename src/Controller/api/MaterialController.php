<?php

namespace App\Controller\api;

use App\Entity\Material;
use OpenApi\Attributes as OA;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]

class MaterialController extends AbstractController
{
    #[OA\Response(
        response: 200,
        description: 'Retourne tout les matériaux',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Material::class, groups: ['pen:read']))
        )
    )]
    #[Route('/materials', name: 'app_material', methods: ['GET'])]
    #[OA\Tag(name: 'Matériaux')]
    public function index(MaterialRepository $materialRepository): Response
    {
        $material = $materialRepository->findAll();

        return $this->json([
            'material' => $material,
        ], context: [
            'groups' => ['pen:read']
        ]);
    }

    
    #[Route('/material/{id}', name: 'app_material_get', methods: ['GET'])]
    #[OA\Tag(name: 'Matériaux')]
    public function get(Material $material): JsonResponse
    {
        return $this->json($material, context: [
            'groups' => ['pen:read']
        ]);
    }

    #[Route('/material/{id}', name: 'app_material_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Matériaux')]
    public function delete(Material $material, EntityManagerInterface $em) {
        $em->remove($material);
        $em->flush();
        return $this->json([
            'code' => 200,
            'message' => "Supression réussie"
        ]);
    }

    #[Route('/material', name: 'app_material_add', methods: ['POST'])]
    #[OA\Tag(name: 'Matériaux')]
        #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Material::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    public function add(Request $request, EntityManagerInterface $em, MaterialRepository $MaterialRepository)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $material = new Material();
            $material->setName($data['name']);
            
            $em->persist($material);
            $em->flush();
            return $this->json($material, context: [
                'groups' => ['pen:read']
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/material/{id}', name: 'app_material_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Matériaux')]
    #[OA\Put(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Material::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    #[OA\Patch(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Material::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    public function update(Material $material, Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $material->setName($data['name']);
            
            $em->persist($material);
            $em->flush();
            return $this->json($material, context: [
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
