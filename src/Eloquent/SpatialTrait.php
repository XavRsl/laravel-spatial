<?php namespace Xavrsl\LaravelSpatial\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Arr;
use Xavrsl\LaravelSpatial\Exceptions\PostgisFieldsNotDefinedException;
use Xavrsl\LaravelSpatial\Geometries\Geometry;
use Xavrsl\LaravelSpatial\Geometries\GeometryInterface;

trait SpatialTrait
{

    public $geometries = [];
    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Phaza\LaravelPostgis\Eloquent\Builder
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    protected function performInsert(EloquentBuilder $query, array $options = [])
    {
        foreach ($this->attributes as $key => &$value) {
            if ($value instanceof GeometryInterface && ! $value instanceof GeometryCollection) {
                $this->geometries[$key] = $value; //Preserve the geometry objects prior to the insert
                $value = $this->getConnection()->raw(sprintf("ST_GeomFromText('%s', 4326)", $value->toWKT()));
            }  else if ($value instanceof GeometryInterface && $value instanceof GeometryCollection) {
                $this->geometries[$key] = $value; //Preserve the geometry objects prior to the insert
                $value = $this->getConnection()->raw(sprintf("ST_GeomFromText('%s', 4326)", $value->toWKT()));
            }
        }

        $insert = parent::performInsert($query, $options);

        foreach($this->geometries as $key => $value){
            $this->attributes[$key] = $value; //Retrieve the geometry objects so they can be used in the model
        }

        return $insert; //Return the result of the parent insert
    }

    public function setRawAttributes(array $attributes, $sync = false)
    {
        $spatialFields = $this->getSpatialFields();

        foreach ($attributes as $attribute => &$value) {
            if (in_array($attribute, $spatialFields) && is_string($value) && strlen($value) >= 15) {
                $value = Geometry::fromWKB($value);
            }
        }

        parent::setRawAttributes($attributes, $sync);
    }

    public function getSpatialFields()
    {
        if (property_exists($this, 'spatialFields')) {
            return Arr::isAssoc($this->spatialFields) ? //Is the array associative?
                array_keys($this->spatialFields) : //Returns just the keys to preserve compatibility with previous versions
                $this->spatialFields; //Returns the non-associative array that doesn't define the geometry type.
        } else {
            throw new SpatialFieldsNotDefinedException(__CLASS__ . ' has to define $spatialFields');
        }

    }
}
