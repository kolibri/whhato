<?php declare(strict_types=1);

namespace App\Whhato;

class DateMessage
{
    private $month;
    private $day;
    private $message;

    public function __construct(string $month, string $day, string $message)
    {
        $this->month = $month;
        $this->day = $day;
        $this->message = $message;
    }

    public function getMonth(): string
    {
        return $this->month;
    }

    public function getDay(): string
    {
        return $this->day;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function format(\DateTime $date): string
    {
        $replace = [];

        if (preg_match_all('/\{\d{1,4}\}/', $this->message, $yearTokens)) {

            foreach ($yearTokens[0] as $yearToken) {
                $year = substr($yearToken, 1, -1);
                $messageDate = \DateTime::createFromFormat(
                    Whhato::FORMAT_YEAR_MONTH_DAY,
                    sprintf('%s-%s-%s', $year, $this->month, $this->day)
                );

                $replace[$yearToken] = $date->diff($messageDate)->y;
            }
        }

        return strtr(
            $this->getMessage(),
            $replace
        );
    }
}
