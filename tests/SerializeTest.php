<?php

namespace As283\ArtisanPlantuml\Tests;

use As283\PlantUmlProcessor\Model\Multiplicity;
use As283\PlantUmlProcessor\Model\Type;
use As283\PlantUmlProcessor\PlantUmlProcessor;
use PHPUnit\Framework\TestCase;

class SerializeTest extends TestCase
{
    public function testSerializeClass()
    {
        $puml =
            "@startuml
        class Direccion{
            string cp
            string localidad
            string provincia
            string calle
            int numero
        }
            
        class Usuario{
            id: int
            email: string
            password: string
            nombre: string
            apikey: string
        }
            
        class Rol {
            int id
            string nombre
        }

        Direccion \"0..1\" -- \"1..*\" Usuario
        Usuario \"*\" -- \"1\" Rol
        @enduml";
        $parsed = PlantUmlProcessor::parse($puml);

        $serialized = PlantUmlProcessor::serialize($parsed);

        $reparsed = PlantUmlProcessor::parse($serialized);

        $this->assertEquals($parsed, $reparsed);
    }

    public function testSerializeWithModifiers()
    {
        $puml =
            "@startuml
        class Direccion{
            + string cp
            localidad: string
            # string provincia
            string calle?!
            > int numero
        }
            
        class Usuario{
            > id: int
            email?: string
            password!: string
            nombre: string
            ~ apikey: string
        }
            
        class Rol {
            > id: int
            > string nombre
        }

        Direccion \"0..1\" -- \"1..*\" Usuario
        Usuario \"*\" -- \"1\" Rol
        @enduml";
        $parsed = PlantUmlProcessor::parse($puml);

        $serialized = PlantUmlProcessor::serialize($parsed);

        $reparsed = PlantUmlProcessor::parse($serialized);

        $this->assertEquals($parsed, $reparsed);
    }
}
