<?php
/** 
 * code generated by vog
 * https://github.com/MarvinWank/vog
 */
declare(strict_types=1);

namespace Vog\ValueObjects;

use UnexpectedValueException;
use InvalidArgumentException;

final class GeneratorOptions
{
    private TargetMode $target;

    public function __construct (
        TargetMode $target
    ) {
        $this->target = $target;
    }
    
    public function getTarget(): TargetMode 
    {
        return $this->target;
    }
    
    public function withTarget(TargetMode $target): self 
    {
        return new self(
            $target
        );
    }
    
    public function toArray(): array
    {
        return [
            'target' =>  $this->valueToArray($this->target),
        ];
    }
    
    public static function fromArray(array $array): self
    {
        if (!array_key_exists('target', $array)) {
            throw new UnexpectedValueException('Array key target does not exist');
        }
        
        return new self(
            $array['target']
        );
    }
        
    private function valueToArray($value)
    {
        if (method_exists($value, 'toArray')) {
            return $value->toArray();
        }
        
        return (string) $value;
    }    
    public function equals($value)
    {
        $ref = $this->toArray();
        $val = $value->toArray();
        
        return ($ref === $val);
    }
}