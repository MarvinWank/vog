<?php

namespace Vog\Service\Php;

use Vog\ValueObjects\GeneratorOptions;

class SetService extends AbstractPhpService
{
    //TODO: Make controller optionally public --> Generator
    public function generateConstructor(): string
    {
        return $this->templateEngine->replaceValues(self::TEMPLATE_DIR . '/Set/Constructor.vtpl');
    }


    public function generateToArray(string $itemType): string
    {
        $phpcode = <<<EOT
        
            public function toArray() {
        EOT;

        //TODO: toggle between method_exists() Check and Interface Check according to configuration --> Generator
        if (!in_array($itemType, parent::PHP_PRIMITIVE_TYPES)) {
            $phpcode .= <<<EOT
                
                        \$return = [];
                        foreach (\$this->items as \$item) {
                            if(method_exists(\$item, 'toArray')) {
                                \$return[] = \$item->toArray();
                            }
                            
                            else if(method_exists(\$item, 'toString')) {
                                \$return[] = \$item->toString();
                            }
                            
                            else{
                                \$return[] = \$item;
                            }
                        }
                        
                        return \$return;
                EOT;
        } else {
            $phpcode .= <<<EOT
                
                        return \$this->items;
                EOT;
        }

        $phpcode .= <<<EOT
        
            }
        EOT;
        return $phpcode;
    }
}