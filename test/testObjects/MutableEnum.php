<?php

declare(strict_types=1);

namespace Test\TestObjects;

final class MutableEnum
{

	public const OPTIONS = [ "OMNIVORE" => "Omnivore", "VEGETARIAN" => "Vegetarian", "VEGAN" => "Vegan",];

	public const OMNIVORE = 'Omnivore';
	public const VEGETARIAN = 'Vegetarian';
	public const VEGAN = 'Vegan';
        
    private string $name;
    private string $value;
        
    private function __construct(string $name)
    {
        $this->name = $name;
        $this->value = self::OPTIONS[$name];
    }


	public static function OMNIVORE(): self
	{
		return new self('OMNIVORE');
	}

	public static function VEGETARIAN(): self
	{
		return new self('VEGETARIAN');
	}

	public static function VEGAN(): self
	{
		return new self('VEGAN');
	}


    public static function fromValue(string $input_value): self
    {
        foreach (self::OPTIONS as $key => $value) {
            if ($input_value === $value) {
                return new self($key);
            }
        }

        throw new \InvalidArgumentException("Unknown enum value '$input_value' given");
    }
    
    public static function fromName(string $name): self
    {
        if(!array_key_exists($name, self::OPTIONS)){
             throw new \InvalidArgumentException("Unknown enum name $name given");
        }
        
        return new self($name);
    }

    public function equals(?self $other): bool
    {
        return (null !== $other) && ($this->name() === $other->name());
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function toString(): string
    {
        return $this->name;
    }

}