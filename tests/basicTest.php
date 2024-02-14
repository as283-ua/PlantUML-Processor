<?php
namespace As283\ArtisanPlantuml\Tests;

use function As283\PlantUmlProcessor\parse;
use function As283\PlantUmlProcessor\serialize;
use PHPUnit\Framework\TestCase;

class basicTest extends TestCase
{
    public function testParse()
    {
        $schema = parse("hello");
        
        $this->assertTrue($schema == null);
    }

    public function testSerialize()
    {
        serialize(null);
        $this->assertTrue(true);
    }
}
