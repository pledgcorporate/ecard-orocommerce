<?php

namespace Pledg\Bundle\PaymentBundle\Method\View\Provider;

use Oro\Bundle\PaymentBundle\Method\View\AbstractPaymentMethodViewProvider;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\Method\Config\Provider\PledgConfigProviderInterface;
use Pledg\Bundle\PaymentBundle\Method\View\Factory\PledgViewFactoryInterface;

class PledgViewProvider extends AbstractPaymentMethodViewProvider
{
    /** @var PledgViewFactoryInterface */
    private $factory;

    /** @var PledgConfigProviderInterface */
    private $configProvider;

    /**
     * @param PledgConfigProviderInterface $configProvider
     * @param PledgViewFactoryInterface $factory
     */
    public function __construct(
        PledgConfigProviderInterface $configProvider,
        PledgViewFactoryInterface $factory
    ) {
        $this->factory = $factory;
        $this->configProvider = $configProvider;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function buildViews()
    {
        $configs = $this->configProvider->getPaymentConfigs();
        foreach ($configs as $config) {
            $this->addPledgView($config);
        }
    }

    /**
     * @param PledgConfigInterface $config
     */
    protected function addPledgView(PledgConfigInterface $config): void
    {
        $this->addView(
            $config->getPaymentMethodIdentifier(),
            $this->factory->create($config)
        );
    }
}
