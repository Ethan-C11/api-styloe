<?php

namespace App\Service;

use Faker\Factory;
use App\Entity\Pen;
use App\Repository\TypeRepository;
use App\Repository\BrandRepository;
use App\Repository\ColorRepository;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;

class PenService
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MaterialRepository     $materialRepository,
        private readonly TypeRepository         $typeRepository,
        private readonly ColorRepository        $colorRepository,
        private readonly BrandRepository        $brandRepository
    )
    {
    }

    public function create(array $data)
    {
        $faker = Factory::create();

        // On traite les données pour créer un nouveau Produit
        $pen = new Pen();
        $pen->setName($data['name']);
        $pen->setPrice($data['price']);
        $pen->setDescription($data['description']);
        $pen->setRef($faker->unique()->ean13);

        if (!empty($data['type'])) {
            $type = $this->typeRepository->find($data['type']);

            if (!$type) {
                throw new \Exception("Le type renseigné n'est pas valide");
            }
            $pen->setType($type);
        }
        if (!empty($data['brand'])) {
            $brand = $this->brandRepository->find($data['type']);

            if (!$brand) {
                throw new \Exception("La marque renseignée n'est pas valide");
            }
            $pen->setBrand($brand);
        }
        if (!empty($data['material'])) {
            $material = $this->materialRepository->find($data['material']);

            if (!$material) {
                throw new \Exception("Le matériau renseigné n'est pas valide");
            }
            $pen->setMaterial($material);
        }
        if (!empty($data['color'])) {
            foreach ($data['color'] as $item) {
                $color = $this->colorRepository->find($item);

                if (!$color) {
                    throw new \Exception("La couleur renseignée n'est pas valide");
                }
                $pen->addColor($color);
            }
        }


        $this->entityManager->persist($pen);
        $this->entityManager->flush();

        return $pen;
    }

    public function update(Pen $pen, array $data)
    {
        if(!empty($data["name"])) 
            $pen->setName($data['name']);
        if(!empty($data['price']))
            $pen->setPrice($data['price']);
        if(!empty($data['description']))
            $pen->setDescription($data['description']);

        if (!empty($data['type'])) {
            $type = $this->typeRepository->find($data['type']);

            if (!$type) {
                throw new \Exception("Le type renseigné n'est pas valide");
            }
            $pen->setType($type);
        }
        if (!empty($data['brand'])) {
            $brand = $this->brandRepository->find($data['type']);

            if (!$brand) {
                throw new \Exception("La marque renseignée n'est pas valide");
            }
            $pen->setBrand($brand);
        }
        if (!empty($data['material'])) {
            $material = $this->materialRepository->find($data['material']);

            if (!$material) {
                throw new \Exception("Le matériau renseigné n'est pas valide");
            }
            $pen->setMaterial($material);
        }
        if (!empty($data['color'])) {
            foreach($pen->getColor() as $color) {
                $pen->removeColor($color);
            }

            foreach($data['color'] as $colorId) {
                $color = $this->colorRepository->find($colorId);
                if (!$color)
                    throw new \Exception('Couleur(s) non valide');
                $pen->addColor($color);
            }
        }
        

        $this->entityManager->persist($pen);
        $this->entityManager->flush();

        return $pen;
    }

    public function delete(Pen $pen) {
        $this->entityManager->remove($pen);
        $this->entityManager->flush();
    }

}