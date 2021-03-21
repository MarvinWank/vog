# generate-typescript

The `generate-typescript` subcommand allows you to generate typescript interfaces and types from existing vog definitions.
It takes two additional arguments: The path to the definition file and the output file. All generated types and interface
will be written to the same file.

*Note*: namespaces are ignored by this command. All typescript files will be written to the same directory.

## value objects

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
interface Recipe {
    title: string
    minutesToPrepare?: int
    rating: float,
    dietStyle: DietStyle
}
```

*Note*: Non-primitive types have to be defined in the same value file. 

## enums/nullableEnums

`generate-typescript` will create typescript enums from enums or nullable Enums. 

**Note**: enums and nullableEnums will result in the same typescript enum

Example

```json
    {
      "type": "enum",
      "name": "DietStyle",
      "values": {
        "OMNIVORE": "Omnivore",
        "VEGETARIAN": "Vegetarian",
        "VEGAN": "Vegan"
      }
    }
```

will result in 

```ts 
export enum DietStyle {
	OMNIVORE = 'Omnivore',
	VEGETARIAN = 'Vegetarian',
	VEGAN = 'Vegan',
}
```

## Set 

Since typescript natively supports generics, Sets will be simply be converted to an array of the given type.

Example: 

```json
    {
      "type": "set",
      "name": "RecipeSet",
      "itemType": "Recipe"
    }
```

will result in

``` ts
export type RecipeSet = Array<Recipe>
```

Keep in mind that non-primitve data types must be defined in the same .ts File, there is no automatic import.