<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WhhatoControllerTest extends WebTestCase
{
    public function testIndexPageWorks(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        static::assertTrue($client->getResponse()->isSuccessful());
        static::assertGreaterThan(0, $crawler->filter('h1')->count());
    }

    public function testOverviewWorks(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/overview');
        static::assertTrue($client->getResponse()->isSuccessful());
        static::assertSame(12, $crawler->filter('li.month')->count());
        static::assertSame(365, $crawler->filter('li.day')->count());
    }

    public function testWhatHappenedTodayActionWithoutGivenDate(): void
    {
        $client = static::createClient();
        $client->request('POST', '/whhato');
        static::assertTrue($client->getResponse()->isSuccessful());
        static::assertSame(
            sprintf(
                '{"text":"%s","response_type":"in_channel"}',
                $this->calcExpectedMessage(new \DateTimeImmutable())
            ),
            $client->getResponse()->getContent()
        );
    }

    public function testWhatHappenedTodayActionWithGivenDate(): void
    {
        $client = static::createClient();
        $client->request('POST', '/whhato/25-01-2015');
        static::assertTrue($client->getResponse()->isSuccessful());
        static::assertSame('{"text":"Its been 38 years since this day in 1977","response_type":"in_channel"}',
            $client->getResponse()->getContent()
        );
    }

    public function testWhatHappenedTodayActionWithWrongDateFormatGivesNotFound(): void
    {
        $client = static::createClient();
        $client->request('POST', '/whhato/foobar');
        static::assertTrue($client->getResponse()->isNotFound());
    }

    public function testProdReturnsSomething(): void
    {
        $client = static::createClient(['environment' => 'prod', 'debug' => false]);
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
        $expectedYear = 2001 - (int) $givenDate->format('z');

        return sprintf(
            'Its been %s years since this day in %s',
            $givenDate->format('Y') - $expectedYear,
            $expectedYear
        );
    }
}
