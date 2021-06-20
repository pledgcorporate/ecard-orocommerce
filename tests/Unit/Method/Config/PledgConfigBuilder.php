<?php

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\Method\Config;

use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfig;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;

class PledgConfigBuilder
{
    /** @var array */
    private $params;

    public function withDefaultValues(): self
    {
        $this->params = [
            PledgConfig::FIELD_LABEL => 'field label',
            PledgConfig::FIELD_SHORT_LABEL => 'short field label',
            PledgConfig::FIELD_ADMIN_LABEL => 'ecommerce channel',
            PledgConfig::FIELD_CLIENT_IDENTIFIER => '1234567890987654321',
            PledgConfig::FIELD_CLIENT_SECRET => 'SECRET',
            PledgConfig::FIELD_PAYMENT_METHOD_IDENTIFIER => 12345
        ];

        return $this;
    }

    public function build(): PledgConfigInterface
    {
        return new PledgConfig($this->params);
    }
}
