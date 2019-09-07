<?php


namespace App\Whhato;


class CachedRandomizer
{
    private $cache;

    public function __construct(FileCache $cache)
    {
        $this->cache = $cache;
    }


    public function getRandomElement(array $elements, $cacheKey)
    {
        if (empty($elements)) {
            throw new \LogicException('Cannot get random element of empty array.');
        }

        $possibleKeys = array_diff(array_keys($elements), $this->cache->getCacheForKey($cacheKey));

        if (empty($possibleKeys)) {
            $this->cache->clearKey($cacheKey);
            $possibleKeys = $elements;
        }

        $randomKey = array_rand($possibleKeys);
        $this->cache->addItemToKey($cacheKey, $randomKey);

        return $elements[$randomKey];
    }
}