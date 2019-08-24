<?php

declare(strict_types=1);

namespace App\Whhato;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Yaml\Yaml;

class YamlLoader implements LoaderInterface
{
    private $data;

    public function __construct(string $dataPath)
    {
        $this->data = self::loadDataPath($dataPath);
    }

    public function findByDate(\DateTimeInterface $dateTime): array
    {
        return $this->data[$dateTime->format(Whhato::FORMAT_MONTH_DAY)] ?? [];
    }

    public static function loadDataPath(string $dataPath): array
    {
        $finder = new Finder();
        $finder->files()->in($dataPath)->name('/\.yaml$/');
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
    }
}
