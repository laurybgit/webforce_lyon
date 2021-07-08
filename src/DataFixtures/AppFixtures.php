<?php

namespace App\DataFixtures;

use Faker;
use APP\Entity\Article;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture {
    private $encoder;
    const REF_USER_OBJECT = 'user-object';
    const NB_USERS = 20;
    const NB_ARTICLES = 50;
    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager) {
        $faker = Faker\Factory::create();

        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < self::NB_USERS; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setRoles([$faker->randomElement(['ROLE_ADMIN', 'ROLE_USER', 'ROLE_CUSTOM'])]);
            $password = $this->encoder->encodePassword($user, 'pizzapizza');
            $user->setPassword($password);
            $user->setName($faker->lastName);
            $this->addReference(self::REF_USER_OBJECT . $i, $user);
            $manager->persist($user);
        }



        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence());
            $article->setDescription($faker->paragraph(1));
            $article->setUser($this->getReference(self::REF_USER_OBJECT . rand(0, self::NB_USERS - 1)));
            $article->setImage($faker->imageUrl);
            $article->setContent($faker->paragraph());
            $manager->persist($article);
        }

        $manager->flush();
    }
}
