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