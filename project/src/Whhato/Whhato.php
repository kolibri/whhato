<?php

declare(strict_types=1);

namespace App\Whhato;

class Whhato
{
    public const FORMAT_MONTH_DAY = 'm-d';

    /** @var LoaderInterface[] */
    private $loaders;

    public function __construct(iterable $loaders)
    {
        $this->loaders = $loaders;
    }

    public function getRandomDateMessage(\DateTimeInterface $date): DateMessage
    {
        $messages = $this->getDateMessages($date);

        if (empty($messages)) {
            throw new DateMessageNotFoundException(sprintf('No messages for %s', $date->format(self::FORMAT_MONTH_DAY)));
        }

        $rand = array_rand($messages); // Leave this for debugging ;)

        return $messages[$rand];
    }

    /** @return DateMessage[] */
    public function getDateMessages(\DateTimeInterface $date): array
    {
        $messages = [];
        foreach ($this->loaders as $loader) {
            $messages = array_merge($messages, $loader->findByDate($date));
        }

        return $messages;
    }

    public function overview(): array
    {
        $buffer = [];
        for ($dayOfYear = 0; $dayOfYear < 366; ++$dayOfYear) {
            $month = \DateTimeImmutable::createFromFormat('z', (string) $dayOfYear)->format('m');
            $day = \DateTimeImmutable::createFromFormat('z', (string) $dayOfYear)->format('d');
            $date = \DateTimeImmutable::createFromFormat('Y-m-d', sprintf('%s-%s-%s', '1996', $month, $day));

            if (!array_key_exists($month, $buffer)) {
                $buffer[$month] = [];
            }

            $buffer[$month][$day] = $this->getDateMessages($date);
        }

        return $buffer;
    }

    public function getMessagesInRowFor(\DateTimeInterface $date): int
    {
        $bufferDate = \DateTime::createFromFormat(\DATE_ATOM, ($date->format(\DATE_ATOM)));
        for ($inRow = 0; $inRow < 366; ++$inRow) {
            if (empty($this->getDateMessages($bufferDate))) {
                return $inRow;
            }
            $bufferDate->add(new \DateInterval('P1D'));
        }

        return $inRow;
    }
}
