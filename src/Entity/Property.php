<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropertyRepository")
 * @UniqueEntity("title")
 * @Vich\Uploadable
 */
class Property
{

    const HEAT = [
        0 => 'Electrique',
        1 => 'Gaz'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @var File|null
     * @Assert\Image(
     *          mimeTypes="image/jpeg")
     * @Vich\UploadableField(mapping="property_image", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @Assert\Length(min=5, max=255)
     * @Assert\Regex("/^[a-zA-Z][a-zA-Z0-9]/")
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Regex("/^[a-zA-Z][a-zA-Z0-9]/")
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=10, max=400)
     */
    private $surface;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=1, max=15)
     */
    private $rooms;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=1, max=10)
     */
    private $bedrooms;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=1, max=100)
     */
    private $floor;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(min=1500, max=900000)
     */
    private $price;

    /**
     * @ORM\Column(type="string")
     */
    private $heat;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[a-zA-Z][a-zA-Z]/")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex("/^[0-9]{5}$/")
     */
    private $postal_code;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $sold = false;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", inversedBy="properties")
     */
    private $options;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;



    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->options = new ArrayCollection();
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

    public function getSlug(): string
    {
        return (new Slugify())->slugify($this->title); // hello-world
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
    // fluent setter; retourne un objet courant
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getRooms(): ?int
    {
        return $this->rooms;
    }

    public function setRooms(int $rooms): self
    {
        $this->rooms = $rooms;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): self
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getFloor(): ?int
    {
        return $this->floor;
    }

    public function setFloor(int $floor): self
    {
        $this->floor = $floor;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getFormattedPrice(): string
    {
        return number_format($this->price, 0, '', ' ');
    }

    public function getHeat(): ?int
    {
        return $this->heat;
    }

    public function setHeat(int $heat): self
    {
        $this->heat = $heat;

        return $this;
    }

    public function getHeatType(): string 
    {
        return self::HEAT[$this->heat];
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(bool $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addProperty($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            $option->removeProperty($this);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->filename;
    }

    /**
     * @param null|string $filename
     * @return Property
     */
    public function setFileName(?string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return null|File
     */
    public function getImageFile():? File
    {
        return $this->imageFile;
    }

    /**
     * @param null|string $imageFile
     * @return Property
     */
    public function setImageFile(?File $imageFile): self
    {
        $this->imageFile = $imageFile;
        
        if ($this->imageFile instanceof UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }

        return $this;
        
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
