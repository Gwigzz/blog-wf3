<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{

    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 100)]
    private $username;

    #[Assert\NotBlank()]
    #[Assert\Email()]
    private $email;

    #[Assert\NotBlank()]
    private $telephone;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 10, max: 1500)]
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
