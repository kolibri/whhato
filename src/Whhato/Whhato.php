<?php declare(strict_types=1);

namespace App\Whhato;

class Whhato
{
    const FORMAT_MONTH_DAY = 'm-d';
    const FORMAT_YEAR_MONTH_DAY = 'Y-m-d';

    private $loader;
    private $dateMessages;

    public function __construct(Loader $loader, string $dataPath)
    {
        $this->loader = $loader;
        foreach ($this->loader->loadDataPath($dataPath) as $dateMessage) {
            $this->addDateMessage($dateMessage);
        }
    }

    public function getRandomDateMessage(\DateTime $date): DateMessage
    {
        $messages = $this->getDateMessages($date);
        $rand = array_rand($messages); // Leave this for debugging ;)

        return $messages[$rand];
    }

    public function getDateMessages(\DateTime $date): array
    {
        $monthDay = $date->format(self::FORMAT_MONTH_DAY);

        if (!isset($this->dateMessages[$monthDay])) {
            throw new DateMessageNotFoundException(sprintf('no entries for date %s', $monthDay));
        }

        return $this->dateMessages[$monthDay];
    }

    private function addDateMessage(DateMessage $dateMessage)
    {
        $monthDay = sprintf('%s-%s', $dateMessage->getMonth(), $dateMessage->getDay());
        $this->dateMessages[$monthDay][] = $dateMessage;
    }
}
