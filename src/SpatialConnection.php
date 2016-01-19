<?php namespace Xavrsl\LaravelSpatial;


use Illuminate\Database\MySqlConnection;

class SpatialConnection extends MySqlConnection
{
    /**
     * Get the default schema grammar instance.
     *
     * @return \Illuminate\Database\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        return $this->withTablePrefix(new Schema\Grammars\SpatialGrammar);
    }


    public function getSchemaBuilder()
    {
        if (is_null($this->schemaGrammar)) {
            $this->useDefaultSchemaGrammar();
        }

        return new Schema\Builder($this);
    }
}
