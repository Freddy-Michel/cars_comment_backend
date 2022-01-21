<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource(
    security:'is_granted("ROLE_USER")',
    normalizationContext: [
        'groups' => ['read:collection'],
        'openapi_definition_name' => 'Collection'
    ],
    denormalizationContext: ['groups' => ['write:Comment']],
    paginationItemsPerPage: 2,
    collectionOperations: [
        'get' =>[
            'openapi_context' => [
                'security' => ['cookieAuth' => []]
            ]
        ],
        'post' => [
            'openapi_context' => [
                'security' => ['cookieAuth' => []]
            ]
        ]
    ],
    itemOperations:[
        'put',
        'delete',
        'get' => [
            'normalization_context' => [
                'groups' => ['read:collection', 'read:item', 'read:Comment'],
                'openapi_definition_name' => 'Detail'
            ]
        ] 
    ]
)]
#[ApiFilter(SearchFilter::class, properties:['id'=>'exact'])]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:collection'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:collection', 'write:Comment'])]
    #[Length(min: 5)]
    private $title;

    #[ORM\Column(type: 'text')]
    #[Groups(['read:collection', 'read:item', 'write:Comment'])]
    #[Length(min: 5)]
    private $content;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read:item'])]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: Cars::class, inversedBy: 'comments', cascade:['persist'])]
    #[Groups(['read:item', 'write:Comment'])]
    #[Valid()]
    private $cars;

    public function __construct(){
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCars(): ?Cars
    {
        return $this->cars;
    }

    public function setCars(?Cars $cars): self
    {
        $this->cars = $cars;

        return $this;
    }
}
