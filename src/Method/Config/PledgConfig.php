<?php

namespace Pledg\Bundle\PaymentBundle\Method\Config;

use Oro\Bundle\PaymentBundle\Method\Config\ParameterBag\AbstractParameterBagPaymentConfig;

class PledgConfig extends AbstractParameterBagPaymentConfig implements PledgConfigInterface
{
    public const FIELD_CLIENT_IDENTIFIER = 'client_identifier';
    public const FIELD_CLIENT_SECRET = 'client_secret';

    public function getClientIdentifier()
    {
        return $this->get(self::FIELD_CLIENT_IDENTIFIER);
    }

    public function getClientSecret()
    {
        return $this->get(self::FIELD_CLIENT_SECRET);
    }

}
