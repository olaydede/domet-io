<?php

namespace App\Tests\Unit\Controller;

class HomeControllerTest extends AbstractDometWebTestCase
{
    /** @test */
    public function ifNoUserAuthenticatedRedirectsToLogin(): void
    {
        $crawler = $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects();
    }
}
