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
            $buffer = \array_merge($buffer, Yaml::parse(file_get_contents($item->getRealPath())));
        }

        return $this->flattenRawArray($buffer);
    }

    private function flattenRawArray(array $raw): array
    {
        $buffer = [];
        foreach ($raw as $monthDay => $dateMessages) {
            foreach ($dateMessages as $dateMessage) {
                $monthDayArray = explode('-', $monthDay);
                $buffer[] = new DateMessage($monthDayArray[0], $monthDayArray[1], $dateMessage);
            }
        }

        return $buffer;
    }
}
