<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Assodeo - Se connecter');

        $client->submitForm('Se connecter', [
            '_username' => 'john.doe@example.com',
            '_password' => 'password',
        ]);

        self::assertResponseRedirects('/');
    }

    public function testLoginWithInvalidUsername(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Assodeo - Se connecter');

        $client->submitForm('Se connecter', [
            '_username' => 'jane.doe@example.com',
            '_password' => 'password',
        ]);

        $client->followRedirect();

        self::assertSelectorTextContains('.alert', 'Identifiants invalides.');
    }

    public function testLoginWithInvalidPassword()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Assodeo - Se connecter');

        $client->submitForm('Se connecter', [
            '_username' => 'john.doe@example.com',
            '_password' => 'invalid_password',
        ]);

        $client->followRedirect();

        self::assertSelectorTextContains('.alert', 'Identifiants invalides');
    }
}
