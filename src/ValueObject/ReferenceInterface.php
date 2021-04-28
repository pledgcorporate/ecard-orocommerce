<?php


namespace Pledg\Bundle\PaymentBundle\ValueObject;


interface ReferenceInterface
{
    public function __toString(): string;
    public static function fromString(string $reference): self;
}
