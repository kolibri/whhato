<?php

declare(strict_types=1);

namespace App\Tests\Whhato;

use App\Whhato\CachedRandomizer;
use App\Whhato\DateMessage;
use App\Whhato\DateMessageNotFoundException;
use App\Whhato\Loader;
use App\Whhato\Whhato;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WhhatoTest extends TestCase
{
    public function testCanGatAllDateMessages()
    {
        $msg1 = new DateMessage('01-25', 'Something happened {2015} years ago');
        $msg2 = new DateMessage('09-26', 'And {2000} something different happened.');

        /** @var Loader|MockObject $loader */
        $loader = $this->createMock(Loader::class);
        $loader->method('loadDataPath')->willReturn(['01-25' => [$msg1], '09-26' => [$msg2]]);

        /** @var CachedRandomizer|MockObject $randomizer */
        $randomizer = $this->createMock(CachedRandomizer::class);

        $whhato = new Whhato($loader, $randomizer);

        static::assertSame([$msg1], $whhato->getDateMessages(\DateTime::createFromFormat('d.m.Y', '25.01.2020')));
        static::assertSame([$msg2], $whhato->getDateMessages(\DateTime::createFromFormat('d.m.Y', '26.09.2020')));

        $this->expectException(DateMessageNotFoundException::class);
        $whhato->getRandomDateMessage(\DateTimeImmutable::createFromFormat('d.m.Y', '13.01.2020'));
    }

    public function testCanGetRandomDateMessage()
    {
        $msg1 = new DateMessage('01-25', 'Something happened {2015} years ago');
        $msg2 = new DateMessage('01-25', 'And {2000} something different happened.');
        $msg3 = new DateMessage('09-26', '{1985} years ago also something happened.');

        /** @var Loader|MockObject $loader */
        $loader = $this->createMock(Loader::class);
        $loader->method('loadDataPath')->willReturn(['01-25' => [$msg1, $msg2], '09-26' => [$msg3]]);

        /** @var CachedRandomizer|MockObject $randomizer */
        $randomizer = $this->createMock(CachedRandomizer::class);
        $randomizer
            ->expects(static::once())
            ->method('getRandomElement')
            ->with([$msg1, $msg2], '01-25')
            ->willReturn($msg1);

        $whhato = new Whhato($loader, $randomizer);

        static::assertSame($msg1, $whhato->getRandomDateMessage(\DateTimeImmutable::createFromFormat('d.m.Y', '25.01.2020')));

        $this->expectException(DateMessageNotFoundException::class);
        $whhato->getRandomDateMessage(\DateTimeImmutable::createFromFormat('d.m.Y', '13.01.2020'));
    }
}
