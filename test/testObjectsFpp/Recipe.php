<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Test\TestObjectsFpp;


use UnexpectedValueException;

final class Recipe implements ValueObject
{
    private string $title;
    private ?int $minutes_to_prepare;
    private float $rating;
    private DietStyle $diet_style;

    public function __construct (
        string $title,
        ?int $minutes_to_prepare,
        float $rating,
        DietStyle $diet_style
    ) {
        $this->title = $title;
        $this->minutes_to_prepare = $minutes_to_prepare;
        $this->rating = $rating;
        $this->diet_style = $diet_style;
    }
    
    public function title(): string 
    {
        return $this->title;
    }
    
    public function minutes_to_prepare(): ?int 
    {
        return $this->minutes_to_prepare;
    }
    
    public function rating(): float 
    {
        return $this->rating;
    }
    
    public function diet_style(): DietStyle 
    {
        return $this->diet_style;
    }
    
    public function with_title(string $title): self 
    {
        return new self(
            $title,
            $this->minutes_to_prepare,
            $this->rating,
            $this->diet_style
        );
    }
    
    public function with_minutes_to_prepare(?int $minutes_to_prepare): self 
    {
        return new self(
            $this->title,
            $minutes_to_prepare,
            $this->rating,
            $this->diet_style
        );
    }
    
    public function with_rating(float $rating): self 
    {
        return new self(
            $this->title,
            $this->minutes_to_prepare,
            $rating,
            $this->diet_style
        );
    }
    
    public function with_diet_style(DietStyle $diet_style): self 
    {
        return new self(
            $this->title,
            $this->minutes_to_prepare,
            $this->rating,
            $diet_style
        );
    }
    
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'minutes_to_prepare' => $this->minutes_to_prepare,
            'rating' => $this->rating,
            'diet_style' =>  $this->valueToArray($this->diet_style),
        ];
    }
    
    public static function fromArray(array $array): self
    {
        if (!array_key_exists('title', $array)) {
            throw new UnexpectedValueException('Array key title does not exist');
        }
        
        if (!array_key_exists('minutes_to_prepare', $array)) {
            throw new UnexpectedValueException('Array key minutes_to_prepare does not exist');
        }
        
        if (!array_key_exists('rating', $array)) {
            throw new UnexpectedValueException('Array key rating does not exist');
        }
        
        if (!array_key_exists('diet_style', $array)) {
            throw new UnexpectedValueException('Array key diet_style does not exist');
        }
        
        if (is_string($array['diet_style']) && is_a(DietStyle::class, Enum::class, true)) {
            $array['diet_style'] = DietStyle::fromName($array['diet_style']);
        }
    
        if (is_array($array['diet_style']) && (is_a(DietStyle::class, Set::class, true) || is_a(DietStyle::class, ValueObject::class, true))) {
            $array['diet_style'] = DietStyle::fromArray($array['diet_style']);
        }

        return new self(
            $array['title'],
            $array['minutes_to_prepare'],
            $array['rating'],
            $array['diet_style']
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