<?php

namespace Pledg\Bundle\PaymentBundle\Integration;

use Oro\Bundle\IntegrationBundle\Provider\ChannelInterface;
use Oro\Bundle\IntegrationBundle\Provider\IconAwareIntegrationInterface;

class PledgChannelType implements ChannelInterface, IconAwareIntegrationInterface
{
    public const TYPE = 'pledg';

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'pledg.channel_type.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return 'bundles/pledgpayment/img/pledg.png';
    }
}
