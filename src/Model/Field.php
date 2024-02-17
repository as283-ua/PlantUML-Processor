<?php

namespace As283\PlantUmlProcessor\Model;

class Field {
    /**
     * @var string
     */
    public $name;
    /**
     * @var Type
     */
    public $type;
    /**
     * @var Visibility
     */
    public $visibility;

    public function __construct(){}

    /**
     * @param \SimpleXMLElement $field
     * @return Field
     */
    public static function makeFromXmlELement($field)
    {
        $result = new Field();
        $details = $field->attributes()->name;
        $details = explode(" ", $details);

        if(sizeof($details) == 1){
            if (Visibility::fromString($details[0]) != null){
                // ERROR: USO DE PALABRA RESERVADA COMO NOMBRE DE VARIABLE
                return null;
            }

            $result->name = $details[0];
            $result->type = null;
            $result->visibility = null;
            return $result;
        }
        
        
        // assume if visibility is defined, it's the first element
        $nextIndex = $result->visibility != null ? 1 : 0;

        if(str_contains($details[$nextIndex], ":")){                        // name: [type]
            //remove ':'
            $details[$nextIndex] = substr($details[$nextIndex], 0, -1);

            $result->name = $details[$nextIndex];
            if(sizeof($details) > $nextIndex + 1){
                $result->type = Type::fromString($details[$nextIndex + 1]);
            }
        } else {                                                            // [type] name
            $result->type = Type::fromString($details[$nextIndex]);
            $result->name = $details[$nextIndex + 1];
        }

        return $result;
    }

    public function __toString()
    {
        $str = "";
        if($this->visibility != null){
            $str .= $this->visibility . " ";
        }
        if($this->type != null){
            $str .= $this->type->toString() . " ";
        }
        $str .= $this->name;
        return $str;
    }
}