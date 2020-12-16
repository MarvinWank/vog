<?php


namespace Vog;


class ValueObject extends VogDataObject
{
    private const PRIMITIVE_TYPES = ["string", "int", "float", "bool", "array"];
    private string $string_value;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->type = "valueObject";
    }

    public function set_string_value(string $string_value)
    {
        if(!array_key_exists($string_value, $this->values)){
            throw new \UnexpectedValueException("Designated string value $string_value does not exist in values");
        }

        $this->string_value = $string_value;
    }

    public function getPhpCode(): string
    {
        $phpcode = $this->generateGenericPhpHeader();

        foreach ($this->values as $name => $data_type) {
            $phpcode .= "\n\tprivate $data_type $$name;";
        }

        $phpcode = $this->generate_construtctor($phpcode);
        $phpcode = $this->generate_getters($phpcode);
        $phpcode = $this->generate_with_methods($phpcode);
        $phpcode = $this->generate_to_array($phpcode);
        $phpcode = $this->generate_from_array($phpcode);
        $phpcode = $this->generate_value_to_array($phpcode);

        if(isset($this->string_value)){
            $phpcode = $this->generate_toString($phpcode);
        }


        $phpcode .= "\n}";
        return $phpcode;
    }

    private function str_lreplace($search, $replace, $subject)
    {
        $pos = strrpos($subject, $search);

        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

    private function generate_construtctor(string $phpcode): string
    {
        $phpcode .= "\n\n\tpublic function __construct (";
        foreach ($this->values as $name => $data_type) {
            $phpcode .= "\n\t\t$data_type $$name,";
        }
        $phpcode = $this->str_lreplace(',', '', $phpcode);
        $phpcode .= "\n\t)\n\t{";

        foreach ($this->values as $name => $data_type) {
            $phpcode .= "\n\t\t\$this->$name = $$name;";
        }
        $phpcode .= "\n\t}\n";
        return $phpcode;
    }

    private function generate_getters(string $phpcode): string
    {
        foreach ($this->values as $name => $data_type) {
            if ($data_type) {
                $phpcode .= "\n\tpublic function $name(): $data_type {";
            } else {
                $phpcode .= "\n\tpublic function $name() {";
            }
            $phpcode .= "\n\t\treturn \$this->$name;";
            $phpcode .= "\n\t}\n";
        }
        return $phpcode;
    }

    private function generate_with_methods(string $phpcode)
    {
        foreach ($this->values as $name => $data_type) {
            $phpcode .= "\n\n\tpublic function with_$name (";

            $phpcode .= "$data_type $$name";
            $phpcode .= "):self\n\t{";

            $phpcode .= "\n\t\treturn new self(";
            foreach ($this->values as $name_assigner => $data_type_assginer) {
                if ($name_assigner === $name) {
                    $phpcode .= "$$name_assigner,";
                    continue;
                }
                $phpcode .= "\$this->$name_assigner,";

            }
            $phpcode .= ");";
            $phpcode .= "\n\t}";
        }

        return $phpcode;
    }

    private function generate_to_array(string $phpcode): string
    {
        $phpcode .= "\n\tpublic function toArray(): array";
        $phpcode .= "\n\t{";

        $phpcode .= "\n\t\t return [";
        foreach ($this->values as $name => $datatype) {
            if (!in_array($datatype, self::PRIMITIVE_TYPES)) {
                $phpcode .= "\n\t\t\t '$name' =>  \$this->value_to_array(\$this->$name), ";
            } else {
                $phpcode .= "\n\t\t\t '$name' => \$this->$name, ";
            }
        }
        $phpcode .= "\n\t\t];";

        $phpcode .= "\n\t}";
        return $phpcode;
    }

    private function generate_from_array(string $phpcode): string
    {
        $phpcode .= "\n\n\tpublic static function fromArray(array \$array): self";
        $phpcode .= "\n\t{";

        foreach ($this->values as $name => $datatype) {
            $phpcode .= "\n\t\tif(!array_key_exists('$name', \$array)){";
            $phpcode .= "\n\t\t\t throw new \\UnexpectedValueException('Array key $name does not exist');";
            $phpcode .= "\n\t\t}";
        }

        $phpcode .= "\n";

        $phpcode .= "\n\t\treturn new self(";
        foreach ($this->values as $name => $datatype) {
            $phpcode .= "\$array['$name'],";
        }
        $phpcode .= ");";

        $phpcode .= "\n\t}";

        return $phpcode;
    }

    private function generate_toString(string $phpcode)
    {
        $phpcode .= "\n\n\tpublic function __toString(): string";
        $phpcode .= "\n\t{";
        $phpcode .= "\n\t\treturn strval(\$this->$this->string_value);";
        $phpcode .= "\n\t}";

        $phpcode .= "\n\n\tpublic function toString(): string";
        $phpcode .= "\n\t{";
        $phpcode .= "\n\t\treturn strval(\$this->$this->string_value);";
        $phpcode .= "\n\t}";

        return $phpcode;
    }

    private function generate_value_to_array(string $phpcode)
    {
        $phpcode .= "\n\n\tprivate function value_to_array(\$value)\n\t{";

        $phpcode .= "\n\t\tif(method_exists(\$value, 'toArray')) {";
        $phpcode .= "\n\t\t\treturn \$value->toArray();";
        $phpcode .= "\n\t\t}";

        $phpcode .= "\n\t\treturn strval(\$value);";

        $phpcode .= "\n\t}";

        return $phpcode;
    }

}