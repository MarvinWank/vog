<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Vog\ValueObjects;


use UnexpectedValueException;

class GeneratorOptions implements ValueObject
{
    private TargetMode $target;
    private ?string $dateTimeFormat;
    private ToArrayMode $toArrayMode;

    public function __construct (
        TargetMode $target,
        ?string $dateTimeFormat,
        ToArrayMode $toArrayMode
    ) {
        $this->target = $target;
        $this->dateTimeFormat = $dateTimeFormat;
        $this->toArrayMode = $toArrayMode;
    }
    
    public function getTarget(): TargetMode 
    {
        return $this->target;
    }
    
    public function getDateTimeFormat(): ?string 
    {
        return $this->dateTimeFormat;
    }
    
    public function getToArrayMode(): ToArrayMode 
    {
        return $this->toArrayMode;
    }
    
    public function withTarget(TargetMode $target): self 
    {
        return new self(
            $target,
            $this->dateTimeFormat,
            $this->toArrayMode
        );
    }
    
    public function withDateTimeFormat(?string $dateTimeFormat): self 
    {
        return new self(
            $this->target,
            $dateTimeFormat,
            $this->toArrayMode
        );
    }
    
    public function withToArrayMode(ToArrayMode $toArrayMode): self 
    {
        return new self(
            $this->target,
            $this->dateTimeFormat,
            $toArrayMode
        );
    }
    
    public function toArray(): array
    {
        return [
            'target' =>  $this->valueToArray($this->target),
            'dateTimeFormat' => $this->dateTimeFormat,
            'toArrayMode' =>  $this->valueToArray($this->toArrayMode),
        ];
    }
    
    public static function fromArray(array $array): self
    {        
        if (!array_key_exists('target', $array)) {
            throw new UnexpectedValueException('Array key target does not exist');
        }
        
        if (is_string($array['target']) && is_a(TargetMode::class, Enum::class, true)) {
            $array['target'] = TargetMode::fromName($array['target']);
        }
    
        if (is_array($array['target']) && (is_a(TargetMode::class, Set::class, true) || is_a(TargetMode::class, ValueObject::class, true))) {
            $array['target'] = TargetMode::fromArray($array['target']);
        }
        
        if (!array_key_exists('toArrayMode', $array)) {
            throw new UnexpectedValueException('Array key toArrayMode does not exist');
        }
        
        if (is_string($array['toArrayMode']) && is_a(ToArrayMode::class, Enum::class, true)) {
            $array['toArrayMode'] = ToArrayMode::fromName($array['toArrayMode']);
        }
    
        if (is_array($array['toArrayMode']) && (is_a(ToArrayMode::class, Set::class, true) || is_a(ToArrayMode::class, ValueObject::class, true))) {
            $array['toArrayMode'] = ToArrayMode::fromArray($array['toArrayMode']);
        }

        return new self(
            $array['target'],
            $array['dateTimeFormat'],
            $array['toArrayMode']
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