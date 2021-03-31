<?php


namespace Pledg\Bundle\PaymentBundle\Method\Config\Provider;


use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Pledg\Bundle\PaymentBundle\Method\Config\Factory\PledgConfigFactoryInterface;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\Entity\PledgSettings;
use Pledg\Bundle\PaymentBundle\Repository\PledgSettingsRepository;
use Psr\Log\LoggerInterface;

class PledgConfigProvider implements PledgConfigProviderInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @var PledgConfigFactoryInterface
     */
    protected $configFactory;

    /**
     * @var PledgConfigInterface[]
     */
    protected $configs;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param ManagerRegistry $doctrine
     * @param LoggerInterface $logger
     * @param PledgConfigFactoryInterface $configFactory
     */
    public function __construct(
        ManagerRegistry $doctrine,
        LoggerInterface $logger,
        PledgConfigFactoryInterface $configFactory
    ) {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->configFactory = $configFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentConfigs()
    {
        $configs = [];

        $settings = $this->getEnabledIntegrationSettings();

        foreach ($settings as $setting) {
            $config = $this->configFactory->create($setting);

            $configs[$config->getPaymentMethodIdentifier()] = $config;
        }

        return $configs;
    }

    /**
     * {@inheritDoc}
     */
    public function getPaymentConfig($identifier)
    {
        $paymentConfigs = $this->getPaymentConfigs();

        if ([] === $paymentConfigs || false === array_key_exists($identifier, $paymentConfigs)) {
            return null;
        }

        return $paymentConfigs[$identifier];
    }

    /**
     * {@inheritDoc}
     */
    public function hasPaymentConfig($identifier)
    {
        return null !== $this->getPaymentConfig($identifier);
    }

    /**
     * @return PledgSettings[]
     */
    protected function getEnabledIntegrationSettings()
    {
        try {
            /** @var ObjectManager $objectManager */
            $objectManager = $this->doctrine->getManagerForClass(PledgSettings::class);
            /** @var PledgSettingsRepository $repository */
            $repository = $objectManager->getRepository(PledgSettings::class);

            return $repository->getEnabledSettings();
        } catch (\UnexpectedValueException $e) {
            $this->logger->critical($e->getMessage());

            return [];
        }
    }
}
