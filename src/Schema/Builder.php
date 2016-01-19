<?php namespace Xavrsl\LaravelSpatial\Schema;

use Closure;
use Illuminate\Database\Schema\MySqlBuilder as SchemaBuilder;

class Builder extends SchemaBuilder
{
    /**
     * Create a new command set with a Closure.
     *
     * @param string $table
     * @param Closure $callback
     * @return Blueprint
     */
    protected function createBlueprint($table, Closure $callback = null)
    {
        if (isset($this->resolver)) {
            return call_user_func($this->resolver, $table, $callback);
        }

        return new Blueprint($table, $callback);
    }
}
