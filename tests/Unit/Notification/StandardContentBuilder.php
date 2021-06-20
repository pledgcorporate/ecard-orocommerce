<?php

declare(strict_types=1);

namespace Tests\Pledg\Bundle\PaymentBundle\Unit\Notification;

class StandardContentBuilder
{
    /** @var string */
    protected $createAt;

    /** @var string */
    protected $id;

    /** @var array */
    protected $additionalData;

    /** @var array */
    protected $metadata;

    /** @var string */
    protected $status;

    /** @var bool */
    protected $sandbox;

    /** @var string */
    protected $error;

    /** @var string */
    protected $reference;

    /** @var string */
    protected $signature;

    public function __construct()
    {
        $this->createdAt = '2019-04-04T12:20:34.97138Z';
        $this->id = 'test-valid';
        $this->additionalData = ['xx' => 'yy'];
        $this->metadata = ['foo' => 'bar'];
        $this->status = 'completed';
        $this->sandbox = true;
        $this->error = '';
        $this->reference = 'PLEDG_108698_6786391';
        $this->signature = '6E9728899AA20E5767015165177B304ECDA4E48E79918E21D26C905E575B1EEB';
    }

    public function withReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function withError(string $error): self
    {
        $this->error = $error;

        return $this;
    }

    public function withSignature(string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function build(): array
    {
        return [
            'created_at' => $this->createdAt,
            'id' => $this->id,
            'additional_data' => $this->additionalData,
            'metadata' => $this->metadata,
            'status' => $this->status,
            'sandbox' => $this->sandbox,
            'error' => $this->error,
            'reference' => $this->reference,
            'signature' => $this->signature,
        ];
    }
}
