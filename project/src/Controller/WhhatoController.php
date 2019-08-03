<?php declare(strict_types=1);

namespace App\Controller;

use App\Whhato\DateMessageNotFoundException;
use App\Whhato\Whhato;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class WhhatoController
{
    public function index(Environment $twig): Response
    {
        return new Response($twig->render('index.html.twig'));
    }

    public function whatHappenedToday(Whhato $whhato): JsonResponse
    {
        $date = new \DateTime('now');

        try {
            $message = $whhato->getRandomDateMessage($date);

            return new JsonResponse(['text' => $message->format($date), 'response_type' => 'in_channel',]);
        } catch (DateMessageNotFoundException $dateMessageNotFoundException) {
            return new JsonResponse(['text' => $dateMessageNotFoundException->getMessage()], 404);
        }
    }
}
