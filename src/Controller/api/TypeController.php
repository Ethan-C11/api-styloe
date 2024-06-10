<?php

namespace App\Controller\api;

use App\Entity\Type;
use OpenApi\Attributes as OA;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class TypeController extends AbstractController
{
    #[OA\Response(
        response: 200,
        description: 'Retourne tout les types',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Type::class, groups: ['pen:read']))
        )
    )]
    #[Route('/types', name: 'app_type', methods: ['GET'])]
    #[OA\Tag(name: 'Types')]
    public function index(TypeRepository $typeRepository): Response
    {
        $type = $typeRepository->findAll();

        return $this->json([
            'type' => $type,
        ], context: [
            'groups' => ['pen:read']
        ]);
    }

    
    #[Route('/type/{id}', name: 'app_type_get', methods: ['GET'])]
    #[OA\Tag(name: 'Types')]
    public function get(Type $type): JsonResponse
    {
        return $this->json($type, context: [
            'groups' => ['pen:read']
        ]);
    }

    #[Route('/type/{id}', name: 'app_type_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Types')]
    public function delete(Type $type, EntityManagerInterface $em) {
        $em->remove($type);
        $em->flush();
        return $this->json([
            'code' => 200,
            'message' => "Supression réussie"
        ]);
    }

    #[Route('/type', name: 'app_type_add', methods: ['POST'])]
    #[OA\Tag(name: 'Types')]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Type::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    public function add(Request $request, EntityManagerInterface $em, TypeRepository $TypeRepository)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $type = new Type();
            $type->setName($data['name']);
            
            $em->persist($type);
            $em->flush();
            return $this->json($type, context: [
                'groups' => ['pen:read']
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/type/{id}', name: 'app_type_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Types')]
    #[OA\Put(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Type::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    #[OA\Patch(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Type::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    public function update(Type $type, Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $type->setName($data['name']);
            
            $em->persist($type);
            $em->flush();
            return $this->json($type, context: [
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
