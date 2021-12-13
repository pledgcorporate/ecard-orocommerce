<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\Notification\Validator;

use Pledg\Bundle\PaymentBundle\JWT\HandlerInterface;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfig;
use Pledg\Bundle\PaymentBundle\Method\Config\Provider\PledgConfigProviderInterface;
use Pledg\Bundle\PaymentBundle\Provider\PaymentTransactionProviderInterface;
use Webmozart\Assert\Assert;

class TransferValidator implements ValidatorInterface
{
    protected const SIGNATURE_KEY = 'signature';

    /** @var PaymentTransactionProviderInterface */
    protected $paymentTransactionProvider;

    /** @var PledgConfigProviderInterface */
    protected $pledgConfigProvider;

    /** @var HandlerInterface */
    protected $handler;

    public function __construct(
        PaymentTransactionProviderInterface $paymentTransactionProvider,
        PledgConfigProviderInterface $pledgConfigProvider,
        HandlerInterface $handler
    ) {
        $this->paymentTransactionProvider = $paymentTransactionProvider;
        $this->pledgConfigProvider = $pledgConfigProvider;
        $this->handler = $handler;
    }

    private function supports(array $content): bool
    {
        return 1 === count($content) && array_key_exists(self::SIGNATURE_KEY, $content);
    }

    public function validate(array $content): bool
    {
        if (false === $this->supports($content)) {
            throw NotSupportedException::fromContent($content);
        }

        $body = $this->handler->decode($content[self::SIGNATURE_KEY]);

        Assert::keyExists($body, 'reference');

        $paymentTransaction = $this->paymentTransactionProvider->getByReference($body['reference']);

        if (null === $paymentTransaction) {
            return false;
        }

        /** @var PledgConfig $pledgConfig */
        $pledgConfig = $this->pledgConfigProvider->getPaymentConfig($paymentTransaction->getPaymentMethod());

        return $this->handler->verify($content[self::SIGNATURE_KEY], $pledgConfig->getClientSecret())
            && isset($body['transfer_order_item_uid']);
    }
}
