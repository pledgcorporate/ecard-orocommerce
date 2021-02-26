<?php

namespace Pledg\Bundle\PaymentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Pledg\Bundle\PaymentBundle\Entity\PledgSettings;

class PledgSettingsRepository extends EntityRepository
{
    /**
     * @return PledgSettings[]
     */
    public function getEnabledSettings()
    {
        return $this->createQueryBuilder('settings')
            ->innerJoin('settings.channel', 'channel')
            ->andWhere('channel.enabled = true')
            ->getQuery()
            ->getResult();
    }
}
