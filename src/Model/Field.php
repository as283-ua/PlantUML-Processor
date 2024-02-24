<?php

namespace As283\PlantUmlProcessor\Model;

use As283\PlantUmlProcessor\Exceptions\FieldException;

class Field {
    /**
     * @var string
     */
    public $name;
    
    /**
     * @var Type|null
     */
    public $type = null;
    
    /**
     * @var Visibility|null
     */
    public $visibility = null;

    /**
     * @var boolean
     */
    public $unique = false;

    /**
     * @var boolean
     */
    public $primary = false;

    /**
     * @var boolean
     */
    public $nullable = false; 

    // Too complicated perhaps

    // /**
    //  * @var mixed
    //  */
    // public $default = null;

    public function __construct(){}

    /**
     * @param string $name
     * @throws FieldException
     */
    private static function validateName($name){
        if (!ctype_alnum($name)){
            throw new FieldException("Invalid name: " . $name . ". Name must be alphanumeric and not start with a number.");
        } 
        
        if(is_numeric($name[0])){
            throw new FieldException("Invalid name: " . $name . ". Name must be alphanumeric and not start with a number.");
        } 
        
        if(Visibility::fromString($name) != null){
            throw new FieldException("Use of reserved word as variable name: " . $name);
        }
    }

    /**
     * @param string $name
     * @return Field
     * @throws FieldException
     * @example "field!?:", removes : (the next word should be its type), removes ? and sets nullable, removes ! and sets unique, sets name to "field"
     * @example "field!-", does not remove : (type goes before or is not present), - is nothing, so validates and throws exception
     * @example "field",no special chars to remove, validates and sets name to "field" and returns
     */
    private static function evaluateName($name){
        $result = new Field();
        // name!?
        if($name[-1] === ":"){
            $name = substr($name, 0, -1);
        }

        if($name[-1] === "?"){
            $result->nullable = true;
            $name = substr($name, 0, -1);
        } else if ($name[-1] === "!"){
            $result->unique = true;
            $name = substr($name, 0, -1);
        } else {
            self::validateName($name);
            $result->name = $name;
            return $result;
        }
        
        if($name[-1] === "?"){
            $result->nullable = true;
            $name = substr($name, 0, -1);
        } else if ($name[-1] === "!"){
            $result->unique = true;
            $name = substr($name, 0, -1);
        }

        self::validateName($name);
        $result->name = $name;
        return $result;
    }

    /** 
     * @param string[] $field
     * @return bool
     */
    private static function checkPk($details){
        array_search(">", $details) !== false;
        return false;
    }

    /**
     * @param \SimpleXMLElement $field
     * @return Field
     * @deprecated
     */
    public static function makeFromXmlELementOld($field)
    {
        $result = new Field();
        $details = $field->attributes()->name;
        $details = explode(" ", $details);

        if(sizeof($details) == 1){
            self::validateName($details[0]);

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
    /**
     * @param \SimpleXMLElement $field
     * @return Field
     * @throws FieldException
     */
    public static function makeFromXmlELement($field)
    {
        $result = new Field();

        $details = $field->attributes()->name;

        $details = explode(" ", $details);

        // remove empty strings
        for ($i = 0; $i<sizeof($details); $i++){
            if($details[$i] === ""){
                array_splice($details, $i, 1);
                $i--;
            }
        }

        if(sizeof($details) == 1){
            $result = self::evaluateName($details[0]);

            $result->type = null;
            $result->visibility = null;
            return $result;
        }


        $visibility = Visibility::fromString($field->attributes()->visibility);

        $result->visibility = $visibility;

        
        if($details[0] === ">"){
            $result->primary = true;
            array_splice($details, 0, 1);
        }

        $type = Type::fromString($details[0]);
        if($type != null){
            $result->type = $type;
            array_splice($details, 0, 1);
        }

        $fieldFromName = self::evaluateName($details[0]);
        $result->name = $fieldFromName->name;
        $result->unique = $fieldFromName->unique;
        $result->nullable = $fieldFromName->nullable;

        if($details[0][-1] === ":" && sizeof($details) > 1){
            $result->type = Type::fromString($details[1]);
        }

        return $result;
    }

    public function __toString()
    {
        $str = "";
        if($this->visibility != null){
            $str .= $this->visibility->toString() . " ";
        }
        if($this->primary){
            $str .= "> ";
        }

        $str .= $this->name;
        if($this->nullable){
            $str .= "?";
        }
        if($this->unique){
            $str .= "!";
        }

        if($this->type != null){
            $str .= ": " . $this->type->toString() . " ";
        }
        return $str;
    }
}