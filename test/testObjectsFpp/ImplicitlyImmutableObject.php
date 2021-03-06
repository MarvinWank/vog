<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Test\TestObjectsFpp;


use UnexpectedValueException;

final class ImplicitlyImmutableObject implements ValueObject
{
    private string $foo;

    public function __construct (
        string $foo
    ) {
        $this->foo = $foo;
    }
    
    public function foo(): string 
    {
        return $this->foo;
    }
    
    public function with_foo(string $foo): self 
    {
        return new self(
            $foo
        );
    }
    
    public function toArray(): array
    {
        return [
            'foo' => $this->foo,
        ];
    }
    
    public static function fromArray(array $array): self
    {
        if (!array_key_exists('foo', $array)) {
            throw new UnexpectedValueException('Array key foo does not exist');
        }
        
        return new self(
            $array['foo']
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
    
}