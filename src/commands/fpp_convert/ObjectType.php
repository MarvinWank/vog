<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Vog\FppConvert;


use InvalidArgumentException;

final class ObjectType implements Enum
{
    public const OPTIONS = [ 
        'VOG_ENUM' => 'enum',
        'VOG_NULLABLE_ENUM' => 'nullableEnum',
        'VOG_VALUE_OBJECT' => 'valueObject',
        'VOG_SET' => 'set',
    ];

    public const VOG_ENUM = 'enum';               
    public const VOG_NULLABLE_ENUM = 'nullableEnum';               
    public const VOG_VALUE_OBJECT = 'valueObject';               
    public const VOG_SET = 'set';                       
    private string $name;
    private string $value;
        
    private function __construct(string $name)
    {
        $this->name = $name;
        $this->value = self::OPTIONS[$name];
    }

    public static function VOG_ENUM(): self
    {
        return new self('VOG_ENUM');
    }
    
    public static function VOG_NULLABLE_ENUM(): self
    {
        return new self('VOG_NULLABLE_ENUM');
    }
    
    public static function VOG_VALUE_OBJECT(): self
    {
        return new self('VOG_VALUE_OBJECT');
    }
    
    public static function VOG_SET(): self
    {
        return new self('VOG_SET');
    }
    
    public static function fromValue(string $value): self
    {
        foreach (self::OPTIONS as $key => $option) {
            if ($value === $option) {
                return new self($key);
            }
        }

        throw new InvalidArgumentException("Unknown enum value '$value' given");
    }
    
    public static function fromName(string $name): self
    {
        if(!array_key_exists($name, self::OPTIONS)){
             throw new InvalidArgumentException("Unknown enum name $name given");
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