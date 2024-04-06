<?php

namespace App\Entity\Lead;

use App\Repository\Lead\DealEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use OpenApi\Attributes as OAT;

#[ORM\Entity(repositoryClass: DealEntityRepository::class)]
class Deal
{
    #[Groups(['administrator'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['administrator'])]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?DealContacts $contacts = null;

    #[Groups(['administrator'])]
    #[ORM\OneToMany(mappedBy: 'deal', targetEntity: DealField::class, cascade: ['persist', 'remove'])]
    private Collection $fields;

    #[Groups(['administrator'])]
    #[ORM\OneToOne(cascade: ['persist', 'remove',], fetch: "EAGER")]
    #[OAT\Property(type: 'jsonType')]
    private ?DealOrder $order = null;

    #[ORM\Column]
    private ?int $projectId = null;

    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContacts(): ?DealContacts
    {
        return $this->contacts;
    }

    public function setContacts(?DealContacts $contacts): static
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function getOrder(): ?DealOrder
    {
        return $this->order;
    }

    public function setOrder(?DealOrder $order): static
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return Collection<int, DealField>
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(DealField $field): static
    {
        $this->fields[] = $field;

        return $this;
    }

    public function removeField(DealField $field): static
    {
        if ($this->fields->removeElement($field)) {
            // set the owning side to null (unless already changed)
            if ($field->getDeal() === $this) {
                $field->setDeal(null);
            }
        }

        return $this;
    }

    public function getProjectId(): ?int
    {
        return $this->projectId;
    }

    public function setProjectId(int $projectId): static
    {
        $this->projectId = $projectId;

        return $this;
    }
}
