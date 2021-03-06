<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Vog\ValueObjects;


use UnexpectedValueException;

final class Config implements ValueObject
{
    private GeneratorOptions $generatorOptions;

    public function __construct (
        GeneratorOptions $generatorOptions
    ) {
        $this->generatorOptions = $generatorOptions;
    }
    
    public function getGeneratorOptions(): GeneratorOptions 
    {
        return $this->generatorOptions;
    }
    
    public function withGeneratorOptions(GeneratorOptions $generatorOptions): self 
    {
        return new self(
            $generatorOptions
        );
    }
    
    public function toArray(): array
    {
        return [
            'generatorOptions' =>  $this->valueToArray($this->generatorOptions),
        ];
    }
    
    public static function fromArray(array $array): self
    {
        if (!array_key_exists('generatorOptions', $array)) {
            throw new UnexpectedValueException('Array key generatorOptions does not exist');
        }
        
        if (is_string($array['generatorOptions']) && is_a(GeneratorOptions::class, Enum::class, true)) {
            $array['generatorOptions'] = GeneratorOptions::fromName($array['generatorOptions']);
        }
    
        if (is_array($array['generatorOptions']) && (is_a(GeneratorOptions::class, Set::class, true) || is_a(GeneratorOptions::class, ValueObject::class, true))) {
            $array['generatorOptions'] = GeneratorOptions::fromArray($array['generatorOptions']);
        }

        return new self(
            $array['generatorOptions']
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