<?php

namespace As283\PlantUmlProcessor\Exceptions;

use Exception;

class RepeatedFieldNameException extends Exception
{
    public $fieldName;
    public function __construct($fieldName)
    {
        $this->fieldName = $fieldName;
        parent::__construct("Field names cannot be repeated. Field name: " . $fieldName);
    }
}