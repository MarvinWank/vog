<?php

declare(strict_types=1);

namespace Test\TestObjects;

final class Recipe
{
	private string $title;
	private ?int $minutes_to_prepare;
	private float $rating;
	private  $any_value;

	public function __construct (
		string $title,
		?int $minutes_to_prepare,
		float $rating,
		 $any_value
	)
	{
		$this->title = $title;
		$this->minutes_to_prepare = $minutes_to_prepare;
		$this->rating = $rating;
		$this->any_value = $any_value;
	}
	public function title(): string {
		return $this->title;
	}

	public function minutes_to_prepare(): ?int {
		return $this->minutes_to_prepare;
	}

	public function rating(): float {
		return $this->rating;
	}

	public function any_value() {
		return $this->any_value;
	}


	public function with_title (string $title):self
	{
		return new self($title,$this->minutes_to_prepare,$this->rating,$this->any_value,);
	}

	public function with_minutes_to_prepare (?int $minutes_to_prepare):self
	{
		return new self($this->title,$minutes_to_prepare,$this->rating,$this->any_value,);
	}

	public function with_rating (float $rating):self
	{
		return new self($this->title,$this->minutes_to_prepare,$rating,$this->any_value,);
	}

	public function with_any_value ( $any_value):self
	{
		return new self($this->title,$this->minutes_to_prepare,$this->rating,$any_value,);
	}
	public function toArray(): array
	{
		 return [
			 'title' => $this->title, 
			 'minutes_to_prepare' => $this->minutes_to_prepare, 
			 'rating' => $this->rating, 
			 'any_value' => $this->any_value, 
		];
	}

	public function fromArray(array $array): self
	{
		if(!array_key_exists('title', $array)){
			 throw new \UnexpectedValueException('Array key title does not exist');
		}
		if(!array_key_exists('minutes_to_prepare', $array)){
			 throw new \UnexpectedValueException('Array key minutes_to_prepare does not exist');
		}
		if(!array_key_exists('rating', $array)){
			 throw new \UnexpectedValueException('Array key rating does not exist');
		}
		if(!array_key_exists('any_value', $array)){
			 throw new \UnexpectedValueException('Array key any_value does not exist');
		}

		return new self($array['title'],$array['minutes_to_prepare'],$array['rating'],$array['any_value'],);
	}
}