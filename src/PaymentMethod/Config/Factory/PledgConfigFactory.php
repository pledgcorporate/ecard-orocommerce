<?php

namespace Pledg\Bundle\PaymentBundle\PaymentMethod\Config\Factory;

use Doctrine\Common\Collections\Collection;
use Oro\Bundle\IntegrationBundle\Generator\IntegrationIdentifierGeneratorInterface;
use Oro\Bundle\LocaleBundle\Helper\LocalizationHelper;
use Pledg\Bundle\PaymentBundle\PaymentMethod\Config\PledgConfig;
use Pledg\Bundle\PaymentBundle\Entity\PledgSettings;

class PledgConfigFactory implements PledgConfigFactoryInterface
{
    /**
     * @var LocalizationHelper
     */
    private $localizationHelper;

    /**
     * @var IntegrationIdentifierGeneratorInterface
     */
    private $identifierGenerator;

    /**
     * @param LocalizationHelper $localizationHelper
     * @param IntegrationIdentifierGeneratorInterface $identifierGenerator
     */
    public function __construct(
        LocalizationHelper $localizationHelper,
        IntegrationIdentifierGeneratorInterface $identifierGenerator
    ) {
        $this->localizationHelper = $localizationHelper;
        $this->identifierGenerator = $identifierGenerator;
    }

    public function create(PledgSettings $settings)
    {
        $params = [];
        $channel = $settings->getChannel();

        $params[PledgConfig::FIELD_LABEL] = $this->getLocalizedValue($settings->getLabels());
        $params[PledgConfig::FIELD_SHORT_LABEL] = $this->getLocalizedValue($settings->getShortLabels());
        $params[PledgConfig::FIELD_ADMIN_LABEL] = $channel->getName();
        $params[PledgConfig::FIELD_CLIENT_IDENTIFIER] = $settings->getClientIdentifier();
        $params[PledgConfig::FIELD_CLIENT_SECRET] = $settings->getClientSecret();
        $params[PledgConfig::FIELD_PAYMENT_METHOD_IDENTIFIER] =
            $this->identifierGenerator->generateIdentifier($channel);

        return new PledgConfig($params);
    }

    /**
     * @param Collection $values
     *
     * @return string
     */
    private function getLocalizedValue(Collection $values)
    {
        return (string) $this->localizationHelper->getLocalizedValue($values);
    }

}
