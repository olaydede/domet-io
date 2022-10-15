<?php
namespace App\Controller;

use App\Enum\DometType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name:'home')]
    public function index(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findBy(['author' => $this->getUser()], ['id' => 'DESC']);
        return $this->render('home.html.twig', ['tasks' => $tasks]);
    }
}