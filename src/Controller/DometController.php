<?php

namespace App\Controller;

use App\DTO\Factory\DometDTOFactory;
use App\Entity\Domet;
use App\Entity\Task;
use App\Enum\DometStatus;
use App\Enum\DometType;
use App\Repository\DometRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Routing\Attribute\Route;

class DometController extends AbstractController
{
    protected DometRepository $dometRepository;

    /**
     * @param DometRepository $dometRepository
     */
    public function __construct(DometRepository $dometRepository)
    {
        $this->dometRepository = $dometRepository;
    }

    #[Route('/domet', name: 'app_domet')]
    public function index(): Response
    {
        return $this->render('domet/index.html.twig', [
            'controller_name' => 'DometController',
        ]);
    }

    #[Route(
        '/domet/start/{task}/{time}',
        name: 'domet_start',
        requirements: ['task' => '\d+'],
        options: ['expose' => true]
    )]
    public function start(Request $request, ChatterInterface $chatter, Task $task, int $time): JsonResponse
    {
        $effectiveDate = \DateTime::createFromFormat('U.u', $time / 1000);
        // Check if a Domet exists for this Task, if not create
        $domet = $this->dometRepository->findLatestIncompleteDometByTask($task);
        if (! $domet instanceof Domet) {
            $domet = new Domet();
            $domet
                ->setDometType(DometType::FOCUS)
                ->setUser($this->getUser())
                ->setTask($task)
                ->setDuration(15000) // Domet::DEFAULT_DURATION_MS)
                ->setDurationLeft(15000) // Domet::DEFAULT_DURATION_MS)
                ->setStatus(DometStatus::IN_PROGRESS)
            ;
        }
        $domet->startWorkOn($effectiveDate);
        $this->dometRepository->save($domet, true);
        // Add a DometPart to lock this period of work
        return new JsonResponse(
            [
                'data' => [
                    'domet' => DometDTOFactory::makeFromDomet($domet)
                ]
            ],
            JsonResponse::HTTP_OK
        );
    }

    #[Route(
        '/domet/stop/{task}/{time}',
        name: 'domet_stop',
        requirements: ['task' => '\d+'],
        options: ['expose' => true]
    )]
    public function stop(Request $request, Task $task, int $time): JsonResponse
    {
        $effectiveDate = \DateTime::createFromFormat('U.u', $time / 1000);
        // Find appropriate Domet
        $domet = $this->dometRepository->findLatestIncompleteDometByTask($task);
        if (! $domet instanceof Domet) {
            throw new NotFoundHttpException();
        }
        $domet->stopWorkOn($effectiveDate);
        $this->dometRepository->save($domet, true);
        // Render
        return new JsonResponse(
            [
                'data' => [
                    'domet' => DometDTOFactory::makeFromDomet($domet)
                ]
            ],
            JsonResponse::HTTP_OK
        );
    }
}
