<?php

namespace As283\PlantUmlProcessor\Model;

class Method {
    public string $name;
    public Visibility $visibility;
    public Type $type;

    /**
     * @var array<Field>
     */
    public $params; 
}