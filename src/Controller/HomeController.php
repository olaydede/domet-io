<?php
namespace App\Controller;

use App\DTO\DometPreferenceDTO;
use App\Entity\Domet;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Bridge\Slack\SlackOptions;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name:'home', options: ['expose' => true])]
    public function index(TaskRepository $taskRepository): Response
    {
        // Get User's preference for domet durations
        $userPreference = new DometPreferenceDTO(
            15000, // Domet::DEFAULT_DURATION_MS,
            15000 //Domet::DEFAULT_DURATION_MS
        );
        $tasks = $taskRepository->findBy(['author' => $this->getUser()], ['id' => 'DESC']);
        return $this->render('home.html.twig', ['tasks' => $tasks, 'userPreference' => $userPreference]);
    }
}