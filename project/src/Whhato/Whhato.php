<?php declare(strict_types=1);

namespace App\Whhato;

class Whhato
{
    public const FORMAT_MONTH_DAY = 'm-d';

    private $dateMessages;

    public function __construct(Loader $loader)
    {
        $this->dateMessages = $loader->loadDataPath();
    }

    public function getRandomDateMessage(\DateTimeInterface $date): DateMessage
    {
        $messages = $this->getDateMessages($date);
        $rand = array_rand($messages); // Leave this for debugging ;)

        return $messages[$rand];
    }

    /** @return DateMessage[] */
    public function getDateMessages(\DateTimeInterface $date): array
    {
        $monthDay = $date->format(self::FORMAT_MONTH_DAY);

        if (!$this->hasDateMessage($date)) {
            throw new DateMessageNotFoundException(sprintf('no entries for date %s', $monthDay));
        }

        return $this->dateMessages[$monthDay];
    }

    public function hasDateMessage(\DateTimeInterface $date)
    {
        return array_key_exists($date->format(self::FORMAT_MONTH_DAY), $this->dateMessages);
    }

    public function overview()
    {
        $buffer = [];
        for ($dayOfYear = 0; $dayOfYear < 366; $dayOfYear++) {
            $month = \DateTimeImmutable::createFromFormat('z', (string)$dayOfYear)->format('m');
            $day = \DateTimeImmutable::createFromFormat('z', (string)$dayOfYear)->format('d');
            $date = \DateTimeImmutable::createFromFormat('Y-m-d', sprintf('%s-%s-%s', '1996', $month, $day));

            if (!array_key_exists($month, $buffer)) {
                $buffer[$month] = [];
            }

            $buffer[$month][$day] = $this->hasDateMessage($date) ? $this->getDateMessages($date) : [];
        }

        return $buffer;
    }
}
