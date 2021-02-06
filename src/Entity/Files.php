<?php

namespace App\Entity;

use App\Repository\FilesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FilesRepository::class)
 * Assert\Callback(methods={"validate"})
 * !This is for upload:
 * @Vich\Uploadable
 */
class Files
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="datetime")
    *
    * @var \DateTimeInterface|null
    */
    private $updatedAt;

    /**
    * !This is not a mapped field of entity metadata, just a simple property.
    * NOTE:This will store the UploadedFile object after the form is submitted. 
    * NOTE:This should not be persisted to the database, but you do need to annotate it.
    *
    * @Vich\UploadableField(mapping="documents", fileNameProperty="name", size="size", mimeType="extension", originalName="path")
    * @var File|null
    * @Assert\File(
    *     maxSize = "4096k",
    *     mimeTypes = {"application/pdf", 
    *                   "application/x-pdf",
    *                   "text/*","application/epub+zip",
    *                   "application/msword",
    *                   "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    *                   "application/vnd.ms-excel",
    *                   "application/vnd.oasis.opendocument.text",
    *                   "application/rtf","image/jpeg", "image/png",
    *                   "audio/x-wav", "audio/mpeg", "audio/mp3",
    *                   "application/x-mobipocket-ebook","application/x-pilot",
    *                   "application/x-rar-compressed","application/zip"},
    *     mimeTypesMessage = "Please upload a valid document file")
    */
    private $file;

    /**
     * NOTE:This will hold the filename of the uploaded file.
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * ? maybe don't need it
     * NOTE: originalName
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * NOTE:mimetype
     * @ORM\Column(type="string", length=255)
     */
    private $extension;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Documents::class, inversedBy="files")
     */
    private $documents;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

   /*
    * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $file
    */
    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            // !It is required that at least one field changes if you are using doctrine
            // !otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDocuments(): ?Documents
    {
        return $this->documents;
    }

    public function setDocuments(?Documents $documents): self
    {
        $this->documents = $documents;

        return $this;
    }

    
    public function __toString(): string
    {
        return $this->getDocuments();
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

}
