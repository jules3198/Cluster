<?php

namespace App\Entity;

use App\Repository\EventPictureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventPictureRepository::class)
 * @Vich\Uploadable()
 */
class EventPicture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="eventPictures")
     */
    private $event;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture_size;

    /**
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="image_name")
     * @Assert\File(
     * maxSize="1000k",
     * maxSizeMessage="Le fichier excède 1000Ko.",
     * mimeTypes={"image/png", "image/jpeg", "image/jpg", "image/svg+xml", "image/gif"},
     * mimeTypesMessage= "formats autorisés: png, jpeg, jpg, svg, gif"
     * )
     *
     * @var File|null
     */
    private $picture_file;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getPictureName(): ?string
    {
        return $this->picture_name;
    }

    public function setPictureName(string $picture_name): self
    {
        $this->picture_name = $picture_name;

        return $this;
    }

    public function getPictureSize(): ?string
    {
        return $this->picture_size;
    }

    public function setPictureSize(string $picture_size): self
    {
        $this->picture_size = $picture_size;

        return $this;
    }

    public function getPictureFile(): ?File
    {
        return $this->picture_file;
    }

    public function setPictureFile(?File $image_file = null): void
    {
        $this->image_file = $image_file;

        if (null !== $image_file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTimeImmutable();
            //$this->updatedAt = new \DateTime('now');
        }
    }
}
