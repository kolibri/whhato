<?php declare(strict_types=1);

namespace App\Whhato;

class DateMessage
{
    private $monthDay;
    private $day;
    private $message;

    public function __construct(string $monthDay, string $message)
    {
        $this->monthDay = $monthDay;
        $this->message = $message;
    }

    public function getMonthDay(): string
    {
        return $this->monthDay;
    }

    public function format(\DateTime $date): string
    {
        $replace = [];

        if (preg_match_all('/\{\d{1,4}\}/', $this->message, $yearTokens)) {

            foreach ($yearTokens[0] as $yearToken) {
                $year = substr($yearToken, 1, -1);
                $messageDate = \DateTime::createFromFormat(
                    Whhato::FORMAT_YEAR_MONTH_DAY,
                    sprintf('%s-%s', $year, $this->monthDay)
                );

                $replace[$yearToken] = $date->diff($messageDate)->y;
            }
        }

        return strtr($this->message, $replace);
    }
}
