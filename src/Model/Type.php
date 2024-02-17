<?php

namespace As283\PlantUmlProcessor\Model;

// not complete. needs a lot more type support
enum Type
{
    case string;
    case int;
    case float;
    case bool;
    case double;
    case Date;
    case DateTime;

    public static function fromString($type){
        $type = strtolower($type);
        return match($type){
            "string" => Type::string,
            "int" => Type::int,
            "float" => Type::float,
            "bool" => Type::bool,
            "double" => Type::double,
            "Date" => Type::Date,
            "DateTime" => Type::DateTime,
            default => null
        };
    }

    public function toString(){
        return match($this){
            Type::string => "string",
            Type::int => "int",
            Type::float => "float",
            Type::bool => "bool",
            Type::double => "double",
            Type::Date => "Date",
            Type::DateTime => "DateTime"
        };
    }
}