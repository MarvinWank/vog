<?php


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
       return "type $this->name = Array<$itemType>\n\n";
    }



}