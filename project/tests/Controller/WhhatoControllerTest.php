<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WhhatoControllerTest extends WebTestCase
{
    public function testIndexPageWorks()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        static::assertTrue($client->getResponse()->isSuccessful());
        static::assertTrue(0 < $crawler->filter('h1')->count());

    }

    public function testWhatHappenedTodayAction(): void
    {
        $client = static::createClient();
        $client->request('GET', '/whhato');
        static::assertTrue($client->getResponse()->isSuccessful());
        static::assertSame(
            sprintf(
                '{"text":"%s","response_type":"in_channel"}',
                $this->calcExpectedMessage(new \DateTimeImmutable())
            ),
            $client->getResponse()->getContent()
        );
    }

    public function testProdReturnsSomething()
    {
        $client = static::createClient(['environment' => 'prod','debug' => false]);
        $client->request('GET', '/whhato');
        $response = json_decode($client->getResponse()->getContent(), true);
        static::assertTrue($client->getResponse()->isSuccessful());
        static::assertArrayHasKey('text', $response);
        static::assertArrayHasKey('response_type', $response);
        static::assertSame('in_channel', $response['response_type']);
        static::assertNotSame($this->calcExpectedMessage(new \DateTimeImmutable()), $response['text']);
    }

    /*
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

