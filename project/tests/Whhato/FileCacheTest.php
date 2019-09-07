<?php

namespace App\Tests\Whhato;

use App\Whhato\FileCache;
use PHPUnit\Framework\TestCase;

class FileCacheTest extends TestCase
{
    public function testGetCacheWithData()
    {
        $cache = new FileCache(__DIR__.'/FileCacheTestData');
        static::assertSame([1, 2, 3], $cache->getCacheForKey('01-25'));
        static::assertSame([], $cache->getCacheForKey('09-26'));
    }

    public function testSetCache()
    {
        $monthDay = '09-26';

        $cacheFilePath = __DIR__.'/FileCacheTestData/'.$monthDay.'.yaml';
        file_exists($cacheFilePath) && unlink($cacheFilePath);

        $cache = new FileCache(__DIR__.'/FileCacheTestData');
        static::assertSame([], $cache->getCacheForKey($monthDay));

        $cache->addItemToKey($monthDay, 3);
        static::assertSame([3], $cache->getCacheForKey($monthDay));

        $cache->addItemToKey($monthDay, 7);
        static::assertSame([3, 7], $cache->getCacheForKey($monthDay));

        unlink($cacheFilePath);
    }

    public function testClearCache()
    {
        $monthDay = '09-26';

        $cacheFilePath = __DIR__.'/FileCacheTestData/'.$monthDay.'.yaml';
        file_exists($cacheFilePath) && unlink($cacheFilePath);

        $cache = new FileCache(__DIR__.'/FileCacheTestData');
        static::assertSame([], $cache->getCacheForKey($monthDay));

        $cache->addItemToKey($monthDay, 3);
        static::assertSame([3], $cache->getCacheForKey($monthDay));

        $cache->clearKey($monthDay);
        static::assertSame([], $cache->getCacheForKey($monthDay));

        unlink($cacheFilePath);
    }
}
