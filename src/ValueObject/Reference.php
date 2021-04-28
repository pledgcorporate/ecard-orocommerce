<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\ValueObject;

use Webmozart\Assert\Assert;

class Reference implements ReferenceInterface
{
    /** @var string */
    private $id;

    /** @var int */
    private $orderId;

    private const PREFIX = 'PLEDG';

    public function __construct(int $orderId, string $id)
    {
        $this->orderId = $orderId;
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function __toString(): string
    {
        return sprintf('%s_%d_%s', self::PREFIX, $this->orderId, $this->id);
    }

    public static function fromString(string $reference): self
    {
        $parts = explode('_', $reference);

        Assert::count($parts, 3, sprintf('The reference %s is invalid the format should be PLEG_ORDERID_PAYMENTID', $reference));
        Assert::eq($parts[0], self::PREFIX);
        $vo = new self((int) $parts[1], (string) $parts[2]);

        Assert::eq($reference, (string) $vo, sprintf('The reference is invalid : %s provide %s expected', $reference, (string) $vo));

        return $vo;
    }
}
