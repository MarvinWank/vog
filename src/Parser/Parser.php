<?php

interface Parser
{
    public function parseFile(string $filePath): array;
}