<?php
namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $randomTasks = [
            'Stick to a New Years Resolution For an Entire Year',
            'Kill a Hog With a Bow',
            'Participate in a Zombie Walk',
            'Help an Elderly Person With Their Bags',
            'Write a Book to Each of my Children',
            'Become a Zen Master',
            'Become a Photographer',
            'Walk Across Forum Romanum',
            'Become a Martial Arts Instructor',
            'Eat Snake',
            'Down Hill Biking',
            'Sleep all Night in a Hammock',
        ];
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 1; $j <= 3; $j++) {
                $task = new Task();
                $task
                    ->setTitle($randomTasks[($i*$j)-1])
                    ->setDescription('Dummy task. No desription here.')
                    ->setAuthor($this->getReference(UserFixtures::USER_REFERENCE))
                    ->setProject($this->getReference('project-'.$i))
                    ->setTaskType('REGULAR');
                $manager->persist($task);
            }
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ProjectFixtures::class
        ];
    }
}
