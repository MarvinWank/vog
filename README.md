# vog - value object generator

vog is a zero-dependcy Object-Oriented PHP Preprocessor that generates immutable data types based on vog definitions. 
The syntax is not inspired by Haskell and thus readable. 

## Credits
This is basically a ripoff of https://github.com/prolic/fpp, but rewritten from scratch, with less fancy 
but readable code, full test coverage and proper documentation. 

## Installation

vog can be most easily installed with composer:
`composer require marvinwank/vog`

## Usage

After installing with composer, there is a plain php file in `vendor/marvinwank/vog`
It can be called from the CLI and accepts two arguments:

1. [**required**] The path to the directory, in which the value file is located
2. [**optional**] The name of the value file. Default: `value.json`

Example call: `./vendor/marvinwank/vog/vog.php ./app/value`

Example call with custom value file name: `./vendor/marvinwank/vog/vog.php ./app/value  customNameValueFile.json`

Notice the space between between the first an the second argument. 

## The value file

The raw data of the objects to be generated by vog a stored in the value file in json format. 
On the root level of the value file, you have to provide the full path to your projects root with the key `root_path`.
No `/` is necessary at the end

Example:
```json
{
  "root_path": "/home/example_user/example_project/src",
 
 
  "models/values": [
      {
        "type": "enum",
        "name": "DietStyle",
        "values": {
          "EVERYTHING": "everything",
          "VEGETARIAN": "vegetarian",
          "VEGAN": "vegan"
        }
      }
  ]
}
```

As you can already see in the example, the value file also has an array at the top level, with the key of it being a path. 
In this array, an Enum with the name of "DietStyle" is given. When generated, its full path will be 
`root_path` + `<key of the path array>` + `name` + `.php`, so in this example

`/home/example_user/example_project/src/models/values/DietStyle.php`

Its namespace will be automatically generated from the path specification according to PSR-4, so in this case it would be 
`Models\Values` 

Any number of objects may be defined in each path array and any number of path arrays may be given in the value file

*Note:* There can be multiple value files although this is not recommended, because you can define paths in the value
file and multiple value files require multiple calls.

## enum

An Enum is a class which can hold any value out of a specific array of given options. For example, the value of  
an Enum called `DietStyle` could be any of "omnivore", "vegetarian" or "vegan". In vog, such an Enum would be defined
as follows:

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

Let's have a look at the "values" object of our enum. This object defines the possible values our Enum will be able to hold.
The keys refer to the **name** of the value, while the values refer to the, well, **value**. First of, PHP constants will be
generated from the value object. This will look as follows: 

```php
	public const OPTIONS = [ "OMNIVORE" => "Omnivore", "VEGETARIAN" => "Vegetarian", "VEGAN" => "Vegan",];

	public const OMNIVORE = 'Omnivore';
	public const VEGETARIAN = 'Vegetarian';
	public const VEGAN = 'Vegan';
```

### Instantiating a generated Enum

An Enum cannot be directly constructed, instead there are 3 different ways to createn an enum

#### 1. calling the static methods

Each Enum will have public static methods named according to its **keys** defined in the value file. In this case:
```php
       public static function OMNIVORE(): self
       public static function VEGETARIAN(): self
       public static function VEGAN(): self
``` 
Calling the `OMNIVORE` method will return an Enum with the value assigned to the key `OMNIVORE` in the value file

#### 2. from value
You can also call the static `fromValue` method, which accepts any value defined in the value file. So in this case either
"Omnivore", "Vegetarian" or "Vegan".

#### 3. from name
Similarily to "fromValue", there also is static method `fromName`, where you can construct an enum form any value defined 
in the value file 

### other Methods

```php
    /** Compares two enums of the same type for equality */
    public function equals(?self $other): bool
    /**  Returns the name of the value of the enum */
    public function name(): string
    /** Returns the value of the enum */
    public function value(): string
    /** Returns the name of the value of the enum */
    public function toString(): string
    /** Same es toString(), but provides support for strval() */
    public function __toString(): string
```
## nullableEnum

The same as the regular Enum except it also accepts `null` in the `fromName` and `fromValue` methods and returns `null`
on `name()`, `value()` and `toString()`. It has the same structure in the value file as the enum and its name is `nullableEnum`

## valueObject

A value object is an object that once constructed cannot be changed. It has getters, but no setters. Also, it doesn't do 
any business logic at all. It truly is an object that holds values - and nothing more.

#### vog definition

```json
    {
      "type": "valueObject",
      "name": "Recipe",
      "values": {
        "title": "string",
        "minutes_to_prepare": "?int",
        "rating": "float",
        "diet_style": "DietStyle"
      },
      "string_value": "title"
    }
```  

The properties are similar to those of the Enum. The `values` object follows the `"identifier": "datatype"` syntax. Notice
that you may also define nullable types and object types. You have to provide the namespace to the object, but in this case,
"DietStyle" hast the same namespace as "Recipe
"