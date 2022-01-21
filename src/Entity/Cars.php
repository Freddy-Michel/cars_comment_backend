<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\CarCountController;
use App\Controller\CarsPublishController;
use App\Repository\CarsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

#[ORM\Entity(repositoryClass: CarsRepository::class)]
#[ApiResource(
    // security:'is_granted("ROLE_USER")',
    normalizationContext:[
        'openapi_definition_name' => 'Collection'
    ],
    attributes:[
        'order'=>['createdAt'=>'DESC']
    ],
    paginationItemsPerPage:10,
    collectionOperations: [
        'get',
        'post' =>[
            'openapi_context' => [
                'security' => ['cookieAuth' => []]
            ]
        ],
        'count' => [
            'method' => 'GET',
            'path' => '/cars/count',
            'controller' => CarCountController::class,
            'read' => false,
            'pagination_enabled'=>false,
            'filters' => [],
            'openapi_context' => [
                'summary' => 'Recupere les nombres des voitures',
                'parameters' => [
                    [
                        'in' => 'query',
                        'name' => 'published',
                        'schema' => [
                            'type' => 'integer',
                            'maximum' => 1,
                            'minimum' => 0
                        ],
                        'description' => 'Filtre les voitures publie'
                    ]
                ],
                'responses' => [
                    '200' => [
                        'description' => 'OK',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'integer',
                                    'example' => 2
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    itemOperations:[
        'put',
        'delete',
        'patch' => [
            'controller' => NotFoundAction::class,
            'openapi_context' => [
                'summary' => 'hidden'
            ],
            'read' => false,
            'output' => false
        ],
        'get',
        'publish' => [
            'method' => 'POST',
            'path' => '/cars/{id}/publish',
            'controller' => CarsPublishController::class,
            'openapi_context' =>[
                'summary' => "Permet de mettre en ligne une voiture",
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => []
                        ]
                    ]
                ]
            ]
        ] 
    ]
)]
#[ApiFilter(SearchFilter::class, properties:['id' => 'exact', 'name'=>'partial', 'matricule'=>'partial', 'marque'=>'partial'])]
class Cars
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:Comment'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:Comment', 'write:Comment'])]
    #[Length(min: 5)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:Comment','write:Comment'])]
    #[Length(min: 5)]
    private $matricule;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:Comment', 'write:Comment'])]
    #[Length(min: 5)]
    private $marque;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\OneToMany(mappedBy: 'cars', targetEntity: Comment::class)]
    private $comments;

    #[ORM\Column(type: 'boolean', options:['default'=> "0"])]
    #[Groups(['read:Comment', 'write:Comment'])]
    #[ApiProperty(openapiContext:['type'=>'boolean'])]
    private $published = false;

    #[ORM\Column(type: 'blob', nullable: true)]
    private $picture;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
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

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

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

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCars($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCars() === $this) {
                $comment->setCars(null);
            }
        }

        return $this;
    }

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setPicture($picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
