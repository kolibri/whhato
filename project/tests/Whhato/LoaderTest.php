<?php

namespace App\Tests\Whhato;

use App\Whhato\DateMessage;
use App\Whhato\Loader;
use PHPUnit\Framework\TestCase;

class LoaderTest extends TestCase
{
    public function testCanLoadDataFromYamlFiles(): void
    {
        $loader = new Loader(__DIR__ . '/LoaderTestData');

        static::assertEquals([
            '01-25' => [new DateMessage('01-25', '{2015} years since 2015')],
            '09-26' => [new DateMessage('09-26', '{1985} years since 1985')],
        ], $loader->loadDataPath());
    }
}