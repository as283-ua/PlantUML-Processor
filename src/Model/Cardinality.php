<?php

namespace As283\PlantUmlProcessor\Model;

use function PHPUnit\Framework\returnSelf;

enum Cardinality
{
    case ZeroOrOne;
    case One;
    case AtLeastOne;
    case Any;

    /**
     * @param string|null $cardinality
     * @return Cardinality
     */
    public static function fromString($cardinality)
    {
        if ($cardinality == null) {
            return Cardinality::One;
        }

        $cardinality = str_replace(" ", "", $cardinality);
        switch ($cardinality) {
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

    public function toString()
    {
        return match ($this) {
            self::ZeroOrOne => "0..1",
            self::One => "1",
            self::AtLeastOne => "1..*",
            self::Any => "*"
        };
    }

    /**
     * @return int -1 if $card1 is less restrictive than card2, 0 if equal, 1 if more restrictive.
     */
    public static function compare($card1, $card2)
    {
        if ($card1 === $card2) {
            return 0;
        }

        if ($card1 === Cardinality::One) {
            return 1;
        }

        if ($card2 === Cardinality::One) {
            return -1;
        }

        if ($card1 === Cardinality::Any) {
            return -1;
        }

        if ($card2 === Cardinality::Any) {
            return 1;
        }

        if ($card1 === Cardinality::ZeroOrOne) {
            return 1;
        }

        //$card2 === zero or one
        return -1;
    }
}
