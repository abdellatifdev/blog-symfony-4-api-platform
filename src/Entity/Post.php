<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *    attributes={
 *          "order"={"createdAt": "DESC"},
 *          "pagination_enabled"=true,
 *          "pagination_client_items_per_page"=true,
 *    },  
 *    itemOperations={
 *         "get"={
 *             "normalization_context"={
 *                 "groups"={"post-with-author"},                
 *             }
 *          },
 *         "put"={
 *             "access_control"="object.getAuthor() == user"
 *         },
 *         "delete"={
 *             "access_control"="object.getAuthor() == user"
 *          }
 *     },
 *     collectionOperations={
 *         "get"={
 *            "normalization_context"={
 *                 "groups"={"post-with-author"},    
 *              }            
 *         },
 *         "post"={
 *             "denormalization_context"={
 *                 "groups"={"post"}
 *             },
 *             "normalization_context"={
 *                 "groups"={"get"}
 *             },
 *             
 *         }
 *     },   
 * )
 * @ApiFilter(BooleanFilter::class, properties={"isPulished"})
 * @ApiFilter(SearchFilter::class, properties={"slug": "exact","title"="partial"})
 */
class Post implements AuthoredEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get","post-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=155)
     * @Groups({"get","put","post","post-with-author"})
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"get","put","post","post-with-author"})
     * @Assert\NotBlank()
     * @Assert\Length(min=20)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get","post-with-author"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"get","post-with-author"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get","put","post","post-with-author"})
     */
    private $isPulished;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post-with-author"})
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=155,unique=true)
     * @Gedmo\Slug(fields={"title"})
     * @Groups({"get","put","post","post-with-author"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
     * @Groups({"post-with-author"})
     */
    private $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
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

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = new DateTime();

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new DateTime();

        return $this;
    }

    public function getIsPulished(): ?bool
    {
        return $this->isPulished;
    }

    public function setIsPulished(bool $isPulished): self
    {
        $this->isPulished = $isPulished;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }
}
