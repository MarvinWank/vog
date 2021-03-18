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