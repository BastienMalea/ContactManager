<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[UniqueEntity(fields: ['name', 'firstname', 'phoneNumber'])]
#[Vich\Uploadable]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 50)]
    #[Assert\Length(min: 2, max: 50)]
    private string $name;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 2, max: 50)]
    private string $firstname;

    #[ORM\Column(length: 10)]
    #[Assert\Length(min: 10, max: 10)]
    private string $phoneNumber;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Email(
        message: 'email {value} non valide'
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'members')]
    private Collection $memberGroups;

    #[ORM\ManyToMany(targetEntity: CustomField::class, inversedBy: 'contacts', cascade: ['persist'])]
    private Collection $customFields;

    public function __construct()
    {
        $this->memberGroups = new ArrayCollection();
        $this->customFields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setimageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getMemberGroups(): Collection
    {
        return $this->memberGroups;
    }

    public function addMemberGroup(Group $memberGroup): static
    {
        if (!$this->memberGroups->contains($memberGroup)) {
            $this->memberGroups->add($memberGroup);
            $memberGroup->addMember($this);
        }

        return $this;
    }

    public function removeMemberGroup(Group $memberGroup): static
    {
        if ($this->memberGroups->removeElement($memberGroup)) {
            $memberGroup->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CustomField>
     */
    public function getCustomFields(): Collection
    {
        return $this->customFields;
    }

    public function addCustomField(CustomField $customField): static
    {
        if (!$this->customFields->contains($customField)) {
            $this->customFields->add($customField);
        }

        return $this;
    }

    public function removeCustomField(CustomField $customField): static
    {
        $this->customFields->removeElement($customField);

        return $this;
    }
}
