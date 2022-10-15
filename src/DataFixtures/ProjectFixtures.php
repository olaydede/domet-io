<?php
namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROJECT_REFERENCE_ONE = 'project-1';
    public const PROJECT_REFERENCE_TWO = 'project-2';
    public const PROJECT_REFERENCE_THREE = 'project-3';
    public const PROJECT_REFERENCE_FOUR = 'project-4';

    public function load(ObjectManager $manager)
    {
        // Project 1
        $project = new Project();
        $project
            ->setClient($this->getReference(ClientFixtures::CLIENT_REFERENCE_ONE))
            ->setAuthor($this->getReference(UserFixtures::USER_REFERENCE))
            ->setTitle('Some random project ' . rand(1, 20))
            ->setDescription('Generated through a fixture. Dummy project description');
        $this->addReference(self::PROJECT_REFERENCE_ONE, $project);
        $manager->persist($project);
        // Project 2
        $project = new Project();
        $project
            ->setClient($this->getReference(ClientFixtures::CLIENT_REFERENCE_ONE))
            ->setAuthor($this->getReference(UserFixtures::USER_REFERENCE))
            ->setTitle('Some random project ' . rand(1, 20))
            ->setDescription('Generated through a fixture. Dummy project description');
        $this->addReference(self::PROJECT_REFERENCE_TWO, $project);
        $manager->persist($project);
        // Project 3
        $project = new Project();
        $project
            ->setClient($this->getReference(ClientFixtures::CLIENT_REFERENCE_TWO))
            ->setAuthor($this->getReference(UserFixtures::USER_REFERENCE))
            ->setTitle('Some random project ' . rand(1, 20))
            ->setDescription('Generated through a fixture. Dummy project description');
        $this->addReference(self::PROJECT_REFERENCE_THREE, $project);
        $manager->persist($project);
        // Project 4
        $project = new Project();
        $project
            ->setClient($this->getReference(ClientFixtures::CLIENT_REFERENCE_TWO))
            ->setAuthor($this->getReference(UserFixtures::USER_REFERENCE))
            ->setTitle('Some random project ' . rand(1, 20))
            ->setDescription('Generated through a fixture. Dummy project description');
        $this->addReference(self::PROJECT_REFERENCE_FOUR, $project);
        $manager->persist($project);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixtures::class
        ];
    }
}
