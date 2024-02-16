<?php

namespace As283\PlantUmlProcessor\Model;

enum Cardinality {
    case ZeroOrOne;
    case One;
    case AtLeastOne;
    case Any;

    public static function fromString(string $cardinality): Cardinality{
        switch($cardinality){
            case "0, 1":
                return Cardinality::ZeroOrOne;
            case "0..1":
                return Cardinality::ZeroOrOne;
            case "1":
                return Cardinality::One;
            case "1..*":
                return Cardinality::AtLeastOne;
            case "*":
                return Cardinality::Any;
        }
    }
}