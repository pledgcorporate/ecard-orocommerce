<?php


namespace Tests\Pledg\Bundle\PaymentBundle\Unit\RedirectUrl;


use Oro\Bundle\CustomerBundle\Entity\CustomerAddress;
use Oro\Bundle\CustomerBundle\Entity\CustomerUser;
use Oro\Bundle\CustomerBundle\Entity\CustomerUserAddress;
use Oro\Bundle\LocaleBundle\Helper\LocalizationHelper;
use Oro\Bundle\OrderBundle\Entity\Order;
use Oro\Bundle\OrderBundle\Entity\OrderAddress;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\Provider\OrderProviderInterface;
use Pledg\Bundle\PaymentBundle\RedirectUrl\ParametersFactory;
use Pledg\Bundle\PaymentBundle\RedirectUrl\ParametersInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Prophecy\Prophet;
use Symfony\Component\Routing\RouterInterface;

class ParametersBuilder
{
    /** @var LocalizationHelper|ObjectProphecy */
    protected $localizationHelper;

    /** @var PaymentTransaction */
    protected $paymentTransaction;

    /** @var OrderProviderInterface */
    protected $orderProvider;

    /** @var PledgConfigInterface */
    protected $config;

    /** @var RouterInterface */
    protected $router;

    /** @var CustomerAddress */
    private $billingAddress;

    /** @var CustomerAddress */
    private $shippingAddress;

    public function __construct()
    {
        $this->localizationHelper = (new Prophet())->prophesize(LocalizationHelper::class);
        $this->router = (new Prophet())->prophesize(RouterInterface::class)->reveal();

        $paymentTransaction = (new Prophet())->prophesize(PaymentTransaction::class);
        $paymentTransaction->getFrontendOwner()->willReturn(new CustomerUser());
        $this->paymentTransaction = $paymentTransaction->reveal();

    }

    public function withLocale(string $locale): self
    {
        $this->localizationHelper->getCurrentLocalization()->willReturn($locale);

        return $this;
    }

    public function withPaymentTransaction(PaymentTransaction $paymentTransaction): self
    {
        $this->paymentTransaction = $paymentTransaction;

        return $this;
    }

    public function withConfig(PledgConfigInterface $config): self
    {
        $this->config = $config;

        return $this;
    }

    public function withBillingAddress(CustomerAddress $address): self
    {
        $this->billingAddress = $address;

        return $this;
    }

    public function withShippingAddress(CustomerAddress $address): self
    {
        $this->shippingAddress = $address;

        return $this;
    }

    public function build(): ParametersInterface
    {
        $this->buildOrderProvider();

        return (new ParametersFactory($this->localizationHelper->reveal(), $this->orderProvider, $this->router))
            ->fromPaymentTransactionAndConfig($this->paymentTransaction, $this->config);
    }

    private function buildOrderProvider(): void
    {
        $orderProvider = (new Prophet())->prophesize(OrderProviderInterface::class);
        $orderProvider->findByPaymentTransaction($this->paymentTransaction)->willReturn(
            (new Order())
                ->setBillingAddress(
                    (new OrderAddress())
                        ->setCustomerAddress($this->billingAddress)
                )
                ->setShippingAddress(
                    (new OrderAddress())
                        ->setCustomerAddress($this->shippingAddress)
                )
        );

        $this->orderProvider = $orderProvider->reveal();
    }
}
