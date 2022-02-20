<?php

namespace TemplateEngine;

class TemplateEngine
{
    public function replaceValues(string $target, array $findReplaceMap): string
    {
        $file = file_get_contents($target);
        $result = $file;

        foreach ($findReplaceMap as $find => $replace){
            $result = str_replace('{{'.$find.'}}', $replace, $result);
        }

        $result = preg_replace('/\{\{\w+\}\}/', '', $result);

        return $result;
    }
}