<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains("Assodeo - S'inscrire");

        $client->submitForm("S'inscrire", [
            'registration_form[email]' => 'jane.doe@example.com',
            'registration_form[firstName]' => 'Jane',
            'registration_form[lastName]' => 'Doe',
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true,
        ]);

        self::assertResponseRedirects('/');

        $container = static::getContainer();

        /** @var UserRepository $userRepository */
        $userRepository = $container->get(UserRepository::class);

        $user = $userRepository->findOneBy(['email' => 'jane.doe@example.com']);

        self::assertNotNull($user);

        self::assertSame('Jane', $user->getFirstName());
        self::assertSame('Doe', $user->getLastName());
    }

    public function testRegisterWithExistingEmail(): void
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        self::assertResponseIsSuccessful();
        self::assertPageTitleContains("Assodeo - S'inscrire");

        $client->submitForm("S'inscrire", [
            'registration_form[email]' => 'john.doe@example.com',
            'registration_form[firstName]' => 'John',
            'registration_form[lastName]' => 'Doe',
            'registration_form[plainPassword]' => 'password',
            'registration_form[agreeTerms]' => true,
        ]);

        self::assertResponseStatusCodeSame(422);

        self::assertSelectorTextContains('.invalid-feedback', 'Cet email est déjà utilisé.');
    }
}
