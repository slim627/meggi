<?php

namespace Meggi\IndexBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ConfigRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConfigRepository extends EntityRepository
{
    public function getOwnConfig($keyValue)
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('config')
            ->from('MeggiIndexBundle:Config', 'config')
            ->andWhere('config.keyValue = :keyValue')
            ->setParameters(['keyValue' => $keyValue])
            ->getQuery()
            ->getOneOrNullResult();
    }
}