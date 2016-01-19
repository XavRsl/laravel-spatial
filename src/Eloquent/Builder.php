<?php namespace Xavrsl\LaravelSpatial\Eloquent;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Xavrsl\LaravelSpatial\Geometries\GeometryInterface;

class Builder extends EloquentBuilder
{
    public function update(array $values)
    {
        foreach ($values as $key => &$value) {
            if ($value instanceof GeometryInterface) {
                $value = $this->asWKT($value);
            }
        }

        return parent::update($values);
    }

    protected function getSpatialFields()
    {
        return $this->getModel()->getSpatialFields();
    }


    protected function asWKT(GeometryInterface $geometry)
    {
        return $this->getQuery()->raw(sprintf("ST_GeogFromText('%s')", $geometry->toWKT()));
    }
}
