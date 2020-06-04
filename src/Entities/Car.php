<?php

namespace App\Entities;

class Car {

    const MAX_WIDTH = 5;
    const MAX_HEIGHT = 5;

    private $x;
    private $y;
    private $direction;

    public function __construct($x, $y, $direction)
    {
        $this->x = $x;
        $this->y = $y;
        $this->direction = $direction;
    }

    public function setPosition($x, $y)
    {
        if($x > self::MAX_WIDTH || $x < 0) {
            return;
        }

        if($y > self::MAX_HEIGHT || $y < 0) {
            return;
        }

        $this->x = $x;
        $this->y = $y;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    public function turnLeft()
    {
        switch($this->direction) {
            case 'N': $this->direction = 'W'; break;
            case 'E': $this->direction = 'N'; break;
            case 'S': $this->direction = 'E'; break;
            case 'W': $this->direction = 'S'; break;
        }
    }

    public function turnRight()
    {
        switch($this->direction) {
            case 'N': $this->direction = 'E'; break;
            case 'E': $this->direction = 'S'; break;
            case 'S': $this->direction = 'W'; break;
            case 'W': $this->direction = 'N'; break;
        }
    }

    public function move()
    {
        switch($this->direction) {
            case 'N': $this->y++; break;
            case 'E': $this->x++; break;
            case 'S': $this->y--; break;
            case 'W': $this->x--; break;
        }

        $this->correctPosition();
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function toArray() {
        return [
            'x' => $this->x,
            'y' => $this->y,
            'direction' => $this->direction
        ];
    }

    private function correctPosition()
    {
         if($this->x < 0) { $this->x++; }
        if($this->x >= self::MAX_WIDTH) { $this->x--; }
        if($this->y < 0) { $this->y++; }
        if($this->y >= self::MAX_HEIGHT) { $this->y--; }
    }
}