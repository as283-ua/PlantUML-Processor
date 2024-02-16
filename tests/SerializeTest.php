<?php
namespace As283\ArtisanPlantuml\Tests;

use As283\PlantUmlProcessor\Model\Cardinality;
use As283\PlantUmlProcessor\Model\Type;
use As283\PlantUmlProcessor\PlantUmlProcessor;
use PHPUnit\Framework\TestCase;

class SerializeTest extends TestCase
{
    public function testSerialize()
    {
        PlantUmlProcessor::serialize(null);
        $this->assertTrue(true);
    }

    public function testSerializeClass()
    {
        $puml = "@startuml
        class Direccion{
            string cp
            string localidad
            string provincia
            string alle
            int numero
        }
            
        class Usuario{
            int id
            string email
            string password
            string nombre
            string apikey
        }
            
        class Rol {
            int id
            string nombre
        }

        Direccion \"0, 1\" -- \"1..*\" Usuario
        Usuario \"*\" -- \"1\" Rol
        @enduml";
        $parsed = PlantUmlProcessor::parse($puml);
        
        $serialized = PlantUmlProcessor::serialize($parsed);

        print_r($serialized);
        
        $this->assertEquals($puml, $serialized);
    }
}
