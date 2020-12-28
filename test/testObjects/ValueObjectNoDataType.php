<?php

declare(strict_types=1);

namespace Test\TestObjects;

final class ValueObjectNoDataType
{
	private  $property;

	public function __construct (
		 $property
	)
	{
		$this->property = $property;
	}

	public function property() {
		return $this->property;
	}


	public function with_property ( $property):self
	{
		return new self($property,);
	}
	public function toArray(): array
	{
		 return [
			 'property' =>  $this->value_to_array($this->property), 
		];
	}

	public static function fromArray(array $array): self
	{
		if(!array_key_exists('property', $array)){
			 throw new \UnexpectedValueException('Array key property does not exist');
		}

		return new self($array['property'],);
	}

	private function value_to_array($value)
	{
		if(method_exists($value, 'toArray')) {
			return $value->toArray();
		}
		return strval($value);
	}
}