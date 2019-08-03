<?php declare(strict_types=1);

namespace App\Controller;

use App\Whhato\DateMessageNotFoundException;
use App\Whhato\Overview;
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

    public function whatHappenedToday(string $dateString, Whhato $whhato): JsonResponse
    {
        $date = new \DateTime('now');

        if('' !== $dateString) {
            dump($dateString);
            $date = \DateTimeImmutable::createFromFormat('d-m-Y', $dateString);
        }

        try {
            $message = $whhato->getRandomDateMessage($date);

            return new JsonResponse(['text' => $message->format($date), 'response_type' => 'in_channel',]);
        } catch (DateMessageNotFoundException $dateMessageNotFoundException) {
            return new JsonResponse(['text' => $dateMessageNotFoundException->getMessage()], 404);
        }
    }

    public function overview(Whhato $whhato, Environment $twig)
    {
        return new Response($twig->render('overview.html.twig', ['all' => $whhato->overview()]));
    }
}
