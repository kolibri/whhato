<?php declare(strict_types=1);

namespace App\Controller;

use App\Whhato\DateMessageNotFoundException;
use App\Whhato\Whhato;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class WhhatoController
{
    private $whhato;

    public function __construct(Whhato $whhato)
    {
        $this->whhato = $whhato;
    }

    public function index()
    {
        return new Response(file_get_contents(__DIR__ . '/../../data/index.html'));
    }

    public function whatHappendToday()
    {
        $date = new \DateTime('now');

        try {
            $message = $this->whhato->getRandomDateMessage($date);
            return new JsonResponse(['text' => $message->format($date)]);
        } catch (DateMessageNotFoundException $dateMessageNotFoundException) {
            return new JsonResponse(['text' => $dateMessageNotFoundException->getMessage()], 404);
        }
    }
}
