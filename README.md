# vog - value object generator

vog is a zero-dependency Object-Oriented PHP Preprocessor that generates value objects based on vog definitions. 
The syntax is not inspired by Haskell and thus readable. 

## Table of contents

1. [Credits](#Credits) 

2. [Installation](#Installation)

3. [Usage](#Usage-and-subcommands)

4. [Configuration](#Configuration)

5. [Definition file](#Definition-file)

6. [Enum](#Enum)

7. [NullableEnum](#NullableEnum)

8. [Set](#Set)

9. [ValueObject](#ValueObject)

## Credits

This is basically a ripoff of https://github.com/prolic/fpp, but rewritten from scratch, with less fancy 
but readable code, full test coverage and proper documentation. 


## Usage and subcommands

After installing with composer, there is a plain php file in `vendor/bin/vog`, which can be called from the CLI. 
It has multiple subcommands, which are as follows: 

### generate

The `generate` subcommand is the core of vog. It allows you to generate both immutable and mutable PHP objects from a JSON definition as described below. It takes one additional argument: the path to the json file with the definitions.

Example call: `./vendor/bin/vog generate ./value.json`

### generate-typescript

The `generate-typescript` subcommand allows you to generate typescript interfaces and types from existing vog definitions.
It takes two additional arguments: The path to the definition file and the output file. All generated types and interface
will be written to the same file.

*Note*: namespaces are ignored by this command. All typescript files will be written to the same directory.

#### value objects

`generate-typescript` will create typescript interfaces from value objects. Example:

```json
    {
      "type": "valueObject",
      "name": "Recipe",
      "values": {
        "title": "string",
        "minutesToPrepare": "?int",
        "rating": "float",
        "dietStyle": "DietStyle"
      },
      "string_value": "title"
    }
```

Will result in 

```typescript
interface Recipe{
    title: string
    minutesToPrepare?: int
    rating: float,
    dietStyle: DietStyle
}
```

*Note*: Non-primitive types have to be defined in the same value file. 

                                              |

### Using multiple value files

If you want to create value objects for multiple namespaces in multiple directories, you have to use one value file for each directory/namespace.

    .
    ├── config           # your application config
    ├── modules          
    │   ├── Api          # Namespace Api\Models\Values
    │   │   └── src
    │   ├── Cart         # Namespace Cart\Models\Values 
    │   │    └── src
    │   └── Lib          # Namespace Lib\Models\Values
    │       └── src    
    ├── vog_config.php   # vog config is used for all value generation
    ├── api_values.json  # root_path is ./modules/Api/src, namespace is Api, key for value objects is "models/values"
    ├── cart_values.json # root_path is ./modules/Cart/src, namespace is Cart, key for value objects is
    └── lib_values.json  # root_path is ./modules/Lib/src, namespace is Lib, key for value objects is

If your directory layout would result with not key to define your value objects you can use the following json structure.

```json
{
  "root_path": "/home/example_user/example_project/src",
  "namespace": "Lib\\Models\\Values",  
  ".": [
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

This puts the files in "/home/example_user/example_project/src/." and uses Lib\Models\Values as namespace for this directory.

## Enum

An Enum is a class which can hold any value out of a specific list of given options. For example, the value of  
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

Let's have a look at the "values" object of our Enum. This object defines the possible values our Enum will be able to hold.
The keys refer to the **name** of the value, while the values refer to the, well, **value**. First of, PHP constants will be
generated from the value object. This will look as follows: 

```php
    public const OPTIONS = [ "OMNIVORE" => "Omnivore", "VEGETARIAN" => "Vegetarian", "VEGAN" => "Vegan",];

    public const OMNIVORE = 'Omnivore';
    public const VEGETARIAN = 'Vegetarian';
    public const VEGAN = 'Vegan';
```

### Instantiating a generated Enum

An Enum cannot be directly constructed, instead there are 3 different ways to create an enum

#### 1. calling the static value methods

Each Enum will have public static methods named according to its **keys** defined in the value file. In this case:

```php
       public static function OMNIVORE(): self
       public static function VEGETARIAN(): self
       public static function VEGAN(): self
```

Calling `OMNIVORE()` will return an Enum with the value assigned to the key `OMNIVORE` in the value file

#### 2. fromValue

You can also call the static `fromValue(string $value)` method, which accepts any value defined in the value file. So in this case either
"Omnivore", "Vegetarian" or "Vegan".

#### 3. fromName

Similarly to "fromValue", there also is static method `fromName(string $name)`, where you can construct an enum form any **name** defined 
in the value file 

### other methods

```php
    /** Compares two enums of the same type for equality */
    public function equals(?self $other): bool

    /**  Returns the name of the value of the enum */
    public function name(): string

    /** Returns the value of the enum */
    public function value(): string

    /** Returns the name of the enum */
    public function toString(): string

    /** Same as toString() */
    public function __toString(): string
```

### Using an enum in your code

Usually an Enum is used in other value objects or to check a selection.

```php
$dietStyle = TargetMode::VEGETARIAN();

if ($dietStyle->equals(DietStyle::fromName($someValue))) {
    // do something
}
```

A even better way is, if you test against a value object. For example  

```php
// or read data from a json file and json_decode it to an array.
$recipe = Recipe::fromArray([
    ...
    'dietStyle' => DietStyle::Vegetarian
]);

if ($recipe->getDietStyle()->equals(DietStyle::VEGETARIAN()) {
    //.. do something
}
```

If you want to persist an enum, use `(string)` or `toString()` which persists the name, not the value! 

## NullableEnum

The same as the regular Enum except it also accepts `null` in the `fromName` and `fromValue` methods and returns `null`
on `name()`, `value()` and `toString()`. It has the same structure in the value file as the enum and its name is `nullableEnum`

## Set

A Set is an array of values or objects. It is defined as:

```json
{
  "type": "set",
  "name": "RecipeSet",
  "itemType": "Recipe"
}
```

A Set implements \ArrayAccess, \Countable and \Iterator. The ArrayAccess is read-only as the set is immutable by default. Use add() and remove() which return a new set. In order to create a mutable set, that can be modified, you can use `"mutable": true`. If you create a mutable set, you have regular ArrayAccess features, and when you add or remove items, the objects is modified directly. You can use primitive datatypes in the set as well.

If you specify a non-empty `itemType` this type is enforced in add(), remove() and contains(). The item type can be any valid type or another value object. Please note that the json structure for a set has no "values".

### Instantiating a generated Set

```php
$obj = new RecipeSet([]); // create new empty set

$r1 = new Recipe::fromArray([...]);
$obj = $obj->add($r1),

$r2 = new Recipe::fromArray([ ... ]);
$obj = $obj->add($r2);
```

### fromArray

```php
$r1 = new Recipe::fromArray([...]);
$r2 = new Recipe::fromArray([ ... ]);

$obj = RecipeSet::fromArray([$r1, $r2]);

// or
$data = [
    [ ... ], // assuming valid data to create a Recipe::fromArray()
    [ ... ]
];
$obj = RecipeSet::fromArray($data);
```

fromArray() uses the fromArray() method on the objects stored in the set to create objects from a nested array structure. 

#### toArray

This returns an array with the values. This is a deep conversion that returns values instead of objects

### other methods

```php
    /** Compares two sets of the same type for equality */
    public function equals(?self $other): bool

    /** Returns the number of items im the set, implementing Countable */
    public function count(): int

    /** Adds the value to the set */
    public function add(<itemType> $item)

    /** Adds the value to the set */
    public function remove(<itemType> $item)

    /** Adds the value to the set */
    public function contains(<itemType> $item): bool

    /** used by isset implementing ArrayAccess */
    public function offsetExists($offset): bool

    /** throws an exception because of immutability use add() */
    public function offsetSet($offset, $value)   

    /** implementing ArrayAccess */
    public function offsetGet($offset)

    /** throws an exception because of immutability use remove() */
    public function offsetUnset($offset)

    /** implementing Iterator */
    public function current()

    /** implementing Iterator */
    public function rewind()

    /** implementing Iterator */
    public function key()

    /** implementing Iterator */
    public function next()

    /** implementing Iterator */
    public function valid()
```

If you add/remove items from a set a new instance with modified values is returned as the original set is immutable.

## ValueObject

A value object is an object that once constructed cannot be changed. It has getters, but no setters. Also, it doesn't do any business logic at all. It truly is an object that holds values - and nothing more.

```json
    {
      "type": "valueObject",
      "name": "Recipe",
      "values": {
        "title": "string",
        "minutesToPrepare": "?int",
        "rating": "float",
        "dietStyle": "DietStyle"
      },
      "string_value": "title"
    }
```

The properties are similar to those of the Enum. The `values` object follows the `"identifier": "datatype"` syntax. 

If you don't want to specify a datatype, simply provide an empty string. 

Notice that you may also define nullable types and object types. You'll have to provide the namespace to the object, but in this case, "DietStyle" hast the same namespace as "Recipe".

#### result

Vog will generate private class members according to the definition in the value file.

```php
final class Recipe
{
    private string $title;
    private ?int $minutesToPrepare;
    private float $rating;
    private DietStyle $dietStyle;
```

The members are available by getter functions: 

```php
    public function getTitle(): string {
        return $this->title;
    }
```

As it is an immutable value object per default, there are no setters. Instead, you'll have to create entirely new objects if you want to alter a value. This is made easy by the `with_` methods: 

```php
    public function withTitle (string $title):self
    {
        return new self($title,$this->minutes_to_prepare,$this->rating,$this->diet_style,);
    }
```

`with` methods will be generated for each member defined in the value file. 

If you declare your object `"mutable": true`, it will have `with` methods in addition to setters `setTitle(string $title)` to ensure compatibility.

### Instantiating a generated valueObject

You can use the constructor to create a new instance providing all required parameters.

```php
    public function __construct (string $title, ?int $minutesToPrepare, float $rating, DietStyle $dietStyle)
    {
        $this->title = $title;
        $this->minutesToPrepare = $minutesToPrepare;
        $this->rating = $rating;
        $this->dietStyle = $dietStyle;
    }
```

#### fromArray

A value object can also be created from the static `fromArray()` method.  The keys of the array must match the property names.

```php
$obj = new Recipe::fromArray([
    'title' => 'Title',
    'minutesToPrepare' => null,
    'rating' => 10,
    'dietStyle' => DietStyle::VEGAN()
]);
```

#### toArray

A value object may also be converted into an associative array, for example for data transfer purposes. Note that this is a **deep conversion**, so any non-primitive member will be serialized. 

This is ensured by first checking the non-primitive member for a `toArray()` function. If it does have one, this function is called. 

If it does not have one, the value is casted to string.

Since all elements generated by vog either have a `toArray` method or a fitting implementation of `__toString()`, no strange effects occur when building vog value objects solely on vog generated elements. However, use that function with caution when mixing other objects into vog value objects!

### other methods

```php
    /** Compares two enums of the same type for equality */
    public function equals(?self $other): bool
```

If you define `string_value` for your value object the following methods are added.

```php
    /** Returns the string_value of the value object*/
    public function toString(): string

    /** Same as toString() */
    public function __toString(): string
```

### DateTime Support

Vog does support DateTimeImmutables as strings in `toArray` and `fromArray`!
Just set `\\DateTimeImmutable` (double backslash necessary for escaping) as the data type and vog takes care of the rest. 

The default format is `Y-m-d`, yet that can be changed both [globally](#Configuration) and [individually](#generic-properties)

for each object.

`\\\DateTime` is also possible, but only when the value object is explicitly declared mutable. Otherwise vog will throw an exception.