<?php namespace Xavrsl\LaravelSpatial\Schema\Grammars;

use Illuminate\Database\Schema\Grammars\MySqlGrammar;
use Illuminate\Support\Fluent;
use Xavrsl\LaravelSpatial\Schema\Blueprint;

class SpatialGrammar extends MySqlGrammar
{
    /**
     * Adds a statement to add a point geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typePoint(Fluent $column)
    {
        return 'POINT';
    }

    /**
     * Adds a statement to add a point geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultipoint(Fluent $column)
    {
        return 'MULTIPOINT';
    }

    /**
     * Adds a statement to add a polygon geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typePolygon(Fluent $column)
    {
        return 'POLYGON';
    }

    /**
     * Adds a statement to add a multipolygon geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultipolygon(Fluent $column)
    {
        return 'MULTIPOLYGON';
    }

    /**
     * Adds a statement to add a linestring geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeLinestring(Fluent $column)
    {
        return 'LINESTRING';
    }

    /**
     * Adds a statement to add a multilinestring geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeMultilinestring(Fluent $column)
    {
        return 'MULTILINESTRING';
    }

    /**
     * Adds a statement to add a linestring geometry column
     *
     * @param \Illuminate\Support\Fluent $column
     * @return string
     */
    public function typeGeography(Fluent $column)
    {
        return 'GEOGRAPHY';
    }

    /**
     * Adds a statement to add a geometrycollection geometry column
     *
     * @param Blueprint $blueprint
     * @param Fluent $command
     * @return string
     */
    public function compileGeometrycollection(Blueprint $blueprint, Fluent $command)
    {
        $command->type = 'GEOMETRYCOLLECTION';

        return $this->compileGeometry($blueprint, $command);
    }

    /**
     * Adds a statement to add a geometry column
     *
     * @param Blueprint $blueprint
     * @param Fluent $command
     * @return string
     */
    protected function compileGeometry(Blueprint $blueprint, Fluent $command)
    {

        $dimensions = $command->dimensions ?: 2;
        $typmod = $command->typmod ? 'true' : 'false';
        $srid = $command->srid ?: 4326;

        return sprintf(
            "SELECT AddGeometryColumn('%s', '%s', %d, '%s', %d, %s)",
            $blueprint->getTable(),
            $command->column,
            $srid,
            strtoupper($command->type),
            $dimensions,
            $typmod
        );
    }
}
