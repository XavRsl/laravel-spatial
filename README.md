Laravel spatial extension
=========================

## Features

 * Work with geometry classes instead of arrays. (`$myModel->myPoint = new Point(1,2)`)
 * Adds helpers in migrations. (`$table->polygon('myColumn')`)
 
### Future plans
 
 * Geometry functions on the geometry classes (contains(), equals(), distance(), etc… (HELP!))

## Installation

    composer require xavrsl/laravel-spatial 

Next add the DatabaseServiceProvider to your `config/app.php` file.

    'Xavrsl\LaravelSpatial\DatabaseServiceProvider',

That's all.

## Usage

First of all, make sure you are using Mysql 5.7.

### Migrations

Now create a model with a migration by running

    php artisan make:model Location

If you don't want a model and just a migration run

    php artisan make:migration create_locations_table

Open the created migrations with your editor.

```PHP
use Illuminate\Database\Migrations\Migration;
use Xavrsl\LaravelSpatial\Schema\Blueprint;

class CreateLocationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('address')->unique();
            $table->point('location');
            $table->polygon('polygon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('locations');
    }

}
```

Available blueprint geometries:

 * point
 * multipoint
 * linestring
 * multilinestring
 * polygon
 * multipolygon
 * geometrycollection

### Models

All models which are to be Spatial enabled **must** use the *SpatialTrait*.

You must also define an array called `$spatialFields` which defines
what attributes/columns on your model are to be considered geometry objects.

```PHP
use Illuminate\Database\Eloquent\Model;
use Xavrsl\LaravelSpatial\Eloquent\SpatialTrait;
use xavrsl\LaravelSpatial\Geometries\Point;

class Location extends Model
{
    use SpatialTrait;

    protected $fillable = [
        'name',
        'address'
    ];

    protected $spatialFields = [
        Point::class,
        Polygon::class,
    ];

}

$location1 = new Location();
$location1->name = 'Googleplex';
$location1->address = '1600 Amphitheatre Pkwy Mountain View, CA 94043';
$location1->location = new Point(37.422009, -122.084047);
$location1->save();

$location2 = Location::first();
$location2->location instanceof Point // true
```

Available geometry classes:
 
 * Point
 * MultiPoint
 * LineString
 * MultiLineString
 * Polygon
 * MultiPolygon
 * GeometryCollection
