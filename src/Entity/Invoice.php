<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $transactionDate = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $billingAddress = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $billingCity = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $billingPostalCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTransactionDate(): ?\DateTimeInterface
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(\DateTimeInterface $transactionDate): static
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?string $billingAddress): static
    {
        if ($billingAddress === null) {
            // Si l'adresse de facturation est nulle, récupérer la valeur depuis la session
            $sessionBillingData = $_SESSION['billing_address'] ?? null;
            $this->billingAddress = $sessionBillingData['address'] ?? null;
        } else {
            $this->billingAddress = $billingAddress;
        }

        return $this;
    }

    public function getBillingCity(): ?string
    {
        return $this->billingCity;
    }

    public function setBillingCity(?string $billingCity): static
    {
        if ($billingCity === null) {
            // Si la ville de facturation est nulle, récupérer la valeur depuis la session
            $sessionBillingData = $_SESSION['billing_address'] ?? null;
            $this->billingCity = $sessionBillingData['city'] ?? null;
        } else {
            $this->billingCity = $billingCity;
        }

        return $this;
    }

    public function getBillingPostalCode(): ?string
    {
        return $this->billingPostalCode;
    }

    public function setBillingPostalCode(?string $billingPostalCode): static
    {
        if ($billingPostalCode === null) {
            // Si le code postal de facturation est nul, récupérer la valeur depuis la session
            $sessionBillingData = $_SESSION['billing_address'] ?? null;
            $this->billingPostalCode = $sessionBillingData['postal_code'] ?? null;
        } else {
            $this->billingPostalCode = $billingPostalCode;
        }

        return $this;
    }
}