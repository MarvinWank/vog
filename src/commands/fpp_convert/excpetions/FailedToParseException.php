<?php


class FailedToParseException extends Exception
{
    public static function failedToParseValueObject(string $signature): self
    {
        return new self("Failed to Parse vog definition with siganture '$signature' as value object");
    }
}