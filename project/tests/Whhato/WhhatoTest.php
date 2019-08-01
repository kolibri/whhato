<?php

declare(strict_types=1);

namespace App\Tests\Whhato;

use App\Whhato\DateMessage;
use App\Whhato\Loader;
use App\Whhato\Whhato;
use PHPUnit\Framework\TestCase;

class WhhatoTest extends TestCase
{
    public function testCanGatAllDateMessages()
    {
        $msg1 = new DateMessage('01-25', 'Something happened {2015} years ago');
        $msg2 = new DateMessage('09-26', 'And {2000} something different happened.');

        $loader = $this->createMock(Loader::class);
        $loader->method('loadDataPath')->willReturn([$msg1, $msg2]);

        $whhato = new Whhato($loader, '/data/path');

        static::assertSame(
            [$msg1],
            $whhato->getDateMessages(\DateTime::createFromFormat('d.m.Y', '25.01.2020'))
        );

        static::assertSame(
            [$msg2],
            $whhato->getDateMessages(\DateTime::createFromFormat('d.m.Y', '26.09.2020'))
        );
    }

    public function testCanGetRandomDateMessage()
    {
        $msg1 = new DateMessage('01-25', 'Something happened {2015} years ago');
        $msg2 = new DateMessage('01-25', 'And {2000} something different happened.');

        $loader = $this->createMock(Loader::class);
        $loader->method('loadDataPath')->willReturn([$msg1, $msg2]);

        $whhato = new Whhato($loader, '/data/path');

        static::assertContains(
            $whhato->getRandomDateMessage(\DateTime::createFromFormat('d.m.Y', '25.01.2020')),
            [$msg1, $msg2]
        );
    }

}
