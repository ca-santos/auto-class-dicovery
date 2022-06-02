<?php

namespace CaueSantos\AutoClassDiscovery\Tests;

use CaueSantos\AutoClassDiscovery\Facades\AutoClassDiscovery;
use CaueSantos\AutoClassDiscovery\ServiceProvider;
use Orchestra\Testbench\TestCase;

class AutoClassDiscoveryTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'auto-class-discovery' => AutoClassDiscovery::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
