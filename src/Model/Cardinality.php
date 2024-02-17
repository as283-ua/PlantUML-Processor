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

    public function toString(){
        return match ($this) {
            self::ZeroOrOne => "0..1",
            self::One => "1",
            self::AtLeastOne => "1..*",
            self::Any => "*"
        };
    }
}