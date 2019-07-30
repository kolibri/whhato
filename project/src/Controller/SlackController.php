<?php

declare(strict_types=1);

namespace App\Controller;

use GuzzleHttp\Client;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Client\Provider\SlackClient;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SlackController extends Controller
{
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
            ->getClient('slack')
            ->redirect(['commands']);
    }

    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        try {
            $url = sprintf(
                'https://slack.com/api/oauth.access?code=%s&client_id=%s&client_secret=%s&redirect_uri=%s',
                $request->query->get('code'),
                $this->getParameter('oauth_client_id'),
                $this->getParameter('oauth_client_secret'),
                $this->generateUrl('connect_slack_check', [], UrlGeneratorInterface::ABSOLUTE_URL)
            );

            $guzzleClient = new Client();
            $response = $guzzleClient->request('GET', $url);

            if(200 !== $response->getStatusCode()) {
                return new Response('Something went wrong with access slack');
            }

            return new Response('ok. Everything should work now');
        } catch (IdentityProviderException $e) {
            return new Response('error', 500);
        }
    }
}
