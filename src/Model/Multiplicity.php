<?php

namespace As283\PlantUmlProcessor\Model;

use function PHPUnit\Framework\returnSelf;

enum Multiplicity
{
    case ZeroOrOne;
    case One;
    case AtLeastOne;
    case Any;

    /**
     * @param string|null $multiplicity
     * @return Multiplicity
     */
    public static function fromString($multiplicity)
    {
        if ($multiplicity == null) {
            return Multiplicity::One;
        }

        $multiplicity = str_replace(" ", "", $multiplicity);
        switch ($multiplicity) {
            case "0,1":
                return Multiplicity::ZeroOrOne;
            case "0..1":
                return Multiplicity::ZeroOrOne;
            case "1":
                return Multiplicity::One;
            case "1..1":
                return Multiplicity::One;
            case "1,1":
                return Multiplicity::One;
            case "1..*":
                return Multiplicity::AtLeastOne;
            case "1,*":
                return Multiplicity::AtLeastOne;
            case "*":
                return Multiplicity::Any;
            case "0..*":
                return Multiplicity::Any;
            case "0,*":
                return Multiplicity::Any;
            default:
                return Multiplicity::One;
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
     * @return int -1 if $mult1 is less restrictive than card2, 0 if equal, 1 if more restrictive.
     */
    public static function compare($mult1, $mult2)
    {
        if ($mult1 === $mult2) {
            return 0;
        }

        if ($mult1 === Multiplicity::One) {
            return 1;
        }

        if ($mult2 === Multiplicity::One) {
            return -1;
        }

        if ($mult1 === Multiplicity::Any) {
            return -1;
        }

        if ($mult2 === Multiplicity::Any) {
            return 1;
        }

        if ($mult1 === Multiplicity::ZeroOrOne) {
            return 1;
        }

        //$mult2 === zero or one
        return -1;
    }
}
