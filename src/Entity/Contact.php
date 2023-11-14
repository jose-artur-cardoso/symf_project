<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
// use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Annotation\SerializedName;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 * @Assert\Cascade
 * @UniqueEntity("email")
 * @ApiResource(
 *      collectionOperations={
 *          "get"
 *      },
 *      itemOperations={
 *          "get"
 *      },
 *      attributes={
 *          "normalization_context"={"groups"={"contacts_read"}},
 *      }
 * )
 */

class Contact
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("contacts_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Groups("contacts_read")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank
     * @Groups("contacts_read") 
     */
    private $email;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Assert\Type("\DateTime")
     * @Groups("contacts_read")
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity=Phone::class, mappedBy="contact", cascade={"persist"}, orphanRemoval=true)
     * @Groups("contacts_read")
     */
    private $phones;    
    
    /**
     * @ORM\Column(type="array")
     * @Groups("contacts_read")
     */
    private $phoneList;

    public function __construct()
    {
        $this->phones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhoneList(): ?array
    {
        return $this->phoneList;
    }

    public function setPhoneList(array $phoneList): self
    {
        $this->phoneList = $phoneList;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    /**
     * @SerializedName("birthday")
     * @Groups({"contacts_read"})
     */
    public function getFormattedBirthday(): string
    {
        return $this->birthday->format('Y-m-d');
    }

    public function daysToBirthday(): int
    {
        $today = new \DateTime();
        
        $birthday = $this->getBirthday();

        // Change birthday to current year
        $birthdayOnCurrentYear = $birthday->setDate(date('Y'), $birthday->format('m'), $birthday->format('d'));      
        
        // If birthday has already passed in the current year, add +1 year to the comparision. 

        if($today > $birthdayOnCurrentYear)
        {
            $birthdayOnCurrentYear->modify('+1 year');
        }
        
        $timeToBirthday = $today->diff($birthdayOnCurrentYear);
        return $timeToBirthday->days;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection<int, Phone>
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
            $phone->setContact($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phones->removeElement($phone)) {
            // set the owning side to null (unless already changed)
            if ($phone->getContact() === $this) {
                $phone->setContact(null);
            }
        }

        return $this;
    }
}
