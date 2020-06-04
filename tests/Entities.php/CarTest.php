<?php

namespace App\Tests\Entities;

use PHPUnit\Framework\TestCase;
use App\Entities\Car;

class CalculatorTest extends TestCase
{
    public function testInit()
    {
        $car = new Car(0, 1, 'N');
        $this->assertEquals(0, $car->getX());
        $this->assertEquals(1, $car->getY());
        $this->assertEquals('N', $car->getDirection());
    }

    public function testPlaceNegativeX() {
        $car = new Car(0, 1, 'N');
        $car->setPosition(-1, 1);

        $this->assertEquals(0, $car->getX());
        $this->assertEquals(1, $car->getY());
        $this->assertEquals('N', $car->getDirection());
    }

    public function testPlaceNegativeY() {
        $car = new Car(0, 1, 'N');
        $car->setPosition(0, 1);

        $this->assertEquals(0, $car->getX());
        $this->assertEquals(1, $car->getY());
        $this->assertEquals('N', $car->getDirection());
    }

    public function testTurnLeftFromNorth() {
        $car = new Car(0, 1, 'N');
        $car->turnLeft();

        $this->assertEquals(0, $car->getX());
        $this->assertEquals(1, $car->getY());
        $this->assertEquals('W', $car->getDirection());
    }

    public function testTurnLeftFromEast() {
        $car = new Car(0, 1, 'E');
        $car->turnLeft();

        $this->assertEquals(0, $car->getX());
        $this->assertEquals(1, $car->getY());
        $this->assertEquals('N', $car->getDirection());
    }

    public function testTurnRightFromEast() {
        $car = new Car(0, 1, 'E');
        $car->turnRight();

        $this->assertEquals(0, $car->getX());
        $this->assertEquals(1, $car->getY());
        $this->assertEquals('S', $car->getDirection());
    }

    public function testTurnRightFromSouth() {
        $car = new Car(0, 1, 'S');
        $car->turnRight();

        $this->assertEquals(0, $car->getX());
        $this->assertEquals(1, $car->getY());
        $this->assertEquals('W', $car->getDirection());
    }

    public function testMoveFacingNorth() {
        $car = new Car(1, 1, 'N');
        $car->move();

        $this->assertEquals(1, $car->getX());
        $this->assertEquals(2, $car->getY());
        $this->assertEquals('N', $car->getDirection());
    }

    public function testMoveFacingEast() {
        $car = new Car(1, 1, 'E');
        $car->move();

        $this->assertEquals(2, $car->getX());
        $this->assertEquals(1, $car->getY());
        $this->assertEquals('E', $car->getDirection());
    }

    public function testMoveFacingSouth() {
        $car = new Car(1, 1, 'S');
        $car->move();

        $this->assertEquals(1, $car->getX());
        $this->assertEquals(0, $car->getY());
        $this->assertEquals('S', $car->getDirection());
    }

    public function testMoveFacingWest() {
        $car = new Car(1, 1, 'W');
        $car->move();

        $this->assertEquals(0, $car->getX());
        $this->assertEquals(1, $car->getY());
        $this->assertEquals('W', $car->getDirection());
    }

    public function testMoveToInvalidFacingNorth() {
        $car = new Car(0, Car::MAX_HEIGHT - 1, 'N');
        $car->move();

        $this->assertEquals(0, $car->getX());
        $this->assertEquals(Car::MAX_HEIGHT - 1, $car->getY());
        $this->assertEquals('N', $car->getDirection());
    }

    public function testMoveToInvalidFacingEast() {
        $car = new Car(Car::MAX_WIDTH - 1, 0, 'E');
        $car->move();

        $this->assertEquals(Car::MAX_WIDTH - 1, $car->getX());
        $this->assertEquals(0, $car->getY());
        $this->assertEquals('E', $car->getDirection());
    }

    public function testMoveToInvalidFacingSouth() {
        $car = new Car(1, 0, 'S');
        $car->move();

        $this->assertEquals(1, $car->getX());
        $this->assertEquals(0, $car->getY());
        $this->assertEquals('S', $car->getDirection());
    }

    public function testMoveToInvalidFacingWest() {
        $car = new Car(0, 1, 'W');
        $car->move();

        $this->assertEquals(0, $car->getX());
        $this->assertEquals(1, $car->getY());
        $this->assertEquals('W', $car->getDirection());
    }
}