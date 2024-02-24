<?php

namespace As283\PlantUmlProcessor\Model;

enum Visibility {
    case Public;
    case Protected;
    case Private;
    case Package;

    /**
     * @param string $value
     * @return Visibility|null
     */
    public static function fromString($value){
        if($value == null) return null;

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

    public function toString(){
        return match($this){
            Visibility::Public => "+",
            Visibility::Private => "-",
            Visibility::Protected => "#",
            Visibility::Package => "~"
        };
    }
}