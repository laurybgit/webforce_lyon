<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
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
        /* $user = new User();
        $user->setEmail("test@test.fr");
        $user->setRoles(['ROLE_ADMIN']);
        $password = $this->encoder->encodePassword($user, 'webforce3');
        $user->setPassword($password);        


        $manager->persist($user); */

        //FAKERS

        $faker = Faker\Factory::create();
        for ($i = 1; $i <= 20; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $password = $this->encoder->encodePassword($user, 'webforce3');
            $user->setPassword($password);

            $randomRole = $faker->randomElements(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_APP']);
            $user->setRoles(array($randomRole));

           
            $manager->persist($user);
            $this->addReference('user_'.$i, $user);
            $manager->flush();
        }


         //LES Articles
        for ($i = 1; $i <= 50; $i++) {

            // $auteur = $this->getReference('user_'.rand(1, 20));
            $post = new Post();

            $post->setTitre($faker->sentence); 
            // $post->setResume($faker->paragraph($nbSentences = 3, $variableNbSentences = true));
            // $post->setContenu($faker->text($maxNbChars = 200));
            // $post->setCreatedAt(new \Datetime);
            // $post->setImage($faker->imageUrl($width = 640, $height = 480));

            // $post->setUser($auteur);

            $manager->persist($post);
            $manager->flush(); 
        }
    }
}
