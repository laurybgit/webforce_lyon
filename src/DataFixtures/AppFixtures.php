<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
 use App\Entity\User;
 use App\Entity\Post;

 use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

 use Faker;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        /*$user = new User();
        $user->setEmail('test@test.fr');
        $user->setRoles(['ROLE_ADMIN']);
        $password = $this->encoder->encodePassword($user, 'webforce3');
        $user->setPassword($password);
        
        //$user->setPassword('webforce3');

        $manager->persist($user);*/

        //LES FAKERS

        $faker = Faker\Factory::create();
        for ($i = 1; $i <= 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email);

            $password = $this->encoder->encodePassword($user, 'webforce3');
            $user->setPassword($password);

            $user->setRoles($faker->randomElements(['ROLE_USER','ROLE_ADMIN','ROLE_APP']));

            $user->setNom($faker->firstName);
            $user->setPrenom($faker->lastName);

            $this->addReference('user_'.$i, $user);

            $manager->persist($user);
            $manager->flush();
        }


        // LES ARTICLES
        for ($i = 1; $i <= 10; $i++) {

            $auteur = $this->getReference('user_'.rand(1,10));

            $article = new Post();

            $article->setTitre($faker->sentence( 6,true));


            $article->setResume($faker->paragraph($nbSentences = 3, $variableNbSentences = true));
            $article->setContenu($faker->text($maxNbChars = 600));
            $article->setCreatedAt(new \Datetime);
            $article->setImage($faker->imageUrl($width = 640, $height = 480));
            
            $article->setUser($auteur);

            $manager->persist($article);
            $manager->flush();
        }
    }
}
