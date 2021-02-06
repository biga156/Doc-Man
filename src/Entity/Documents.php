<?php

namespace App\Entity;

use App\Repository\DocumentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentsRepository::class)
 * Vich\Uploadable
 */
class Documents
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="boolean")
     */
    private $protection;

    /**
     * @ORM\Column(type="date")
     */
    private $validity;

    /**
     * @ORM\Column(type="datetime")
     */
    private $alarm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * Vich\UploadableField(mapping="documents_audionote")
     */
    private $audioNote;

    /**
     * @ORM\ManyToMany(targetEntity=Tags::class, inversedBy="documents")
     */
    private $tags;

    /**
     * @ORM\OneToMany(targetEntity=Files::class, mappedBy="documents", orphanRemoval=true, cascade={"persist"})
     */
    private $files; 

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="documents")
     */
    private $user;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getProtection(): ?bool
    {
        return $this->protection;
    }

    public function setProtection(bool $protection): self
    {
        $this->protection = $protection;

        return $this;
    }

    public function getValidity(): ?\DateTimeInterface
    {
        return $this->validity;
    }

    public function setValidity(\DateTimeInterface $validity): self
    {
        $this->validity = $validity;

        return $this;
    }

    public function getAlarm(): ?\DateTimeInterface
    {
        return $this->alarm;
    }

    public function setAlarm(\DateTimeInterface $alarm): self
    {
        $this->alarm = $alarm;

        return $this;
    }

    public function getAudioNote(): ?string
    {
        return $this->audioNote;
    }

    public function setAudioNote(?string $audioNote): self
    {
        $this->audioNote = $audioNote;

        return $this;
    }

    /**
     * @return Collection|Tags[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tags $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection|Files[]
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(Files $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files[] = $file;
            $file->setDocuments($this);
        } 

        return $this;
    }

    public function removeFile(Files $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getDocuments() === $this) {
                $file->setDocuments(null);
            } 
        }

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }
}
