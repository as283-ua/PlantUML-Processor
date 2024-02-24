<?php
namespace As283\ArtisanPlantuml\Tests;

use As283\PlantUmlProcessor\Model\Cardinality;
use As283\PlantUmlProcessor\Model\Origin;
use As283\PlantUmlProcessor\Model\Type;
use As283\PlantUmlProcessor\Model\Visibility;
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

    public function testModifiers()
    {
        $schemaText = 
        "@startuml
        class Direccion{
            > cp?!: string
            - > localidad?!: string
            + string provincia?
            calle!?
            # > numero: int
        }
        @enduml";
        $schema = PlantUmlProcessor::parse($schemaText);

        $this->assertTrue($schema != null);
        $this->assertEquals(1, count($schema->classes));

        $cp = $schema->classes[0]->fields[0];
        $localidad = $schema->classes[0]->fields[1];
        $provincia = $schema->classes[0]->fields[2];
        $calle = $schema->classes[0]->fields[3];
        $numero = $schema->classes[0]->fields[4];

        $this->assertTrue($cp->nullable);
        $this->assertTrue($cp->unique);
        $this->assertTrue($cp->primary);
        $this->assertEquals(null, $cp->visibility);
        $this->assertEquals(Type::string, $cp->type);

        $this->assertTrue($localidad->nullable);
        $this->assertTrue($localidad->unique);
        $this->assertTrue($localidad->primary);
        $this->assertEquals(Visibility::Private, $localidad->visibility);
        $this->assertEquals(Type::string, $localidad->type);

        $this->assertTrue($provincia->nullable);
        $this->assertFalse($provincia->unique);
        $this->assertFalse($provincia->primary);
        $this->assertEquals(Visibility::Public, $provincia->visibility);
        $this->assertEquals(Type::string, $provincia->type);

        $this->assertTrue($calle->nullable);
        $this->assertTrue($calle->unique);
        $this->assertFalse($calle->primary);
        $this->assertEquals(null, $calle->visibility);
        $this->assertEquals(null, $calle->type);

        $this->assertFalse($numero->nullable);
        $this->assertFalse($numero->unique);
        $this->assertTrue($numero->primary);
        $this->assertEquals(Visibility::Protected, $numero->visibility);
        $this->assertEquals(Type::int, $numero->type);
    }

    public function testParseRelationIndexes()
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

        Usuario \"*\" -- \"*\" Usuario
        @enduml";
        $schema = PlantUmlProcessor::parse($schemaText);

        $dir = $schema->classes[0];
        $user = $schema->classes[1];
        $rol = $schema->classes[2];

        $this->assertTrue($schema != null);
        $this->assertEquals(sizeof($dir->relationIndexes), 1);
        $this->assertEquals($dir->relationIndexes[0], Origin::From);

        $this->assertEquals(sizeof($user->relationIndexes), 3);
        $this->assertEquals($user->relationIndexes[0], Origin::To);
        $this->assertEquals($user->relationIndexes[1], Origin::From);
        $this->assertEquals($user->relationIndexes[2], Origin::SelfAssociation);
        
        $this->assertEquals(sizeof($rol->relationIndexes), 1);
        $this->assertEquals($rol->relationIndexes[1], Origin::To);

    }
}
