# var

_var_ is a plugin for [Statamic](http://statamic.com) which allows theme authors to set and get variables easily inside a template.

## Installing

1. Download the zip file (or clone via git) and unzip it or clone the repo into /_add-ons/.
2. Ensure the folder name is `var` (Github timestamps the download folder).
3. Enjoy.

## Usage

### Set

Passing a value directly as a parameter.

```
{{ var:color is="green" }}
{{ var:weather is="cold" }}
```

Passing a value as content of a tag pair. Useful if you want to store long values, e.g. HTML snippets. 

```
{{ var:color }}blue{{ /var:color }}
{{ var:weather }}sunny{{ /var:color }}
```

Setting both variable name and value as parameters. Useful when the variable name is dynamically generated.

```
{{ var:with name="color" is="blue" }}
{{ var:with name="weather" is="warm" }}
```

### Get

Getting a variable value is just as easy.

```
{{ var:color }}
{{ var:with name="color" }}
```

### Exists

You can check if a variable exists with `{{ var:exists name="foo" }}`.

```
{{ var:exists name="foo" }} // false

{{ var:foo is="bar" }}
{{ var:exists name="foo" }} // true
```

### Extract

You can extract variables into the current context. This is useful if you need to apply modifiers to variables for example.

```
{{ var:color is="green" }}
{{ var:weather is="warm" }}

{{ var:extract }}
	{{ color }} // green
	{{ weather|upper }} // WARM
{{ /var:extract }}
```

## Recipes

- [Create flexible layouts with reusable snippets](https://gist.github.com/michaelhue/dee700f0628ea1e15af9)