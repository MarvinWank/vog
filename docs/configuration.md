## Configuration

Vog comes with a default config explicitly stating all available options.

```json
{
    "generatorOptions": {
        "target": "MODE_PSR2",
        "dateTimeFormat": "Y-m-d"
    }
}
```

You may alter the configuration, but

- Your custom config must be namend `vog_config.json` 
- Your custom config must be in the same directoy as you `vendor` directory

I know this is an annoying restraint I am working on it.

These are the currently available options:

## target

- default value: `MODE_PSR2`
- available values: 
  - `MODE_PSR2`
  - `MODE_FPP`
- affects commands:
  - `generate`
- affects definitions:
  - valueObject

This config options changes the code style of the generated PHP-objects. Assume 
you define a property as `"property_name": "string"` in your [definition file](definition_file.md):

`MODE_FPP` is for providing compatibility to fpp. It is not generally recommended as it vioaltes PSR2.

| Method Type   | PSR2                                         | FPP                                             | Notes              |
| ------------- | -------------------------------------------- | ----------------------------------------------- | ------------------ |
| Getter        | getPropertyName(): string                    | property_name(): string                         |                    |
| Fluent getter | withPropertyName(string $propertyName): self | with_property_name(string $property_Name): self |                    |
| Setter        | setPropertyName(string $propertyName)        | set_property_name(string $property_name)        | if defined mutable |

`MODE_PSR2` converts snake_case definitions into camelCase getters, setters and variable names.

`MODE_FPP` does NOT convert camelCase definitions into snake_case PHP-representations.

## Option dateTimeFormat

- default value: `Y-m-d`

- avaiable values: [PHP-Docs](https://www.php.net/manual/de/function.date.php)

- affects commands
  
  - generate
- affects definitions
  - valueObject

Defines which format vog expects when it creates a PHP-DateTimeImmutable object form string int `formArray`. The global value may be overwritten by an object-specific value. See the valueObject-section on the [definition file](definitions/valueObject.md) 