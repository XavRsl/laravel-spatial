<?php namespace Xavrsl\LaravelSpatial\Connectors;

use PDO;
use Xavrsl\LaravelSpatial\SpatialConnection;

class ConnectionFactory extends \Illuminate\Database\Connectors\ConnectionFactory
{
    /**
     * @param string $driver
     * @param PDO $connection
     * @param string $database
     * @param string $prefix
     * @param array $config
     * @return mixed|SpatialConnection
     */
    protected function createConnection($driver, $connection, $database, $prefix = '', array $config = [])
    {
        if ($this->container->bound($key = "db.connection.{$driver}")) {
            return $this->container->make($key, [$connection, $database, $prefix, $config]);
        }

        if ($driver === 'mysql') {
            return new SpatialConnection($connection, $database, $prefix, $config);
        }

        return parent::createConnection($driver, $connection, $database, $prefix, $config);
    }
}
