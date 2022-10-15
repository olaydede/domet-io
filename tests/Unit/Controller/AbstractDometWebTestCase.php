<?php
namespace App\Tests\Unit\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractDometWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->setServerParameter('HTTP_HOST', $_ENV['APP_DOMAIN']);
        parent::setUp();
    }


}