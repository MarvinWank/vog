<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Test\TestObjects;


use UnexpectedValueException;

final class RecipeWithDate implements ValueObject
{
    private string $title;
    private ?int $minutesToPrepare;
    private float $rating;
    private \DateTimeImmutable $creationDate;

    public function __construct (
        string $title,
        ?int $minutesToPrepare,
        float $rating,
        \DateTimeImmutable $creationDate
    ) {
        $this->title = $title;
        $this->minutesToPrepare = $minutesToPrepare;
        $this->rating = $rating;
        $this->creationDate = $creationDate;
    }
    
    public function getTitle(): string 
    {
        return $this->title;
    }
    
    public function getMinutesToPrepare(): ?int 
    {
        return $this->minutesToPrepare;
    }
    
    public function getRating(): float 
    {
        return $this->rating;
    }
    
    public function getCreationDate(): \DateTimeImmutable 
    {
        return $this->creationDate;
    }
    
    public function withTitle(string $title): self 
    {
        return new self(
            $title,
            $this->minutesToPrepare,
            $this->rating,
            $this->creationDate
        );
    }
    
    public function withMinutesToPrepare(?int $minutesToPrepare): self 
    {
        return new self(
            $this->title,
            $minutesToPrepare,
            $this->rating,
            $this->creationDate
        );
    }
    
    public function withRating(float $rating): self 
    {
        return new self(
            $this->title,
            $this->minutesToPrepare,
            $rating,
            $this->creationDate
        );
    }
    
    public function withCreationDate(\DateTimeImmutable $creationDate): self 
    {
        return new self(
            $this->title,
            $this->minutesToPrepare,
            $this->rating,
            $creationDate
        );
    }
    
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'minutesToPrepare' => $this->minutesToPrepare,
            'rating' => $this->rating,
            'creationDate' =>  $this->valueToArray($this->creationDate),
        ];
    }
    
    public static function fromArray(array $array): self
    {
        if (!array_key_exists('title', $array)) {
            throw new UnexpectedValueException('Array key title does not exist');
        }
        
        if (!array_key_exists('minutesToPrepare', $array)) {
            throw new UnexpectedValueException('Array key minutesToPrepare does not exist');
        }
        
        if (!array_key_exists('rating', $array)) {
            throw new UnexpectedValueException('Array key rating does not exist');
        }
        
        if (!array_key_exists('creationDate', $array)) {
            throw new UnexpectedValueException('Array key creationDate does not exist');
        }
        
        if (is_string($array['creationDate']) && is_a(\DateTimeImmutable::class, Enum::class, true)) {
            $array['creationDate'] = \DateTimeImmutable::fromName($array['creationDate']);
        }
    
        if (is_array($array['creationDate']) && (is_a(\DateTimeImmutable::class, Set::class, true) || is_a(\DateTimeImmutable::class, ValueObject::class, true))) {
            $array['creationDate'] = \DateTimeImmutable::fromArray($array['creationDate']);
        }

        return new self(
            $array['title'],
            $array['minutesToPrepare'],
            $array['rating'],
            $array['creationDate']
        );
    }
        
    private function valueToArray($value)
    {
        if (method_exists($value, 'toArray')) {
            return $value->toArray();
        }
        
        return (string) $value;
    }
        
    public function equals($value): bool
    {
        $ref = $this->toArray();
        $val = $value->toArray();
        
        return ($ref === $val);
    }
    
    public function __toString(): string
    {
        return $this->toString();
    }
    
    public function toString(): string
    {
        return (string) $this->title;
    }
    
}