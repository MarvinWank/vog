<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Vog\ValueObjects;


use UnexpectedValueException;

class VogDefinition implements ValueObject
{
    private string $name;
    private string $directory;
    private VogTypes $type;
    private ?array $values;
    private ?string $stringValue;
    private ?string $dateTimeFormat;
    private ?string $itemType;
    private ?string $extends;
    private ?string $implements;
    private ?string $final;
    private ?bool $mutable;

    public function __construct (
        string $name,
        string $directory,
        VogTypes $type,
        ?array $values,
        ?string $stringValue,
        ?string $dateTimeFormat,
        ?string $itemType,
        ?string $extends,
        ?string $implements,
        ?string $final,
        ?bool $mutable
    ) {
        $this->name = $name;
        $this->directory = $directory;
        $this->type = $type;
        $this->values = $values;
        $this->stringValue = $stringValue;
        $this->dateTimeFormat = $dateTimeFormat;
        $this->itemType = $itemType;
        $this->extends = $extends;
        $this->implements = $implements;
        $this->final = $final;
        $this->mutable = $mutable;
    }
    
    public function name(): string 
    {
        return $this->name;
    }
    
    public function directory(): string 
    {
        return $this->directory;
    }
    
    public function type(): VogTypes 
    {
        return $this->type;
    }
    
    public function values(): ?array 
    {
        return $this->values;
    }
    
    public function stringValue(): ?string 
    {
        return $this->stringValue;
    }
    
    public function dateTimeFormat(): ?string 
    {
        return $this->dateTimeFormat;
    }
    
    public function itemType(): ?string 
    {
        return $this->itemType;
    }
    
    public function extends(): ?string 
    {
        return $this->extends;
    }
    
    public function implements(): ?string 
    {
        return $this->implements;
    }
    
    public function final(): ?string 
    {
        return $this->final;
    }
    
    public function mutable(): ?bool 
    {
        return $this->mutable;
    }
    
    public function with_name(string $name): self 
    {
        return new self(
            $name,
            $this->directory,
            $this->type,
            $this->values,
            $this->stringValue,
            $this->dateTimeFormat,
            $this->itemType,
            $this->extends,
            $this->implements,
            $this->final,
            $this->mutable
        );
    }
    
    public function with_directory(string $directory): self 
    {
        return new self(
            $this->name,
            $directory,
            $this->type,
            $this->values,
            $this->stringValue,
            $this->dateTimeFormat,
            $this->itemType,
            $this->extends,
            $this->implements,
            $this->final,
            $this->mutable
        );
    }
    
    public function with_type(VogTypes $type): self 
    {
        return new self(
            $this->name,
            $this->directory,
            $type,
            $this->values,
            $this->stringValue,
            $this->dateTimeFormat,
            $this->itemType,
            $this->extends,
            $this->implements,
            $this->final,
            $this->mutable
        );
    }
    
    public function with_values(?array $values): self 
    {
        return new self(
            $this->name,
            $this->directory,
            $this->type,
            $values,
            $this->stringValue,
            $this->dateTimeFormat,
            $this->itemType,
            $this->extends,
            $this->implements,
            $this->final,
            $this->mutable
        );
    }
    
    public function with_stringValue(?string $stringValue): self 
    {
        return new self(
            $this->name,
            $this->directory,
            $this->type,
            $this->values,
            $stringValue,
            $this->dateTimeFormat,
            $this->itemType,
            $this->extends,
            $this->implements,
            $this->final,
            $this->mutable
        );
    }
    
    public function with_dateTimeFormat(?string $dateTimeFormat): self 
    {
        return new self(
            $this->name,
            $this->directory,
            $this->type,
            $this->values,
            $this->stringValue,
            $dateTimeFormat,
            $this->itemType,
            $this->extends,
            $this->implements,
            $this->final,
            $this->mutable
        );
    }
    
    public function with_itemType(?string $itemType): self 
    {
        return new self(
            $this->name,
            $this->directory,
            $this->type,
            $this->values,
            $this->stringValue,
            $this->dateTimeFormat,
            $itemType,
            $this->extends,
            $this->implements,
            $this->final,
            $this->mutable
        );
    }
    
    public function with_extends(?string $extends): self 
    {
        return new self(
            $this->name,
            $this->directory,
            $this->type,
            $this->values,
            $this->stringValue,
            $this->dateTimeFormat,
            $this->itemType,
            $extends,
            $this->implements,
            $this->final,
            $this->mutable
        );
    }
    
    public function with_implements(?string $implements): self 
    {
        return new self(
            $this->name,
            $this->directory,
            $this->type,
            $this->values,
            $this->stringValue,
            $this->dateTimeFormat,
            $this->itemType,
            $this->extends,
            $implements,
            $this->final,
            $this->mutable
        );
    }
    
    public function with_final(?string $final): self 
    {
        return new self(
            $this->name,
            $this->directory,
            $this->type,
            $this->values,
            $this->stringValue,
            $this->dateTimeFormat,
            $this->itemType,
            $this->extends,
            $this->implements,
            $final,
            $this->mutable
        );
    }
    
    public function with_mutable(?bool $mutable): self 
    {
        return new self(
            $this->name,
            $this->directory,
            $this->type,
            $this->values,
            $this->stringValue,
            $this->dateTimeFormat,
            $this->itemType,
            $this->extends,
            $this->implements,
            $this->final,
            $mutable
        );
    }
    
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'directory' => $this->directory,
            'type' =>  $this->valueToArray($this->type),
            'values' => $this->values,
            'stringValue' => $this->stringValue,
            'dateTimeFormat' => $this->dateTimeFormat,
            'itemType' => $this->itemType,
            'extends' => $this->extends,
            'implements' => $this->implements,
            'final' => $this->final,
            'mutable' => $this->mutable,
        ];
    }
    
    public static function fromArray(array $array): self
    {        
        if (!array_key_exists('name', $array)) {
            throw new UnexpectedValueException('Array key name does not exist');
        }
                
        if (!array_key_exists('directory', $array)) {
            throw new UnexpectedValueException('Array key directory does not exist');
        }
                
        if (!array_key_exists('type', $array)) {
            throw new UnexpectedValueException('Array key type does not exist');
        }
        
        if (isset($array['type']) && is_string($array['type']) && is_a(VogTypes::class, Enum::class, true)) {
            $array['type'] = VogTypes::fromName($array['type']);
        }
    
        if (isset($array['type']) && is_array($array['type']) && (is_a(VogTypes::class, Set::class, true) || is_a(VogTypes::class, ValueObject::class, true))) {
            $array['type'] = VogTypes::fromArray($array['type']);
        }

        return new self(
            $array['name'] ?? null,
            $array['directory'] ?? null,
            $array['type'] ?? null,
            $array['values'] ?? null,
            $array['stringValue'] ?? null,
            $array['dateTimeFormat'] ?? null,
            $array['itemType'] ?? null,
            $array['extends'] ?? null,
            $array['implements'] ?? null,
            $array['final'] ?? null,
            $array['mutable'] ?? null
        );
    }
        
    private function valueToArray($value)
    {
        if($value === null){
            return null;
        }
    
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