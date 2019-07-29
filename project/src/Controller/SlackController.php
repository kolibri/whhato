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
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');

        return $clientRegistry
            ->getClient('slack_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'public_profile', 'email' // the scopes you want to access
            ])
            ;
    }

    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        /** @var SlackClient $client */
        $client = $clientRegistry->getClient('slack_main');

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
