<?php


namespace Pledg\Bundle\PaymentBundle\Integration;


use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;
use Pledg\Bundle\PaymentBundle\Entity\PledgSettings;
use Pledg\Bundle\PaymentBundle\Form\Type\PledgSettingsType;

class PledgTransport implements TransportInterface
{
    /**
     * {@inheritdoc}
     */
    public function init(Transport $transportEntity)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'pledg.settings.transport.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsFormType()
    {
        return PledgSettingsType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsEntityFQCN()
    {
        return PledgSettings::class;
    }
}
