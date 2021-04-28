<?php


namespace Pledg\Bundle\PaymentBundle\Method\Factory;


use Pledg\Bundle\PaymentBundle\Factory\ReferenceFactoryInterface;
use Pledg\Bundle\PaymentBundle\JWT\HandlerInterface;
use Pledg\Bundle\PaymentBundle\Method\Config\PledgConfigInterface;
use Pledg\Bundle\PaymentBundle\Method\PledgMethod;
use Pledg\Bundle\PaymentBundle\RedirectUrl\ParametersFactoryInterface;

class PledgPaymentMethodFactory implements PledgPaymentMethodFactoryInterface
{
    /** @var ParametersFactoryInterface */
    private $parametersFactory;

    /** @var ReferenceFactoryInterface */
    private $referenceFactory;

    /** @var HandlerInterface */
    private $encoder;

    public function __construct(
        ParametersFactoryInterface $parametersFactory,
        ReferenceFactoryInterface $referenceFactory,
        HandlerInterface $encoder
    )
    {
        $this->parametersFactory = $parametersFactory;
        $this->referenceFactory = $referenceFactory;
        $this->encoder = $encoder;
    }

    /**
     * {@inheritdoc}
     */
    public function create(PledgConfigInterface $config)
    {
        return new PledgMethod($config, $this->parametersFactory, $this->referenceFactory, $this->encoder);
    }
}
