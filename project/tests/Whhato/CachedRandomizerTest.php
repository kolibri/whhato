<?php

namespace App\Tests\Whhato;

use App\Whhato\CachedRandomizer;
use App\Whhato\FileCache;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CachedRandomizerTest extends TestCase
{
    public function testRandomizerDoesNotReturnSameElementInARow()
    {
        /** @var FileCache|MockObject $cache */
        $cache = $this->createMock(FileCache::class);

        $randomizer = new CachedRandomizer($cache);
        $cache->method('getCacheForKey')->willReturn([1, 3, 4]);

        static::assertRandomResult($randomizer, ['one', 'two', 'three', 'four', 'five'], ['one', 'three']);
    }

    public function testRandomizerResetsWhenCacheIsFull()
    {
        /** @var FileCache|MockObject $cache */
        $cache = $this->createMock(FileCache::class);

        $randomizer = new CachedRandomizer($cache);
        $cache->method('getCacheForKey')->willReturn([0, 1, 2]);
        $cache->expects(static::exactly(50))->method('clearKey')->with('key');

        static::assertRandomResult($randomizer, ['one', 'two', 'three'], ['one', 'two', 'three']);
    }

    public function testRandomElementKeyIsCached()
    {
        /** @var FileCache|MockObject $cache */
        $cache = $this->createMock(FileCache::class);

        $randomizer = new CachedRandomizer($cache);
        $cache->method('getCacheForKey')->willReturn([0, 2]);
        $cache->expects(static::once())->method('addItemToKey')->with('key', 1);

        $elements = ['one', 'two', 'three'];

        $randomElement = $randomizer->getRandomElement($elements, 'key');
        static::assertSame('two', $randomElement);
    }

    private static function assertRandomResult(CachedRandomizer $randomizer, $givenElements, $expectedElements, $iterations = 50)
    {
        $results = [];
        for ($i = 0; $i < $iterations; ++$i) {
            $randomElement = $randomizer->getRandomElement($givenElements, 'key');

            if (!in_array($randomElement, $expectedElements)) {
                static::fail(sprintf('Random Element "%s" is not one of the expected', $randomElement));
            }
            $results[$randomElement] = true;
        }

        if (count($expectedElements) !== count($results)) {
            static::fail(
                sprintf(
                    'Result of 50 runs had %s elements instead of %s. Seems not to be random enough.',
                    count($results),
                    count($expectedElements)
                )
            );
        }

        static::assertTrue(true);
    }
}
