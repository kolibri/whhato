<?php

namespace App\Whhato;

use Doctrine\ORM\EntityRepository;

class DoctrineLoader extends EntityRepository implements LoaderInterface
{
    public function findByDate(\DateTimeInterface $dateTime): array
    {
        return $this->createQueryBuilder('dm')
            ->where('dm.monthDay = :monthDay')
            ->setParameter('monthDay', $dateTime->format(Whhato::FORMAT_MONTH_DAY))
            ->getQuery()
            ->execute();
    }
}
