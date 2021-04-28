<?php


namespace Tests\Pledg\Bundle\PaymentBundle\Unit\Oro\Entity;


use Oro\Bundle\CustomerBundle\Entity\CustomerAddress;

class CustomerAddressBuilder
{
    /** @var string */
    private $street;

    /** @var string */
    private $city;

    /** @var string */
    private $postalCode;

    public function withStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function withCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function withPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function build(): CustomerAddress
    {
        $address = new CustomerAddress();
        $address->setStreet($this->street);
        $address->setCity($this->city);
        $address->setPostalCode($this->postalCode);

        return $address;
    }
}
