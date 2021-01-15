<?php


namespace Vog\FppConvert;


class ValueObjectJsonObjectBuilder extends AbstractJsonObjectBuilder
{
    public function __construct(string $name, array $values)
    {
        parent::__construct($name, ObjectType::VOG_VALUE_OBJECT(), $values);
    }

    public function getJsonCode(): string
    {

    }

}