<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\PostKind;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
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
        $kinds = ['Technology','Style','Culture','Politics',
                'Opinion','Health','Business','Travel'];
        for($i = 0;$i<sizeof($kinds);$i++){
            $kind = new PostKind();
            $kind->setName($kinds[$i]);
            $manager->persist($kind);
            $this->addReference('kind_'.$i,$kind);
        }
        $faker = Factory::create('fr_Fr');
        for($i = 0;$i < 10;$i++){
            $user = new User();
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setEmail($faker->email);
            $user->setUsername($faker->userName);
            $user->setPassword($this->encoder->encodePassword($user,'secret'));
            $user->setBirthday($faker->dateTime());
            $manager->persist($user);
            
            for($k = 0;$k < mt_rand(15,30);$k++){
                $post = new Post();
                $post->setTitle($faker->realText(40));
                $post->setContent($faker->realText());
                $post->setAuthor($user);
                $post->setIsPulished((bool)random_int(0, 1));
                $kind = $this->getReference("kind_".rand(0, 7));
                $post->setPostKind($kind);
                $manager->persist($post);
            }
        }

        $manager->flush();
    }
}
