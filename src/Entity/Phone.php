<?php

namespace App\Entity;

use App\Repository\PhoneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PhoneRepository::class)
 */
class Phone
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Type(type="string", message="The phone number must be a string.")
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity=Contact::class, inversedBy="phones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Contact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getContact(): ?Contact
    {
        return $this->Contact;
    }

    public function setContact(?Contact $Contact): self
    {
        $this->Contact = $Contact;

        return $this;
    }
}
