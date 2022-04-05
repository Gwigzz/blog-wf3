<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Post;
use App\Entity\User;
/* Import entity for fixtures */
use App\Entity\Comment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    /**
     * insert in db with cli : symfony console doctrine:fixtures:load
     */
    // Configuration des constantes pour la création des éléments
    private const MAX_USER = 47;
    private const MAX_POST_PUBLICATION = 100;
    private const MAX_POST_COMMENT = 20;

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // Instance de Faker en location Française
        $faker = Faker\Factory::create('fr_FR');

        // Création d'un compte ROLE_ADMIN
        $admin = new User();
        $admin
            ->setEmail('a@a.a')
            ->setUsername('admin')
            ->setPassword($this->hasher->hashPassword($admin, 'a'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // Création d'un compte ROLE_BLOGGER
        $blogger = new User();
        $blogger
            ->setEmail('b@b.b')
            ->setUsername('blogger')
            ->setPassword($this->hasher->hashPassword($blogger, 'b'))
            ->setRoles(['ROLE_BLOGGER']);
        $manager->persist($blogger);

        // Création d'un compte ROLE_USER
        $user = new User();
        $user
            ->setEmail('u@u.u')
            ->setUsername('user')
            ->setPassword($this->hasher->hashPassword($user, 'u'));
        // On persiste user
        $manager->persist($user);

        // Création de 10 comptes aléatoire ROLE_USER
        for ($i = 1; $i < self::MAX_USER; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->email())
                ->setUsername($faker->username())
                ->setPassword($this->hasher->hashPassword($user, 'u'));
            // On persiste user
            $manager->persist($user);
            // On stock l'utilisateur dans un tableau
            $users[] = $user;
        }

        // Création de 100 publications avec des données aléatoire (entre 0 et 10 par publication)
        for ($i = 0; $i < self::MAX_POST_PUBLICATION; $i++) {

            // Création de post 
            $post = new Post();

            // Définir les données de ce post
            $post
                ->setTitle($faker->realText(100))
                ->setContent($faker->realText(1000))
                ->setAuthor($faker->randomElement($users));


            // On persiste la publication
            $manager->persist($post);

            // Boucle de création des commentaires (entre 0 et 10)
            $rand = rand(1, self::MAX_POST_COMMENT);

            for ($j = 0; $j < $rand; $j++) {

                // Création d'un commentaire
                $comment = new Comment();
                // Définis les données de ce commentaire
                $comment
                    ->setContent($faker->realText(500))
                    ->setPost($post)
                    // On récupère aléatoirement un utilisateur dans le tableau $user
                    ->setAuthor($faker->randomElement($users));

                // On persiste le commentaire
                $manager->persist($comment);
            }
        }

        // Enregistrenement en bdd
        $manager->flush();
    }
}
