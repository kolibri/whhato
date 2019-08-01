<?php declare(strict_types=1);

namespace App\Whhato;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class Loader
{
    public function loadDataPath(string $path): array
    {
        $finder = new Finder();
        $finder->files()->in($path)->name('/\.yaml$/');
        $buffer = [];

        foreach ($finder as $item) {
            $buffer[] = Yaml::parse(file_get_contents($item->getRealPath()));
        }

        return $this->flattenRawArray(array_merge(...$buffer));
    }

    private function flattenRawArray(array $raw): array
    {
        $buffer = [];
        foreach ($raw as $monthDay => $dateMessages) {
            foreach ($dateMessages as $dateMessage) {
                $buffer[] = new DateMessage($monthDay, $dateMessage);
            }
        }

        return $buffer;
    }
}
