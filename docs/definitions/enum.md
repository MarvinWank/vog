# Enum

An Enum is a class which can hold any value out of a specific list of given options. For example, the value of  
an Enum called `DietStyle` could be any of "omnivore", "vegetarian" or "vegan". In vog, such an Enum would be defined as
follows:

```json
{
  "root_path": "/home/example_user/example_project/src",
  "models/values": [
    {
      "type": "enum",
      "name": "DietStyle",
      "values": {
        "OMNIVORE": "Omnivore",
        "VEGETARIAN": "Vegetarian",
        "VEGAN": "Vegan"
      }
    }
  ]
}
```

Let's have a look at the `values` property of our Enum. This object defines the possible values our Enum will be able to
hold. The keys refer to the **name** of the value, while the values refer to the, well, **value**. First of, PHP
constants will be generated from the value object. This will look as follows:

```php
    public const OPTIONS = [ "OMNIVORE" => "Omnivore", "VEGETARIAN" => "Vegetarian", "VEGAN" => "Vegan",];

    public const OMNIVORE = 'Omnivore';
    public const VEGETARIAN = 'Vegetarian';
    public const VEGAN = 'Vegan';
```

## Instantiating a generated Enum

An Enum cannot be directly instantiated, instead there are 3 different ways to create an enum

### 1. calling the static value methods

Each Enum will have public static methods named according to its **keys** defined in the value file. In this case:

```php
       public static function OMNIVORE(): self
       public static function VEGETARIAN(): self
       public static function VEGAN(): self
```

Calling `OMNIVORE()` will return an Enum with the value assigned to the key `OMNIVORE` in the value file

### 2. fromValue

You can also call the static `fromValue(string $value)` method, which accepts any value defined in the value file. So in
this case either
"Omnivore", "Vegetarian" or "Vegan".

### 3. fromName

Similarly to "fromValue", there also is static method `fromName(string $name)`, where you can construct an enum form
any **name** defined in the value file

## other methods

```php
    /** Compares two enums of the same type for equality */
    public function equals(?self $other): bool

    /**  Returns the name of the value of the enum */
    public function name(): string

    /** Returns the value of the enum */
    public function value(): string

    /** Returns the name of the enum */
    public function toString(): string

    /** Same as toString(). providing support for strval() */
    public function __toString(): string
```

## Use Enums in code

### In if and switch satements

Enums are great when different actions are taken by your application depending on the value of a specific variable, most
commonly in `if` oder `switch` statements. With Enums, there are no suprises: You know exactly which values are to
expect.

```php

switch ($recipe->name()){
    case Recipe::OMNIVORE:
       handleOmnivore();
       break;
    case Recipe::VEGETARIAN:
        handleVegetarian();
        break;
    case Recipe::VEGAN:
        handleVegan();
        break;
    default:
        // This can absolutely not happen. If it does, you can have your money back*.
        // *which means 0$, vog is free. 
}

```

### With Api Requests

If one side of an API decides to change the transmitted values even by a bit, it can lead to very hard to find errors.
Vog Enums instead will throw a descriptive Exception if an unexpected value is transmitted.

```php
$dietStyle = \Models\Values\DietStyle::fromValue($postRequest['dietStlye']);
```

### With vog valueObjects

vog enums and valueObject are a great combination. You can (and should!) define vog Enums as properties of vog valueObjects.
String conversion on `toArray` and `fromArray` works out of the box, but more on that [here](valueObject.md) 

