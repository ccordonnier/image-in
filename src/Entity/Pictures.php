<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PicturesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=PicturesRepository::class)
 */
class Pictures
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="integer")
     */
    private $likes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tag;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Pictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     * 
     */
    private $user_id;

    /**
     * @ORM\OneToMany(targetEntity=PictureLike::class, mappedBy="picture")
     */
    private $pictureLikes;

    public function __construct()
    {
        $this->pictureLikes = new ArrayCollection();
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|PictureLike[]
     */
    public function getPictureLikes(): Collection
    {
        return $this->pictureLikes;
    }

    public function addPictureLike(PictureLike $pictureLike): self
    {
        if (!$this->pictureLikes->contains($pictureLike)) {
            $this->pictureLikes[] = $pictureLike;
            $pictureLike->setPicture($this);
        }

        return $this;
    }

    public function removePictureLike(PictureLike $pictureLike): self
    {
        if ($this->pictureLikes->contains($pictureLike)) {
            $this->pictureLikes->removeElement($pictureLike);
            // set the owning side to null (unless already changed)
            if ($pictureLike->getPicture() === $this) {
                $pictureLike->setPicture(null);
            }
        }

        return $this;
    }

    /**
     * Permet de vérifier si un user à liker une image
     */
    public function isLikedByUser(User $user)
    {
        //dump($this->pictureLikes);
        //return $this->pictureLikes;
        foreach ($this->pictureLikes as $like) {

            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }
}
