<?php declare(strict_types=1);

namespace App\Whhato;

class DateMessage
{
    private const FORMAT_YEAR_MONTH_DAY = 'Y-m-d';
    private $monthDay;
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

    public function getRawMessage(): string
    {
        return $this->message;
    }

    public function format(\DateTimeInterface $date): string
    {
        $replace = [];

        if (preg_match_all('/\{\d{1,4}\}/', $this->message, $yearTokens)) {

            foreach ($yearTokens[0] as $yearToken) {
                $year = substr($yearToken, 1, -1);
                $messageDate = \DateTime::createFromFormat(
                    self::FORMAT_YEAR_MONTH_DAY,
                    sprintf('%s-%s', $year, $this->monthDay)
                );

                $replace[$yearToken] = (int) $date->format('Y') - (int) $messageDate->format('Y');
            }
        }

        return strtr($this->message, $replace);
    }
}
