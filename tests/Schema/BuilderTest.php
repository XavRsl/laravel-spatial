<?php namespace Schema;

use BaseTestCase;
use Mockery;
use Phaza\LaravelPostgis\SpatialConnection;
use Phaza\LaravelPostgis\Schema\Builder;
use Phaza\LaravelPostgis\Schema\Blueprint;

class BuilderTest extends BaseTestCase
{
    public function testReturnsCorrectBlueprint()
    {
        $connection = Mockery::mock(SpatialConnection::class);
        $connection->shouldReceive('getSchemaGrammar')->once()->andReturn(null);

        $mock = Mockery::mock(Builder::class, [$connection]);
        $mock->makePartial()->shouldAllowMockingProtectedMethods();
        $blueprint = $mock->createBlueprint('test', function () {
        });

        $this->assertInstanceOf(Blueprint::class, $blueprint);
    }
}
