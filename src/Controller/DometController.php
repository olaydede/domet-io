<?php

namespace App\Controller;

use App\DTO\Factory\DometDTOFactory;
use App\Entity\Domet;
use App\Entity\Task;
use App\Enum\DometType;
use App\Repository\DometRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class DometController extends AbstractController
{
    const DEFAULT_DURATION = 30*60*1000;

    public DometRepository $dometRepository;

    public SerializerInterface $serializer;

    /**
     * @param DometRepository $dometRepository
     */
    public function __construct(DometRepository $dometRepository)
    {
        $this->dometRepository = $dometRepository;
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
    }


    #[Route('/domet', name: 'app_domet')]
    public function index(): Response
    {
        return $this->render('domet/index.html.twig', [
            'controller_name' => 'DometController',
        ]);
    }

    #[Route('/domet/add/{task}', name: 'domet_add', requirements: ['task' => '\d+'], options: ['expose' => true])]
    public function start(Request $request, Task $task): JsonResponse
    {
        // Check if a Domet exists for this Task, if not create
        $domet = $this->dometRepository->findLatestByTask($task);
        if (! $domet instanceof Domet) {
            $beginDate = new \DateTime();
            $endDate = (clone $beginDate)->modify('+ ' . self::DEFAULT_DURATION . ' milliseconds');
            // Create object
            $domet = new Domet();
            $domet
                ->setDometType(DometType::FOCUS)
                ->setUser($this->getUser())
                ->setTask($task)
                ->setBeginDate($beginDate)
                ->setEndDate($endDate);
        }
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
}
