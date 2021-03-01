<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var EntityManagerInterface
     */
    private $objectManager;

    public function __construct(EntityManagerInterface $objectManager, RequestStack $request)
    {
        $this->taskRepository = $objectManager->getRepository(Task::class);
        $this->objectManager  = $objectManager;

        $apiToken = $request->getCurrentRequest()->headers->get('api-token');

        $user = $this->objectManager->getRepository(User::class)->findOneBy([
            'apiKey' => $apiToken,
        ]);

        if (!$user instanceof User) {
            throw new HttpException(401, 'Unauthorized');
        }
    }

    /**
     * @Route("/tasks", name="api_get_tasks", methods={"GET"})
     *
     * @return Response
     */
    public function getTasks(): Response
    {
        $tasks = $this->taskRepository->findAll();
        return $this->json($tasks);
    }

    /**
     * @Route("/tasks/{taskId}", name="api_get_task", methods={"GET"})
     *
     * @param                $taskId
     *
     * @return Response
     */
    public function getTask($taskId): Response
    {
        $task = $this->taskRepository->find($taskId);

        if (!$task instanceof Task) {
            throw new NotFoundHttpException();
        }

        return $this->json($task);
    }

    /**
     * @Route("/tasks/{taskId}", name="api_delete_task", methods={"DELETE"})
     *
     * @param $taskId
     * @return Response
     */
    public function deleteTask($taskId): Response
    {
        $task = $this->taskRepository->find($taskId);

        if (!$task instanceof Task) {
            throw new NotFoundHttpException();
        }

        $this->objectManager->remove($task);
        $this->objectManager->flush();

        return $this->json('Success');
    }

    /**
     * @Route("/tasks", name="api_add_task", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function addTask(Request $request): Response
    {
        $task = new Task;

        $form = $this->createForm(TaskType::class, $task);
        $form->submit($request->request->all());

        $this->objectManager->persist($task);
        $this->objectManager->flush();

        return $this->json($task);
    }

    /**
     * @Route("/tasks/{taskId}", name="api_update_task", methods={"PUT"})
     *
     * @param         $taskId
     * @param Request $request
     *
     * @return Response
     */
    public function updateTask($taskId, Request $request): Response
    {
        $task = $this->taskRepository->find($taskId);

        if (!$task instanceof Task) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->submit($request->request->all());

        $this->objectManager->persist($task);
        $this->objectManager->flush();

        return $this->json($task);
    }
}
