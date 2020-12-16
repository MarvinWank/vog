<?php

declare(strict_types=1);

namespace Test\TestObjects;

final class RecipeCollection
{
	private RecipeIntStringValue $recipe1;
	private RecipeEnumStringValue $recipe2;

	public function __construct (
		RecipeIntStringValue $recipe1,
		RecipeEnumStringValue $recipe2
	)
	{
		$this->recipe1 = $recipe1;
		$this->recipe2 = $recipe2;
	}

	public function recipe1(): RecipeIntStringValue {
		return $this->recipe1;
	}

	public function recipe2(): RecipeEnumStringValue {
		return $this->recipe2;
	}


	public function with_recipe1 (RecipeIntStringValue $recipe1):self
	{
		return new self($recipe1,$this->recipe2,);
	}

	public function with_recipe2 (RecipeEnumStringValue $recipe2):self
	{
		return new self($this->recipe1,$recipe2,);
	}
	public function toArray(): array
	{
		 return [
			 'recipe1' =>  $this->value_to_array($this->recipe1), 
			 'recipe2' =>  $this->value_to_array($this->recipe2), 
		];
	}

	public static function fromArray(array $array): self
	{
		if(!array_key_exists('recipe1', $array)){
			 throw new \UnexpectedValueException('Array key recipe1 does not exist');
		}
		if(!array_key_exists('recipe2', $array)){
			 throw new \UnexpectedValueException('Array key recipe2 does not exist');
		}

		return new self($array['recipe1'],$array['recipe2'],);
	}

	private function value_to_array($value)
	{
		if(method_exists($value, 'toArray')) {
			return $value->toArray();
		}
		return strval($value);
	}

	public function __toString(): string
	{
		return strval($this->recipe1);
	}

	public function toString(): string
	{
		return strval($this->recipe1);
	}
}