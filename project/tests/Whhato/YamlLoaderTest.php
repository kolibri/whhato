<?php

namespace App\Tests\Whhato;

use App\Whhato\DateMessage;
use App\Whhato\YamlLoader;
use PHPUnit\Framework\TestCase;

class YamlLoaderTest extends TestCase
{
    public function testCanLoadDataFromYamlFiles(): void
    {
        static::assertEquals(
            [
                '01-25' => [new DateMessage('01-25', '{2015} years since 2015')],
                '09-26' => [new DateMessage('09-26', '{1985} years since 1985')],
            ],
            YamlLoader::loadDataPath(__DIR__.'/LoaderTestData')
        );
    }

    public function testCanFindByDate(): void
    {
        $loader = new YamlLoader(__DIR__.'/LoaderTestData');

        static::assertEquals(
            [new DateMessage('01-25', '{2015} years since 2015')],
            $loader->findByDate(\DateTimeImmutable::createFromFormat('Y-m-d', '2015-01-25'))
        );
    }
}
