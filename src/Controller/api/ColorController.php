<?php

namespace App\Controller\api;

use App\Entity\Color;
use OpenApi\Attributes as OA;
use App\Repository\ColorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ColorController extends AbstractController
{

    #[OA\Response(
        response: 200,
        description: 'Retourne toutes les couleurs',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Color::class, groups: ['pen:read']))
        )
    )]
    #[Route('/colors', name: 'app_color', methods: ['GET'])]
    #[OA\Tag(name: 'Couleurs')]
    public function index(ColorRepository $colorRepository): Response
    {
        $color = $colorRepository->findAll();

        return $this->json([
            'color' => $color,
        ], context: [
            'groups' => ['pen:read']
        ]);
    }

    
    #[Route('/color/{id}', name: 'app_color_get', methods: ['GET'])]
    #[OA\Tag(name: 'Couleurs')]
    public function get(Color $color): JsonResponse
    {
        return $this->json($color, context: [
            'groups' => ['pen:read']
        ]);
    }

    #[Route('/color/{id}', name: 'app_color_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Couleurs')]
    public function delete(Color $color, EntityManagerInterface $em) {
        $em->remove($color);
        $em->flush();
        return $this->json([
            'code' => 200,
            'message' => "Supression réussie"
        ]);
    }

    #[Route('/color', name: 'app_color_add', methods: ['POST'])]
    #[OA\Tag(name: 'Couleurs')]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Color::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    public function add(Request $request, EntityManagerInterface $em, ColorRepository $ColorRepository)
    {
        try {
            $data = json_decode($request->getContent(), true);

            $color = new Color();
            $color->setName($data['name']);
            
            $em->persist($color);
            $em->flush();
            return $this->json($color, context: [
                'groups' => ['pen:read']
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/color/{id}', name: 'app_color_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Couleurs')]
    #[OA\Put(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Color::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    #[OA\Patch(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                ref: new Model(
                    type: Color::class,
                    groups: ['pen:create']
                )
            )
        )
    )]
    public function update(Color $color, Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $color->setName($data['name']);
            
            $em->persist($color);
            $em->flush();
            return $this->json($color, context: [
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

