# nullableEnum

The nullableEnum works exactly like a [regular enum](enum.md) except it also accepts `null` in the `fromName` 
and `fromValue` methods and can return `null` on `name()`, `value()` and `toString()`.
It has the same structure in the value file as the enum and its name is `nullableEnum`