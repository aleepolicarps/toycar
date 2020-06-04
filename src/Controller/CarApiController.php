<?php

namespace App\Controller;

use App\Entities\Car;
use App\Exceptions\CarException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarApiController extends AbstractController
{
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/api/car/reset")
     */
    public function reset()
    {
        $this->session->clear('isPlaced');
        return new JsonResponse(['message' => 'Car has been reset.']);
    }

    /**
     * @Route("/api/car/status")
     */
    public function getStatus(Request $request)
    {
        if(!$this->session->get('isPlaced')) {
            return new JsonResponse(['message' => 'Car not placed yet.'], 403);
        }

        $car = new Car($this->session->get('x'), $this->session->get('y'), $this->session->get('direction'));
        return new JsonResponse($car->toArray());
    }

    /**
     * @Route("/api/car/run-command")
     */
    public function runCommand(Request $request)
    {
        $command = strtoupper($request->query->get('command'));
        if(!$command) {
            return new JsonResponse(['message' => 'Invalid command.'], 403);
        }

        if(!$this->session->get('isPlaced') && strpos($command, 'PLACE') === false) {
            return new JsonResponse(['message' => 'Invalid command. Place car first.'], 403);
        }

        $this->session->set('isPlaced', true);
        $car = new Car($this->session->get('x'), $this->session->get('y'), $this->session->get('direction'));

        try {
            $this->executeCommand($command, $car);
        } catch (CarException $e) {
            return new JsonResponse(['message' => 'Invalid command.'], 403);
        }

        $this->session->set('x', $car->getX());
        $this->session->set('y', $car->getY());
        $this->session->set('direction', $car->getDirection());

        $commands = $this->session->get('commands');
        $commands[] = $command;
        $this->session->set('commands', $commands);

        return new JsonResponse($car->toArray());
    }

    /**
     * @Route("/api/car/run-history")
     */
    public function runHistory()
    {
        $commands = $this->session->get('commands') ?? [];
        $history = [];

        try {
            $car = new Car();
            foreach($commands as $command) {
                $car = $this->executeCommand($command, $car);
                $history[] = $car->toArray();
            }
        } catch (CarException $e) {
            return new JsonResponse(['message' => 'Invalid command.'], 403);
        }

        return new JsonResponse($history);
    }

    private function executeCommand($command, $car)
    {
        if(strpos($command, 'PLACE') === 0) {
            list(, $coordinates) = explode(' ', $command);
            list($x, $y, $direction) = explode(',', $coordinates);
            $car->setPosition($x, $y);
            $car->setDirection($direction);
        } else if($command == 'MOVE') {
            $car->move();
        } else if($command == 'LEFT') {
            $car->turnLeft();
        } else if($command == 'RIGHT') {
            $car->turnRight();
        } else {
            throw new CarException('Invalid command.');
        }

        return $car;
    }

}