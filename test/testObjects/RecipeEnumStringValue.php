<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Test\TestObjects;


use UnexpectedValueException;

final class RecipeEnumStringValue implements ValueObject
{
    private string $title;
    private ?int $minutesToPrepare;
    private float $rating;
    private DietStyle $dietStyle;

    public function __construct (
        string $title,
        ?int $minutesToPrepare,
        float $rating,
        DietStyle $dietStyle
    ) {
        $this->title = $title;
        $this->minutesToPrepare = $minutesToPrepare;
        $this->rating = $rating;
        $this->dietStyle = $dietStyle;
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
    
    public function getDietStyle(): DietStyle 
    {
        return $this->dietStyle;
    }
    
    public function withTitle(string $title): self 
    {
        return new self(
            $title,
            $this->minutesToPrepare,
            $this->rating,
            $this->dietStyle
        );
    }
    
    public function withMinutesToPrepare(?int $minutesToPrepare): self 
    {
        return new self(
            $this->title,
            $minutesToPrepare,
            $this->rating,
            $this->dietStyle
        );
    }
    
    public function withRating(float $rating): self 
    {
        return new self(
            $this->title,
            $this->minutesToPrepare,
            $rating,
            $this->dietStyle
        );
    }
    
    public function withDietStyle(DietStyle $dietStyle): self 
    {
        return new self(
            $this->title,
            $this->minutesToPrepare,
            $this->rating,
            $dietStyle
        );
    }
    
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'minutesToPrepare' => $this->minutesToPrepare,
            'rating' => $this->rating,
            'dietStyle' =>  $this->valueToArray($this->dietStyle),
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
        
        if (!array_key_exists('dietStyle', $array)) {
            throw new UnexpectedValueException('Array key dietStyle does not exist');
        }
                if (is_string($array['dietStyle']) && is_a(DietStyle::class, Enum::class, true)) {
            $array['dietStyle'] = DietStyle::fromName($array['dietStyle']);
        }
    
        if (is_array($array['dietStyle']) && (is_a(DietStyle::class, Set::class, true) || is_a(DietStyle::class, ValueObject::class, true))) {
            $array['dietStyle'] = DietStyle::fromArray($array['dietStyle']);
        }

        return new self(
            $array['title'],
            $array['minutesToPrepare'],
            $array['rating'],
            $array['dietStyle']
        );
    }
        
    private function valueToArray($value)
    {
        if (method_exists($value, 'toArray')) {
            return $value->toArray();
        }
        
        if(is_a($value, \DateTime::class, true) || is_a($value, \DateTimeImmutable::class, true)){
            return $value->format('Y-m-d');
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
        return (string) $this->dietStyle;
    }
    
}