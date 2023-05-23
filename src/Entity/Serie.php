<?php

namespace App\Entity;

use App\Repository\SerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SerieRepository::class)]
class Serie
{
    const MAX_RESULT=48;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("serie_data")]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Serie's name is mandatory")]
    #[Assert\Length(
        min : 1,
        max : 255,
        minMessage :  "Minimum {{ limit }} character",
        maxMessage : "Maximum {{ limit }} characters"
    )]
    #[ORM\Column(length: 255)]
    #[Groups("serie_data")]
    private ?string $name = null;

    #[Assert\Length(
        min : 10,
        max : 4000,
        minMessage :  "Minimum {{ limit }} character",
        maxMessage : "Maximum {{ limit }} characters"
    )]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups("serie_data")]
    private ?string $overview = null;

    #[Assert\NotBlank(message: "Serie's status is mandatory")]
    #[Assert\Choice(choices: ["canceled","ended","returning"],message : "Value not allowed")]
    #[ORM\Column(length: 50)]
    #[Groups("serie_data")]
    private ?string $status = null;

    #[Assert\Range(notInRangeMessage: "Not in range", min: 0, max: 10)]
    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 1)]
    #[Groups("serie_data")]
    private ?string $vote = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 6, scale: 2)]
    #[Groups("serie_data")]
    private ?string $popularity = null;

    #[Assert\Choice(choices: ["Drama","Comedy","Adventure","Sci-Fi"],message : "Value not allowed")]
    #[ORM\Column(length: 255)]
    #[Groups("serie_data")]
    private ?string $genres = null;

    #[Assert\LessThan(propertyPath: "lastAirDate")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("serie_data")]
    private ?\DateTimeInterface $firstAirDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("serie_data")]
    private ?\DateTimeInterface $lastAirDate = null;

    #[ORM\Column(length: 255)]
    #[Groups("serie_data")]
    private ?string $backdrop = null;

    #[ORM\Column(length: 255)]
    #[Groups("serie_data")]
    private ?string $poster = null;

    #[ORM\Column]
    #[Groups("serie_data")]
    private ?int $tmdbId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups("serie_data")]
    private ?\DateTimeInterface $dateCreated = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups("serie_data")]
    private ?\DateTimeInterface $dateModified = null;

    #[ORM\OneToMany(mappedBy: 'serie', targetEntity: Season::class,cascade: ['remove'])]
    #[Groups("serie_data")]
    private Collection $seasons;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
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

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getVote(): ?string
    {
        return $this->vote;
    }

    public function setVote(string $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getPopularity(): ?string
    {
        return $this->popularity;
    }

    public function setPopularity(string $popularity): self
    {
        $this->popularity = $popularity;

        return $this;
    }

    public function getGenres(): ?string
    {
        return $this->genres;
    }

    public function setGenres(string $genres): self
    {
        $this->genres = $genres;

        return $this;
    }

    public function getFirstAirDate(): ?\DateTimeInterface
    {
        return $this->firstAirDate;
    }

    public function setFirstAirDate(\DateTimeInterface $firstAirDate): self
    {
        $this->firstAirDate = $firstAirDate;

        return $this;
    }

    public function getLastAirDate(): ?\DateTimeInterface
    {
        return $this->lastAirDate;
    }

    public function setLastAirDate(\DateTimeInterface $lastAirDate): self
    {
        $this->lastAirDate = $lastAirDate;

        return $this;
    }

    public function getBackdrop(): ?string
    {
        return $this->backdrop;
    }

    public function setBackdrop(string $backdrop): self
    {
        $this->backdrop = $backdrop;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getTmdbId(): ?int
    {
        return $this->tmdbId;
    }

    public function setTmdbId(int $tmdbId): self
    {
        $this->tmdbId = $tmdbId;

        return $this;
    }

    public function getDateCreated(): ?\DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): self
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    public function getDateModified(): ?\DateTimeInterface
    {
        return $this->dateModified;
    }

    public function setDateModified(?\DateTimeInterface $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons->add($season);
            $season->setSerie($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getSerie() === $this) {
                $season->setSerie(null);
            }
        }

        return $this;
    }


}
