<?php

namespace App\Exceptions;

class CarException extends \Exception {

    public function __construct($message) {
        return parent::__construct('CarException: ' + $message);
    }

}