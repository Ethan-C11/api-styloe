<?php

namespace App\Controller\api;

use App\Entity\Pen;
use App\Service\PenService;
use OpenApi\Attributes as OA;
use App\Repository\PenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api')]

class PenController extends AbstractController
{
    #[OA\Response(
        response: 200,
        description: 'Retourne tous les stylos.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Pen::class, groups: ['pen:read', 'pen:create']))
        )
    )]

    #[Route('/pens', name: 'app_pens', methods: ['GET'])]
    #[OA\Tag(name: 'Stylos')]
    #[Security(name: 'Bearer')]
    public function index(PenRepository $penRepository): JsonResponse
    {
        $pens = $penRepository->findAll();

        return $this->json([
            'pens' => $pens,
        ], context: [
            'groups' => ['pen:read', 'pen:create']
        ]);
    }

    #[Route('/pen/{id}', name: 'app_pen_get', methods: ['GET'])]
    #[OA\Tag(name: 'Stylos')]
    public function get(Pen $pen): JsonResponse
    {
        return $this->json($pen, context: [
            'groups' => ['pen:read', 'pen:create']
        ]);
    }

    #[Route('/pen/{id}', name: 'app_pen_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Stylos')]
    #[OA\Put(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Pen::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    #[OA\Patch(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Pen::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    public function update(Request $request ,Pen $pen, PenService $penService): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $penService->update($pen, $data);
            return $this->json($pen, context: [
                'groups' => ['pen:create'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/pen/{id}', name: 'app_pen_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Stylos')]
    public function delete(Pen $pen, PenService $PenService) {
        try{
            $PenService->delete($pen);
            return $this->json([
                'code' => 200,
                'message' => "Supression réussie"
            ], 200);
        }catch(\Exception $e) {
            return $this->json([
                'code' => 500,
                'message' => $e->getMessage()
            ], 500);
        }

    }


    #[Route('/pen', name: 'app_pen_add', methods: ['POST'])]
    #[OA\Tag(name: 'Stylos')]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Pen::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    public function add(Request $request ,Pen $pen , PenService $penService)
    {
        try {
            $data = json_decode($request->getContent(), true);
            $pen = $penService->create($data);
            return $this->json($pen, context: [
                'groups' => ['pen:create'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }
}