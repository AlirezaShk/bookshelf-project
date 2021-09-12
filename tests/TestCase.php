<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Route as RouteFacade;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    //How Many tests to be performed?
    protected $testCnt = 10;
}
