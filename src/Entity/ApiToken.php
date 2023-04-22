<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Repository\ApiTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ApiTokenRepository::class)]
#[ApiResource(
    operations:[
        new Post()
    ],
    normalizationContext:[
        'groups' => ['apiToken:read']
    ],
    denormalizationContext: [
    'groups' => ['apiToken:write']
],
)]
class ApiToken
{
    public function __construct(User $user){
        $this->token = bin2hex(random_bytes(60));
        $this->owner = $user;
        $this->expiresAt = new \DateTimeImmutable('+1 hour');
    }
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['apiToken:read'])]
    private ?string $token = null;

    #[ORM\Column]
    #[Groups(['apiToken:read'])]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\ManyToOne(inversedBy: 'apiTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }
}
