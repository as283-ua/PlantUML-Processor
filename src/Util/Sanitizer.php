<?php

namespace As283\PlantUmlProcessor\Util;

/**
 * Sanitize the plantuml xml by removing prohibited characters like ":" and simplify by removing "UML" prefixes, etc.
 * @param string $plantXml
 * @return string
 */
class Sanitizer {
    private const prohibited = ["UML:", "XMI.", "Namespace."];

    public static function sanitize($plantXml){
        return str_replace(self::prohibited, "", $plantXml);
    }
}