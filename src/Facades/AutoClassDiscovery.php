<?php

namespace CaueSantos\AutoClassDiscovery\Facades;

use Illuminate\Support\Facades\Facade;

class AutoClassDiscovery extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'auto-class-discovery';
    }
}
