<?php

declare(strict_types=1);

namespace Test\TestObjects;

final class ChildOfNotFinal extends NotFinal
{
	private string $foo;

	public function __construct (
		string $foo
	)
	{
		$this->foo = $foo;
	}

	public function foo(): string {
		return $this->foo;
	}


	public function with_foo (string $foo):self
	{
		return new self($foo,);
	}
	public function toArray(): array
	{
		 return [
			 'foo' => $this->foo, 
		];
	}

	public static function fromArray(array $array): self
	{
		if(!array_key_exists('foo', $array)){
			 throw new \UnexpectedValueException('Array key foo does not exist');
		}

		return new self($array['foo'],);
	}

	private function value_to_array($value)
	{
		if(method_exists($value, 'toArray')) {
			return $value->toArray();
		}
		return strval($value);
	}
}