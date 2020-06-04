<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entities\Car;

class CarApiController extends AbstractController
{
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
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
            return new JsonResponse(['message' => 'Invalid command.'], 403);
        }

        $this->session->set('x', $car->getX());
        $this->session->set('y', $car->getY());
        $this->session->set('direction', $car->getDirection());

        return new JsonResponse($car->toArray());
    }
}