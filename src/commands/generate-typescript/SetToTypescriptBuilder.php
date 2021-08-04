<?php
declare(strict_types = 1);

namespace Vog\Commands\GenerateTypescript;


class SetToTypescriptBuilder extends AbstractTypescriptBuilder
{
    private string $itemType;

    public function setItemType(string $itemType)
    {
        $this->itemType = $itemType;
    }

    public function getTypescriptCode(): string
    {
       $itemType = $this->sanitizeDataTypeForTypescript($this->itemType);
       return "export type $this->name = Array<$itemType>\n\n";
    }



}