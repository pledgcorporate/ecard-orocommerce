<?php

declare(strict_types=1);

namespace Pledg\Bundle\PaymentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * @ORM\Entity(repositoryClass="Pledg\Bundle\PaymentBundle\Repository\PledgSettingsRepository")
 */
class PledgSettings extends Transport
{
    /**
     * @var Collection|LocalizedFallbackValue[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue",
     *      cascade={"ALL"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(
     *      name="oro_pledg_trans_label",
     *      joinColumns={
     *          @ORM\JoinColumn(name="transport_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="localized_value_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     *      }
     * )
     */
    private $labels;

    /**
     * @var Collection|LocalizedFallbackValue[]
     *
     * @ORM\ManyToMany(
     *      targetEntity="Oro\Bundle\LocaleBundle\Entity\LocalizedFallbackValue",
     *      cascade={"ALL"},
     *      orphanRemoval=true
     * )
     * @ORM\JoinTable(
     *      name="oro_pledg_trans_short_label",
     *      joinColumns={
     *          @ORM\JoinColumn(name="transport_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="localized_value_id", referencedColumnName="id", onDelete="CASCADE", unique=true)
     *      }
     * )
     */
    private $shortLabels;

    /**
     * @var ParameterBag
     */
    private $settings;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
        $this->shortLabels = new ArrayCollection();
    }

    /**
     * @return Collection|LocalizedFallbackValue[]
     */
    public function getLabels(): Collection
    {
        return $this->labels;
    }

    public function addLabel(LocalizedFallbackValue $label): self
    {
        if (!$this->labels->contains($label)) {
            $this->labels->add($label);
        }

        return $this;
    }

    public function removeLabel(LocalizedFallbackValue $label): self
    {
        if ($this->labels->contains($label)) {
            $this->labels->removeElement($label);
        }

        return $this;
    }

    /**
     * @return Collection|LocalizedFallbackValue[]
     */
    public function getShortLabels(): Collection
    {
        return $this->shortLabels;
    }

    public function addShortLabel(LocalizedFallbackValue $label): self
    {
        if (!$this->shortLabels->contains($label)) {
            $this->shortLabels->add($label);
        }

        return $this;
    }

    public function removeShortLabel(LocalizedFallbackValue $label): self
    {
        if ($this->shortLabels->contains($label)) {
            $this->shortLabels->removeElement($label);
        }

        return $this;
    }

    public function getSettingsBag(): ParameterBag
    {
        if (null === $this->settings) {
            $this->settings = new ParameterBag(
                [
                    'labels' => $this->getLabels()->toArray(),
                ]
            );
        }

        return $this->settings;
    }
}
