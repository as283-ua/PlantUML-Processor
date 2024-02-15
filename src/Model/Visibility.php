<?php

namespace As283\PlantUmlProcessor\Model;

enum Visibility {
    case Public;
    case Protected;
    case Private;
    case Package;

    public static function fromString($value){
        $value = strtolower($value);
        return match($value){
            "public" => Visibility::Public,
            "+" => Visibility::Public,
            "private" => Visibility::Private,
            "-" => Visibility::Private,
            "protected" => Visibility::Protected,
            "#" => Visibility::Protected,
            "package" => Visibility::Package,
            "~" => Visibility::Package,
            default => null
        };
    }
}