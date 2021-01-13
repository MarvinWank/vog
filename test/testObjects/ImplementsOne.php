<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Test\TestObjects;


use UnexpectedValueException;

final class ImplementsOne implements ValueObject,Interface1
{
    private string $foo;
    private int $bar;

    public function __construct (
        string $foo,
        int $bar
    ) {
        $this->foo = $foo;
        $this->bar = $bar;
    }
    
    public function getFoo(): string 
    {
        return $this->foo;
    }
    
    public function getBar(): int 
    {
        return $this->bar;
    }
    
    public function withFoo(string $foo): self 
    {
        return new self(
            $foo,
            $this->bar
        );
    }
    
    public function withBar(int $bar): self 
    {
        return new self(
            $this->foo,
            $bar
        );
    }
    
    public function toArray(): array
    {
        return [
            'foo' => $this->foo,
            'bar' => $this->bar,
        ];
    }
    
    public static function fromArray(array $array): self
    {
        if (!array_key_exists('foo', $array)) {
            throw new UnexpectedValueException('Array key foo does not exist');
        }
        
        if (!array_key_exists('bar', $array)) {
            throw new UnexpectedValueException('Array key bar does not exist');
        }
        
        return new self(
            $array['foo'],
            $array['bar']
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
}