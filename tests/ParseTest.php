<?php
namespace As283\ArtisanPlantuml\Tests;

use As283\PlantUmlProcessor\Model\Cardinality;
use As283\PlantUmlProcessor\Model\Type;
use As283\PlantUmlProcessor\PlantUmlProcessor;
use PHPUnit\Framework\TestCase;

class ParseTest extends TestCase
{
    public function testParseFail()
    {
        $schema = PlantUmlProcessor::parse("hello");
        
        $this->assertTrue($schema == null);
    }

    public function testParseSuccess()
    {
        $schemaText = "@startuml
        Class01 <|-- Class02
        @enduml";
        $schema = PlantUmlProcessor::parse($schemaText);
        
        $this->assertTrue($schema != null);
    }

    public function testParseClasses()
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

        Direccion \"0, 1\" -- \"0, 1\" Usuario

        Usuario \"*\" -- \"1\" Rol
        @enduml";
        $schema = PlantUmlProcessor::parse($schemaText);

        // print_r($schema);
        
        $this->assertTrue($schema != null);
        $this->assertEquals(3, count($schema->classes));
        $this->assertEquals(2, count($schema->relations));

        $this->assertEquals("Direccion", $schema->classes[0]->name);
        $this->assertEquals("Usuario", $schema->classes[1]->name);

        $this->assertEquals("id", $schema->classes[1]->fields[0]->name);
        $this->assertEquals(Type::int, $schema->classes[1]->fields[0]->type);
    }

    public function testParseRelations()
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

        Direccion \"0, 1\" -- \"1..*\" Usuario

        Usuario \"*\" -- \"1\" Rol
        @enduml";
        $schema = PlantUmlProcessor::parse($schemaText);

        $this->assertTrue($schema != null);
        $this->assertEquals(3, count($schema->classes));
        $this->assertEquals(2, count($schema->relations));

        $this->assertEquals("Direccion", $schema->relations[0]->from[0]);
        $this->assertEquals(Cardinality::AtLeastOne, $schema->relations[0]->from[1]);

        $this->assertEquals("Usuario", $schema->relations[0]->to[0]);
        $this->assertEquals(Cardinality::ZeroOrOne, $schema->relations[0]->to[1]);

        $this->assertEquals("Usuario", $schema->relations[1]->from[0]);
        $this->assertEquals(Cardinality::One, $schema->relations[1]->from[1]);

        $this->assertEquals("Rol", $schema->relations[1]->to[0]);
        $this->assertEquals(Cardinality::Any, $schema->relations[1]->to[1]);
    }

    public function testParseOnlyRelations()
    {
        $parsed = PlantUmlProcessor::parse("@startuml
        Bob -- Alice : hello
        @enduml"
        );
        
        $this->assertTrue($parsed != null);
    }
}