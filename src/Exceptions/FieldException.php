<?php

namespace As283\PlantUmlProcessor\Exceptions;

use Exception;

class FieldException extends Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}