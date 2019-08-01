<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WhhatoControllerTest extends WebTestCase
{
    public function testWhatHappendTodayAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/whhato');
        static::assertSame(
            sprintf(
                '{"text":"%s","response_type":"in_channel"}',
                $this->calcExpectedMessage(new \DateTimeImmutable())
            ),
            $client->getResponse()->getContent()
        );
    }

    /**
     * Test data works like following:
     * 1.Jan. => notes year 2001
     * 2 Jan. => notes year 2000
     * And so on. For each day, subtract 1 to get the expected year.
     *
     * @todo: Should break on lap years at 29.Feb....
     */
    private function calcExpectedMessage(\DateTimeInterface $givenDate): string
    {
        $expectedYear = 2001 - (int)$givenDate->format('z');

        return sprintf(
            'Its been %s years since this day in %s',
            $givenDate->format('Y') - $expectedYear,
            $expectedYear
        );
    }

}

