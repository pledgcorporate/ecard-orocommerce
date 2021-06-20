<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\Notification\Collector;

use Oro\Bundle\PaymentBundle\Method\Provider\PaymentMethodProviderInterface;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfig;
use Pledg\Bundle\PaymentBundle\Method\Config\Provider\PledgConfigProviderInterface;
use Pledg\Bundle\PaymentBundle\Provider\PaymentTransactionProviderInterface;

class StandardValidator implements ValidatorInterface
{
    /** @var PaymentTransactionProviderInterface */
    protected $paymentTransactionProvider;

    /** @var PaymentMethodProviderInterface  */
    protected $paymentMethodProvider;

    /** @var PledgConfigProviderInterface */
    protected $pledgConfigProvider;

    protected const REQUIRED_KEYS = [
        'created_at',
        'id',
        'additional_data',
        'metadata',
        'status',
        'sandbox',
        'error',
        'reference',
        'signature',
    ];

    protected const SIGNED_KEYS = [
        'created_at',
        'error',
        'id',
        'reference',
        'sandbox',
        'status',
    ];

    public function __construct(
        PaymentTransactionProviderInterface $paymentTransactionProvider,
        PledgConfigProviderInterface $pledgConfigProvider
    ) {
        $this->paymentTransactionProvider = $paymentTransactionProvider;
        $this->pledgConfigProvider = $pledgConfigProvider;
    }

    public function supports(array $content): bool
    {
        return count(self::REQUIRED_KEYS) === count(array_intersect_key(array_flip(self::REQUIRED_KEYS), $content));
    }

    public function validate(array $content): bool
    {
        return $this->withoutError($content) && $this->validateSignature($content);
    }

    protected function withoutError(array $content): bool
    {
        return empty($content['error']);
    }

    protected function validateSignature(array $content): bool
    {
        $paymentTransaction = $this->paymentTransactionProvider->getByReference($content['reference']);

        if (null === $paymentTransaction) {
            return false;
        }

        /** @var PledgConfig $pledgConfig */
        $pledgConfig = $this->pledgConfigProvider->getPaymentConfig($paymentTransaction->getPaymentMethod());

        $signature = $this->createSignature($content, $pledgConfig);

        return $content['signature'] === $signature;
    }

    protected function createSignature(array $content, PledgConfig $pledgConfig): string
    {
        $parameters = $this->retrieveSignedParameters($content);
        /** @var string $hash */
        $hash = hash('SHA256', implode($pledgConfig->getClientSecret(), $parameters));

        return strtoupper($hash);
    }

    protected function retrieveSignedParameters(array $content): array
    {
        $parameters = array_intersect_key($content, array_flip(self::SIGNED_KEYS));
        ksort($parameters);

        return array_map(static function (string $key, $value): string {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            return sprintf('%s=%s', $key, $value);
        }, array_keys($parameters), $parameters);
    }
}
