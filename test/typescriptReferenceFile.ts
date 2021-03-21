export enum DietStyle {
	OMNIVORE = 'Omnivore',
	VEGETARIAN = 'Vegetarian',
	VEGAN = 'Vegan',
}

export enum Cuisine {
	DEUTSCH = 'deutsch',
	MEDITERAN = 'mediteran',
	ASIATISCH = 'asiatisch',
	AMERIKANISCH = 'amerikanisch',
	INDISCH = 'indisch',
}

export interface Recipe {
	title: string
	minutesToPrepare?: number
	rating: number
	dietStyle: DietStyle
}

export interface RecipeNoStringValue {
	title: string
	minutesToPrepare?: number
	rating: number
	dietStyle: DietStyle
}

export interface RecipeIntStringValue {
	title: string
	minutesToPrepare?: number
	rating: number
	dietStyle: DietStyle
}

export interface RecipeEnumStringValue {
	title: string
	minutesToPrepare?: number
	rating: number
	dietStyle: DietStyle
}

export interface RecipeCollection {
	recipe1: RecipeIntStringValue
	recipe2: RecipeEnumStringValue
}

export interface ValueObjectNoDataType {
	property: any
}

export interface DesertRecipe extends TestBaseClass {
	lactosefree: boolean
	light: boolean
}

export interface implementsOne {
	foo: string
	bar: number
}

export interface implementsMany {
	foo: string
	bar: number
}

export interface NotFinal {
	foo: string
	bar: number
}

export interface ChildOfNotFinal extends NotFinal {
	foo: string
}

export interface MutableObject {
	foo: string
}

export interface ExplicitlyImmutableObject {
	foo: string
}

export interface ImplicitlyImmutableObject {
	foo: string
}

export interface WithCamelCase {
	camelCased: string
}

export interface WithUnderscore {
	no_camel_case: string
}

export type RecipeSet = Array<Recipe>

export interface ValueObjectWithNestedSet {
	title: string
	recipes: RecipeSet
}

export type setWithPrimitiveType = Array<string>

export type mutableSetWithPrimitiveType = Array<string>

export interface RecipeWithDate {
	title: string
	minutesToPrepare?: number
	rating: number
	creationDate: string
}

export interface RecipeWithIndividualDateFormat {
	title: string
	minutesToPrepare?: number
	rating: number
	creationDate: string
}

