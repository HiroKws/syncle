<?php

namespace Syncle;

// class TestCase extends Illuminate\Foundation\Testing\TestCase
class TestCase extends \Orchestra\Testbench\TestCase
{

    public function tearDown()
    {
        \Mockery::close();
    }

    protected function getPackageProviders()
    {
        return array( 'Syncle\SyncleServiceProvider' );
    }

}
