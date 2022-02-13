<?php

namespace Vog\Parser;

interface Parser
{
    public function parseFile(string $filePath): array;
}