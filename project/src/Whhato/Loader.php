<?php declare(strict_types=1);

namespace App\Whhato;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class Loader
{

    private $dataPath;

    public function __construct(string $dataPath)
    {
        $this->dataPath = $dataPath;
    }


    public function loadDataPath(): array
    {
        $finder = new Finder();
        $finder->files()->in($this->dataPath)->name('/\.yaml$/');
        $buffer = [];

        foreach ($finder as $item) {
            $buffer = \array_merge($buffer, Yaml::parse(file_get_contents($item->getRealPath())));
        }

        array_walk($buffer, function (&$messages, $monthDay) {
            $messages = array_map(function ($message) use ($monthDay) {
                return new DateMessage($monthDay, $message);
            }, $messages ?? []);
        });

        return array_filter($buffer);

#        return $this->flattenRawArray($buffer);
    }

    private function flattenRawArray(array $raw): array
    {
        $buffer = [];
        foreach ($raw as $monthDay => $dateMessages) {
            if (!is_array($dateMessages)) {
                continue;
            }
            foreach ($dateMessages as $dateMessage) {
                $buffer[] = new DateMessage($monthDay, $dateMessage);
            }
        }

        return $buffer;
    }
}
