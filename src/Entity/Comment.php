<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *    subresourceOperations={
 *          "api_posts_comments_get_subresource"={
 *                 "normalization_context"={
 *                      "groups"={"get-author-with-comment"}
 *             }
 *         },
 *    },
 *    attributes={
 *          "order"={"createdAt": "DESC"},
 *    },
 *     collectionOperations={
 *         "post"={
 *             "normalization_context"={
 *                 "groups"={"get"}
 *             },             
 *         },
 *     }, 
 *     itemOperations={
 *         "get",
 *         "put"={
 *             "access_control"="object.getAuthor() == user"
 *         },
 *         "delete"={
 *             "access_control"="object.getAuthor() == user"
 *          }
 *     }
 * )
 */
class Comment implements AuthoredEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"get-author-with-comment"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Groups({"post"})
     * @Groups({"get-author-with-comment"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get-author-with-comment"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @Groups({"get-author-with-comment"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @Groups({"post"})
     */
    private $post;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
