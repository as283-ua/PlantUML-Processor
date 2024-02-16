<?php

namespace As283\PlantUmlProcessor\Model;

enum RelationType {
    case Association;
    case Aggregation;
    case Composition;
    case Inheritance;
    case Realization;
    case Dependency;
    case Use;

    public function toString()
    {
        /*
        Class03 *-- Class04
        Class05 o-- Class06
        Class09 -- Class10
        */
        return match ($this) {
            self::Association => "--",
            self::Aggregation => "*--",
            self::Composition => "o--"
        };
    }
}