<?php

declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Client;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SlackController
{
    private $oauthClientId;
    private $oauthClientSecret;

    public function __construct(string $oauthClientId, string $oauthClientSecret)
    {
        $this->oauthClientId = $oauthClientId;
        $this->oauthClientSecret = $oauthClientSecret;
    }

    /** @Route("/connect/slack", name="connect_slack_start") */
    public function connectAction(ClientRegistry $clientRegistry): Response
    {
        return $clientRegistry
            ->getClient('slack')
            ->redirect(['commands']);
    }

    /** @Route("/connect/slack/check", name="connect_slack_check") */
    public function connectCheckAction(Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        $url = sprintf(
                'https://slack.com/api/oauth.access?code=%s&client_id=%s&client_secret=%s&redirect_uri=%s',
                $request->query->get('code'),
                $this->oauthClientId,
                $this->oauthClientSecret,
                $urlGenerator->generate('connect_slack_check', [], UrlGeneratorInterface::ABSOLUTE_URL)
            );

        $guzzleClient = new Client();
        $response = $guzzleClient->request('GET', $url);

        if (200 !== $response->getStatusCode()) {
            return new Response('Something went wrong with access slack');
        }

        return new Response('ok. Everything should work now');
    }
}
