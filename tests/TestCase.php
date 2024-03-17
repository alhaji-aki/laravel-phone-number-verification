<?php

namespace AlhajiAki\PhoneNumberVerification\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /** @var \AlhajiAki\PhoneNumberVerification\Tests\TestModel */
    protected $testModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    /**
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function setUpDatabase(Application $app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('implemented_must_verify_phone_number_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone');
            $table->string('phone_verified_at')->nullable();
        });

        $app['db']->connection()->getSchemaBuilder()->create('not_implemented_must_verify_phone_number_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone');
        });
    }
}
