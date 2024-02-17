<?php

namespace As283\PlantUmlProcessor\Model;

use function PHPUnit\Framework\returnSelf;

enum Cardinality {
    case ZeroOrOne;
    case One;
    case AtLeastOne;
    case Any;

    /**
     * @param string|null $cardinality
     * @return Cardinality
     */
    public static function fromString($cardinality){
        if($cardinality == null){
            return Cardinality::One;
        }
        
        $cardinality = str_replace(" ", "", $cardinality);
        switch($cardinality){
            case "0,1":
                return Cardinality::ZeroOrOne;
            case "0..1":
                return Cardinality::ZeroOrOne;
            case "1":
                return Cardinality::One;
            case "1..1":
                return Cardinality::One;
            case "1,1":
                return Cardinality::One;
            case "1..*":
                return Cardinality::AtLeastOne;
            case "1,*":
                return Cardinality::AtLeastOne;
            case "*":
                return Cardinality::Any;
            case "0..*":
                return Cardinality::Any;
            case "0,*":
                return Cardinality::Any;
            default:
                return Cardinality::One;
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