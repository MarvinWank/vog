<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Vog\ValueObjects;


use InvalidArgumentException;

final class TargetMode implements Enum
{
    public const VALUES = ['psr2', 'fpp', ];               
    public const NAMES = ['MODE_PSR2', 'MODE_FPP', ];               
    public const OPTIONS = [ 
        'MODE_PSR2' => 'psr2',
        'MODE_FPP' => 'fpp',
    ];

    public const MODE_PSR2 = 'psr2';               
    public const MODE_FPP = 'fpp';               
        
    private ?string $name;
    private ?string $value;
        
    private function __construct(?string $name)
    {
        if(is_null($name)){
            $this->name = null;
            $this->value = null;
        } else {
            $this->name = $name;
            $this->value = self::OPTIONS[$name];
        }
    }

    public static function MODE_PSR2(): self
    {
        return new self('MODE_PSR2');
    }
    
    public static function MODE_FPP(): self
    {
        return new self('MODE_FPP');
    }
    
    public static function fromValue(?string $value): self
    {
        if(is_null($value)){
            return new self(null);
        }
    
        foreach (self::OPTIONS as $key => $option) {
            if ($value === $option) {
                return new self($key);
            }
        }

        throw new InvalidArgumentException("Unknown enum value '$value' given");
    }
    
    public static function fromName(?string $name): self
    {
        if(is_null($name)){
            return new self(null);
        }
    
        if(!array_key_exists($name, self::OPTIONS)){
             throw new InvalidArgumentException("Unknown enum name $name given");
        }
        
        return new self($name);
    }

    public function equals(?self $other): bool
    {
        if($this->value === null && $other === null){
            return true;
        }
        return ($other !== null) && ($this->name() === $other->name());
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function toString(): ?string
    {
        return $this->name;
    }
}