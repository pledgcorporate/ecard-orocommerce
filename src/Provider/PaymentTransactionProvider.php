<?php


namespace Pledg\Bundle\PaymentBundle\Provider;


use Doctrine\Persistence\ObjectRepository;
use Oro\Bundle\PaymentBundle\Entity\PaymentTransaction;

class PaymentTransactionProvider implements PaymentTransactionProviderInterface
{
    /** @var ObjectRepository */
    private $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getByReference(string $reference): ?PaymentTransaction
    {
        /** @var PaymentTransaction $paymentTransaction */
        $paymentTransaction = $this->repository->findOneBy(['reference' => $reference]);

        return $paymentTransaction;
    }
}
