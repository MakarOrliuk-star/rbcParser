<?php

namespace App\Entity;

use App\Repository\RbcNewsRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Exception;

/**
 * @ORM\Entity(repositoryClass=RbcNewsRepository::class)
 */
class RbcNews
{
    private const IMAGES_DIR = 'images';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $original_url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $original_image_url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $timestamp;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $updated_at;

    public function __construct(
        string $original_url,
        string $title,
        string $content,
        DateTimeImmutable $timestamp)
    {
        $this->original_url = $original_url;
        $this->title = $title;
        $this->content = $content;
        $this->timestamp = $timestamp;
    }

    public function setImageOptions(?string $url, ?string $title)
    {
        $this->original_image_url = $url;
        $this->imageTitle = $title;
        if (null !== $url) {
            $imagePathInfo = pathinfo($url);

            $localImagePath = sprintf('%s/%s/%s.%s',
                $_SERVER['DOCUMENT_ROOT'],
                self::IMAGES_DIR,
                $imagePathInfo['filename'],
                $imagePathInfo['extension']);
            try {
                $this->saveImage($url, $localImagePath);
            } catch (Exception $exp) {
                return $exp->getMessage();
            }
            $this->imageUrl = sprintf('/%s/%s.%s',
                self::IMAGES_DIR,
                $imagePathInfo['filename'],
                $imagePathInfo['extension']);
        }
        return true;
    }

    private function saveImage(string $url, string $path): void
    {
        if (!file_exists($path)) {
            file_put_contents($path, file_get_contents($url));
        }
    }

    public function update(
        string $title,
        string $content,
        DateTimeImmutable $timestamp,
        ?string $original_image_url,
        ?string $imageTitle): self
    {
        $this->title = $title;
        $this->content = $content;
        $this->timestamp = $timestamp;
        $this->setImageOptions($original_image_url, $imageTitle);
        $this->updated_at = new DateTimeImmutable();

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalUrl(): ?string
    {
        return $this->original_url;
    }

    public function setOriginalUrl(string $original_url): self
    {
        $this->original_url = $original_url;

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

    public function getOriginalImageUrl(): ?string
    {
        return $this->original_image_url;
    }

    public function setOriginalImageUrl(string $original_image_url): self
    {
        $this->original_image_url = $original_image_url;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->image_url;
    }

    public function setImageUrl(string $image_url): self
    {
        $this->image_url = $image_url;

        return $this;
    }

    public function getImageTitle(): ?string
    {
        return $this->image_title;
    }

    public function setImageTitle(string $image_title): self
    {
        $this->image_title = $image_title;

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

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    public function setTimestamp(string $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
