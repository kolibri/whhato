<?php


namespace App\Tests\Whhato;


use App\Whhato\DateMessage;
use PHPUnit\Framework\TestCase;

class DateMessageTest extends TestCase
{
    public function testCalculationWhenFormatted()
    {
        $dateMessage = new DateMessage('01-25', '{2015} years since 2015');
        static::assertSame('5 years since 2015', $dateMessage->format(\DateTimeImmutable::createFromFormat('Y-m-d', '2020-01-25')));
        static::assertSame('20 years since 2015', $dateMessage->format(\DateTimeImmutable::createFromFormat('Y-m-d', '2035-01-25')));
    }

    public function testGetters()
    {
        $dateMessage = new DateMessage('01-25', '{2015} years since 2015');
        static::assertSame('01-25', $dateMessage->getMonthDay());
        static::assertSame('{2015} years since 2015', $dateMessage->getRawMessage());
    }
}