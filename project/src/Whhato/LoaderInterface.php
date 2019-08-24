<?php

namespace App\Whhato;

interface LoaderInterface
{
    /** @return DateMessage[] */
    public function findByDate(\DateTimeInterface $dateTime): array;
}
