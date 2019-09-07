<?php


namespace App\Whhato;


use Symfony\Component\Yaml\Yaml;

class FileCache
{
    private $cacheDir;

    public function __construct($fileCacheDir)
    {
        $this->cacheDir = $fileCacheDir;
    }

    public function getCacheForKey($key): array
    {
        return $this->loadFile($key);
    }

    public function addItemToKey($key, $item): void
    {
        $this->saveFile($key, array_merge($this->loadFile($key), [$item]));
    }

    public function clearKey($key): void {
        $this->saveFile($key, []);
    }

    private function loadFile($key): array
    {

        if (!file_exists($this->getCacheFilePath($key))) {
            return [];
        }

        $data = Yaml::parse(file_get_contents($this->getCacheFilePath($key)));

        return $data[$key];
    }

    private function saveFile($key, $data): void
    {
        file_put_contents($this->getCacheFilePath($key), Yaml::dump([$key => $data]));
    }

    private function getCacheFilePath($key): string
    {
        return sprintf('%s/%s.yaml', $this->cacheDir, $key);
    }
}