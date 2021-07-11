<?php


namespace Pledg\Bundle\PaymentBundle\Notification\Validator;


class CompositeValidator implements ValidatorInterface
{
    /** @var iterable|ValidatorInterface[] */
    protected $validators;

    public function __construct(iterable $validators)
    {
        $this->validators = $validators;
    }

    public function validate(array $content): bool
    {
        foreach ($this->validators as $validator) {
            try {
                return $validator->validate($content);
            } catch (NotSupportedException $e) {}
        }

        throw NotSupportedException::fromContent($content);
    }
}
