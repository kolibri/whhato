<?php declare(strict_types=1);

namespace App\Controller;

use App\Whhato\DateMessageNotFoundException;
use App\Whhato\Whhato;
use Symfony\Component\HttpFoundation\JsonResponse;

class WhhatoController
{
    private $whhato;

    public function __construct(Whhato $whhato)
    {
        $this->whhato = $whhato;
    }

    public function whatHappendToday()
    {
        $date = new \DateTime('now');

        try {
            $message = $this->whhato->getRandomDateMessage($date);
            return new JsonResponse(['message' => $message->format($date)]);
        } catch (DateMessageNotFoundException $dateMessageNotFoundException) {
            return new JsonResponse(['error' => $dateMessageNotFoundException->getMessage()], 404);
        }
    }
}
