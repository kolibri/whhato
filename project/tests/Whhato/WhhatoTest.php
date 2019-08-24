<?php

declare(strict_types=1);

namespace App\Tests\Whhato;

use App\Whhato\DateMessage;
use App\Whhato\DateMessageNotFoundException;
use App\Whhato\LoaderInterface;
use App\Whhato\Whhato;
use PHPUnit\Framework\TestCase;

class WhhatoTest extends TestCase
{
    public function testCanGatAllDateMessages()
    {
        $msg1 = new DateMessage('01-25', 'Something happened {2015} years ago');
        $msg2 = new DateMessage('09-26', 'And {2000} something different happened.');

        $date1 = \DateTime::createFromFormat('d.m.Y', '25.01.2020');
        $date2 = \DateTime::createFromFormat('d.m.Y', '26.09.2020');

        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects(static::any())->method('findByDate')->willReturnCallback(function (\DateTimeInterface $date) use ($date1, $date2, $msg1, $msg2) {
            if ($date->format('m-d') == $date1->format('m-d')) {
                return [$msg1];
            }

            if ($date->format('m-d') == $date2->format('m-d')) {
                return [$msg2];
            }

            return [];
        });

        $whhato = new Whhato([$loader]);

        static::assertSame([$msg1], $whhato->getDateMessages($date1));
        static::assertSame([$msg2], $whhato->getDateMessages($date2));

        $this->expectException(DateMessageNotFoundException::class);
        $whhato->getRandomDateMessage(\DateTime::createFromFormat('d.m.Y', '11.03.2020'));
    }

    public function testCanGetRandomDateMessage()
    {
        $msg1 = new DateMessage('01-25', 'Something happened {2015} years ago');
        $msg2 = new DateMessage('01-25', 'And {2000} something different happened.');
        $msg3 = new DateMessage('09-26', '{1985} years ago also something happened.');

        $date1 = \DateTime::createFromFormat('d.m.Y', '25.01.2020');
        $date2 = \DateTime::createFromFormat('d.m.Y', '26.09.2020');

        $loader = $this->createMock(LoaderInterface::class);
        $loader->expects(static::any())->method('findByDate')->willReturnCallback(function (\DateTimeInterface $date) use ($date1, $date2, $msg1, $msg2, $msg3) {
            if ($date->format('m-d') == $date1->format('m-d')) {
                return [$msg1, $msg2];
            }

            if ($date->format('m-d') == $date2->format('m-d')) {
                return [$msg3];
            }

            return [];
        });

        $whhato = new Whhato([$loader]);

        $results = [];
        for ($i = 0; $i < 50; ++$i) {
            $date0125 = \DateTimeImmutable::createFromFormat('d.m.Y', '25.01.2020');
            $msg = $whhato->getRandomDateMessage($date0125);
            $isOneOfPossible = ('Something happened 5 years ago' === $msg->format($date0125) ||
                'And 20 something different happened.' === $msg->format($date0125));
            if (!$isOneOfPossible) {
                static::fail();
            }

            $results[$msg->format($date0125)] = true;
        }

        if (2 !== count($results)) {
            $this->fail('Always got the same result for 50 times. Probably a bug.');
        }

        $date0926 = \DateTimeImmutable::createFromFormat('d.m.Y', '26.09.2020');
        $msg = $whhato->getRandomDateMessage($date0926);
        static::assertSame('35 years ago also something happened.', $msg->format($date0926));

        $this->expectException(DateMessageNotFoundException::class);
        $whhato->getRandomDateMessage(\DateTimeImmutable::createFromFormat('d.m.Y', '13.01.2020'));
    }
}
