<?php

namespace Jaxyeh\UrlShortenerBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertTrue($crawler->filter('html:contains("Web Address")')->count() > 0);
    }

    public function testBadUrlWithNotFound()
    {
      $client = static::createClient();

      $crawler = $client->request('GET', '/this$houldNotWork');

      $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
