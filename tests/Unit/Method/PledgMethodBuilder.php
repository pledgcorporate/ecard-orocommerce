<?php


namespace Tests\Pledg\Bundle\PaymentBundle\Unit\Method;

use Pledg\Bundle\PaymentBundle\Factory\ReferenceFactoryInterface;
use Pledg\Bundle\PaymentBundle\JWT\HandlerInterface;
use Pledg\Bundle\PaymentBundle\JWT\HS256Handler;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\Method\PledgMethod;
use Pledg\Bundle\PaymentBundle\RedirectUrl\ParametersFactoryInterface;
use Prophecy\Prophet;

class PledgMethodBuilder
{
    /** @var ParametersFactoryInterface */
    private $parametersFactory;

    /** @var ReferenceFactoryInterface */
    private $referenceFactory;

    /** @var HandlerInterface */
    private $encoder;

    /** @var PledgConfigInterface */
    private $config;

    public function __construct()
    {
        $this->parametersFactory = (new Prophet())
            ->prophesize(ParametersFactoryInterface::class)
            ->reveal();

        $this->referenceFactory = (new Prophet())
            ->prophesize(ReferenceFactoryInterface::class)
            ->reveal();

        $this->encoder = new HS256Handler();
    }

    public function withConfig(PledgConfigInterface $config): self
    {
        $this->config = $config;

        return $this;
    }
    public function build(): PledgMethod
    {
        return new PledgMethod($this->config, $this->parametersFactory, $this->referenceFactory, $this->encoder);
    }
}
