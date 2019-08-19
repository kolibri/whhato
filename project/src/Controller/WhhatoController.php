<?php

declare(strict_types=1);

namespace App\Controller;

use App\Whhato\DateMessageNotFoundException;
use App\Whhato\Whhato;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class WhhatoController
{
    /** @Route("/whhato/{dateString<\d\d-\d\d-\d\d\d\d>}", name="whhato", methods={"GET", "HEAD", "POST"})) */
    public function randomMessage(Whhato $whhato, string $dateString = ''): JsonResponse
    {
        $date = '' !== $dateString ?
            \DateTimeImmutable::createFromFormat('d-m-Y', $dateString) :
            new \DateTimeImmutable('now');

        try {
            $message = $whhato->getRandomDateMessage($date);

            return new JsonResponse(['text' => $message->format($date), 'response_type' => 'in_channel']);
        } catch (DateMessageNotFoundException $dateMessageNotFoundException) {
            return new JsonResponse(['text' => $dateMessageNotFoundException->getMessage()], 404);
        }
    }

    /** @Route("/", name="index", methods={"GET", "HEAD"}) */
    public function index(Whhato $whhato, Environment $twig): Response
    {
        return new Response($twig->render('index.html.twig', ['whhato' => $whhato]));
    }

    /** @Route("/overview", name="overview", methods={"GET", "HEAD"}) */
    public function overview(Whhato $whhato, Environment $twig): Response
    {
        return new Response($twig->render('overview.html.twig', ['all' => $whhato->overview()]));
    }
}
