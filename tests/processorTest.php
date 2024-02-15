<?php
namespace As283\ArtisanPlantuml\Tests;

use function As283\PlantUmlProcessor\parse;
use function As283\PlantUmlProcessor\serialize;
use PHPUnit\Framework\TestCase;

class processorTest extends TestCase
{
    public function testParseFail()
    {
        $schema = parse("hello");
        
        $this->assertTrue($schema == null);
    }

    public function testParseSuccess()
    {
        $schemaText = "@startuml
        Class01 <|-- Class02
        @enduml";
        $schema = parse($schemaText);
        
        $this->assertTrue($schema != null);
    }

    public function testParseFull()
    {
        $schemaText = 
        "@startuml
        class Direccion{
            cp: string
            localidad: string
            provincia: string
            calle: string
            numero: int
        }
            
        class Usuario{
            id: int
            email: string
            password: string
            nombre: string
            apikey: string
        }
            
        class Rol {
            id: int
            nombre: string
        }
        @enduml";
        $schema = parse($schemaText);
        
        $this->assertTrue($schema != null);
    }

    public function testSerialize()
    {
        serialize(null);
        $this->assertTrue(true);
    }
}
