<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\RedirectUrl;

use Oro\Bundle\AddressBundle\Entity\AddressType;
use Oro\Bundle\CustomerBundle\Entity\CustomerUserAddress;
use Oro\Bundle\LocaleBundle\Entity\Localization;
use Oro\Bundle\LocaleBundle\Helper\LocalizationHelper;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\Provider\OrderProviderInterface;
use Pledg\Bundle\PaymentBundle\ValueObject\Reference;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ParametersFactory implements ParametersFactoryInterface
{
    /** @var RouterInterface */
    private $router;

    /** @var LocalizationHelper */
    private $localizationHelper;

    /** @var OrderProviderInterface */
    private $orderProvider;

    public function __construct(
        LocalizationHelper $localizationHelper,
        OrderProviderInterface $orderProvider,
        RouterInterface $router
    ) {
        $this->localizationHelper = $localizationHelper;
        $this->orderProvider = $orderProvider;
        $this->router = $router;
    }

    public function fromPaymentTransactionAndConfig(
        PaymentTransaction $paymentTransaction,
        PledgConfigInterface $config
    ): ParametersInterface
    {
        $order = $this->orderProvider->findByPaymentTransaction($paymentTransaction);
        $parameters = new Parameters();
        $parameters->merchantUid = $config->getClientIdentifier();
        $parameters->title = 'title'; // todo
        $parameters->lang = 'fr_FR'; //$this->localizationHelper->getCurrentLocalization() instanceof Localization
            //? $this->localizationHelper->getCurrentLocalization()->getLanguageCode()
            //: null;
        $parameters->reference = $paymentTransaction->getReference();
        $parameters->amountCents = (int) ($paymentTransaction->getAmount() * 100);
        $parameters->currency = 'EUR'; //$paymentTransaction->getCurrency();
        $parameters->firstName = $paymentTransaction->getFrontendOwner()->getFirstName();
        $parameters->lastName = $paymentTransaction->getFrontendOwner()->getLastName();
        $parameters->email = $paymentTransaction->getFrontendOwner()->getEmail();
        $parameters->phoneNumber = '0666666666';
        $parameters->billingAddress = $order->getBillingAddress();
        $parameters->shippingAddress = $order->getShippingAddress();
        $parameters->redirectUrl = $this->router->generate(
            'oro_payment_callback_return',
            ['accessIdentifier' => $paymentTransaction->getAccessIdentifier()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $parameters->failedUrl = $this->router->generate(
            'oro_payment_callback_error',
            ['accessIdentifier' => $paymentTransaction->getAccessIdentifier(),],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $parameters->notificationUrl = $this->router->generate(
            'oro_payment_callback_notify',
            [
                'accessIdentifier' => $paymentTransaction->getAccessIdentifier(),
                'accessToken' => $paymentTransaction->getAccessToken()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        return $parameters;
    }
}
