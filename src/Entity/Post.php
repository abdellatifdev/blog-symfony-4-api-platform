<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *      normalizationContext={
 *          "groups"={"get_posts"}
 *     }
 * )
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get_posts"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=155)
     * @Groups({"get_posts"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups({"get_posts"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get_posts"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"get_posts"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"get_posts"})
     */
    private $isPulished = true;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_posts"})
     */
    private $author;

    /**
     * @ORM\Column(type="string", length=155)
     * @Groups({"get_posts"})
     */
    private $slug;

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

    public function setAuthor(?User $author): self
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
}
