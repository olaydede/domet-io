<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture implements DependentFixtureInterface
{
    public const CLIENT_REFERENCE_ONE = 'client-1';
    public const CLIENT_REFERENCE_TWO = 'client-2';

    public function load(ObjectManager $manager): void
    {
        $clientAcme = new Client();
        $clientAcme
            ->setName('Acme Inc.')
            ->setUser($this->getReference(UserFixtures::USER_REFERENCE, User::class));
        $manager->persist($clientAcme);
        $clientDevelopers = new Client();
        $clientDevelopers
            ->setName('Developers Ltd.')
            ->setUser($this->getReference(UserFixtures::USER_REFERENCE, User::class));
        $manager->persist($clientDevelopers);
        $manager->flush();
        // References
        $this->addReference(self::CLIENT_REFERENCE_ONE, $clientAcme);
        $this->addReference(self::CLIENT_REFERENCE_TWO, $clientDevelopers);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
