<?php

namespace Ojs\JournalBundle\Entity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * NumeratorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NumeratorRepository extends EntityRepository
{
    /**
     * @param Journal $journal
     * @return Numerator
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getArticleNumerator(Journal $journal)
    {
        return $this
            ->createQueryBuilder('numerator')
            ->andWhere('numerator.journal = :journal')
            ->andWhere('numerator.type = :type')
            ->setParameter('journal', $journal)
            ->setParameter('type', 'article')
            ->getQuery()
            ->getSingleResult(Query::HYDRATE_OBJECT);
    }
}