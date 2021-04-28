<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\RedirectUrl;

use Oro\Bundle\OrderBundle\Entity\OrderAddress;
use Pledg\Bundle\PaymentBundle\ValueObject\Reference;

class Parameters implements ParametersInterface
{
    /** @var string */
    public $merchantUid;
    /** @var string */
    public $title;
    /** @var string */
    public $lang;
    /** @var Reference */
    public $reference;
    /** @var int */
    public $amountCents;
    /** @var string */
    public $currency;
    /** @var string */
    public $civility;
    /** @var string */
    public $firstName;
    /** @var string */
    public $lastName;
    /** @var string */
    public $email;
    /** @var string */
    public $phoneNumber;
    /** @var OrderAddress */
    public $billingAddress;
    /** @var OrderAddress */
    public $shippingAddress;
    /** @var string */
    public $redirectUrl;
    /** @var string */
    public $failedUrl;

    public function toArray(): array
    {
        return [
            'merchantUid' => $this->merchantUid,
            'title' => $this->title,
            'lang' => $this->lang,
            'reference' => $this->reference,
            'amountCents' => $this->amountCents,
            'currency' => $this->currency,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber,
            'address' => $this->buildAddress($this->billingAddress),
            'shippingAddress' => $this->buildAddress($this->shippingAddress),
            'countryCode' => $this->billingAddress->getCountryIso2(),
            'redirectUrl' => $this->redirectUrl,
            'cancelUrl' => $this->failedUrl,
            'paymentNotificationUrl' => 'mailto:'. $this->email,
        ];
    }

    private function buildAddress(OrderAddress $address): array
    {
        return [
            'street' => $address->getStreet(),
            'city' => $address->getCity(),
            'zipcode' => $address->getPostalCode(),
            'country' => $address->getCountryIso2(),
        ];
    }
}
