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
}
