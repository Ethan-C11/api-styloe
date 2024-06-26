<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Pen;
use App\Entity\Type;
use App\Entity\Material;
use App\Entity\Color;
use App\Entity\Brand;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $this->createUser($manager);
        $this->createAdmin($manager);
        $faker = Factory::create();

        // Création des types
        $types = [];
        foreach (['Bille', 'Plume', 'Rollerball', 'Feutre'] as $typeName) {
            $type = new Type();
            $type->setName($typeName);
            $manager->persist($type);
            $types[] = $type;
        }

        // Création des marques
        $brands = [];
        foreach (['Parker', 'Montblanc', 'Lamy', 'Waterman', 'Cross'] as $brandName) {
            $brand = new Brand();
            $brand->setName($brandName);
            $manager->persist($brand);
            $brands[] = $brand;
        }

        // Création des matériaux
        $materials = [];
        foreach (['Plastique', 'Métal', 'Bois', 'Acier', 'Aluminium'] as $materialName) {
            $material = new Material();
            $material->setName($materialName);
            $manager->persist($material);
            $materials[] = $material;
        }

        // Création des couleurs
        $colors = [];
        foreach (['Noir', 'Bleu', 'Rouge', 'Vert', 'Orange'] as $colorName) {
            $color = new Color();
            $color->setName($colorName);
            $manager->persist($color);
            $colors[] = $color;
        }

        // Génération de 10 stylos
        for ($i = 0; $i < 10; $i++) {
            $pen = new Pen();
            $pen->setName($faker->word);
            $pen->setPrice($faker->randomFloat(2, 5, 50));
            $pen->setDescription($faker->sentence);
            $pen->setRef($faker->unique()->ean13);

            $pen->setType($types[$faker->numberBetween(0, 3)]);
            $pen->setMaterial($materials[$faker->numberBetween(0, 4)]);
            $pen->setBrand($brands[$faker->numberBetween(0, 4)]);

            $colorCount = $faker->numberBetween(1, 3);
            for ($j = 0; $j < $colorCount; $j++) {
                $pen->addColor($colors[$faker->numberBetween(0, 4)]);
            }

            $manager->persist($pen);
        }

        $manager->flush();
    }

    public function createAdmin(ObjectManager $manager) {
        $user = new User();

        $user->setUsername('demo@apipen.fr');
        $user->setPassword(password_hash('azerty', PASSWORD_DEFAULT));
        $user->setRoles(array('ROLE_ADMIN'));

        $manager->persist($user);
        $manager->flush();
    }

    public function createUser(ObjectManager $manager) {
        $user = new User();

        $user->setUsername('demo-user@apipen.fr');
        $user->setPassword(password_hash('azerty', PASSWORD_DEFAULT));
        $user->setRoles(array('ROLE_USER'));

        $manager->persist($user);
        $manager->flush();
    }
}