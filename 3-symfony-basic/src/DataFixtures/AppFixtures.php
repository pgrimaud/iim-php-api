<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $task = new Task;
            $task->setName('Tâche n°' . $i);
            $task->setDescription('Description blabla' . $i);
            $task->setDone(0); # pas nécessaire car $done = 0 dans l'entité

            $manager->persist($task);
        }

        for ($i = 1; $i <= 10; $i++) {
            $user = new User;

            $user->setEmail('test' . $i . '@gmail.com');
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
            $user->setApiKey('apikeytest' . $i);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
