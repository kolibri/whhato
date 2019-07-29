<?php

declare(strict_types=1);

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\SlackClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SlackController
{
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('slack')
            ->redirect(['commands']      );
    }

    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        /** @var SlackClient $client */
        $client = $clientRegistry->getClient('slack');

        try {
            // the exact class depends on which provider you're using
            /** @var SlackClient $user */
            $user = $client->fetchUser();

            // do something with all this new power!
            // e.g. $name = $user->getFirstName();
            var_dump($user); die;
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage()); die;
        }
    }
}
