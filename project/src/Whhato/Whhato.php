<?php declare(strict_types=1);

namespace App\Whhato;

class Whhato
{
    private const FORMAT_MONTH_DAY = 'm-d';

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

        if (!isset($this->dateMessages[$monthDay])) {
            throw new DateMessageNotFoundException(sprintf('no entries for date %s', $monthDay));
        }

        return $this->dateMessages[$monthDay];
    }
}
