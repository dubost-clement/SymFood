<?php

namespace App\DataFixtures;

use App\Entity\Actuality;
use App\Entity\Category;
use App\Entity\Recipe;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        $users = [];

        for ($u = 0; $u < 10; $u++){
            $user = new User();
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setEmail($faker->email)
                ->setPassword($hash)
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
            ;

            $users[] = $user;
            $manager->persist($user);
        }

        for ($c = 0; $c < 4; $c++) {
            $category = new Category();
            $category->setTitle($faker->sentence(1));
            $manager->persist($category);


            for ($i = 0; $i < 6; $i++) {
                $recipe = new Recipe();
                $user = $users[mt_rand(0, count($users) -1 )];

                $recipe->setTitle($faker->sentence(3))
                    ->setDescription($faker->paragraph())
                    ->setCategory($category)
                    ->setUser($user)
                ;

                $manager->persist($recipe);
            }
        }

        for ($a = 0; $a < 10; $a++) {
            $actuality = new Actuality();

            $actuality->setTitle($faker->sentence(4))
                ->setDescription($faker->paragraph())
            ;

            $manager->persist($actuality);
        }

        $manager->flush();
    }
}
