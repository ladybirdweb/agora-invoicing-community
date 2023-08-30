[![npm version](https://img.shields.io/npm/v/phpunserialize.svg?style=flat)](https://www.npmjs.com/package/phpunserialize)

phpUnserialize
==============

Convert serialized PHP data to a javascript object graph.


Why?
----
> OMG why would anyone do something this perverse? PHP has a `json_encode()`
> method so you don't have to try and cobble together ugly hacks like this.

It all started so innocently. The guy at the desk next to mine asked "hey is
there a javascript library that can turn this php serialize mess into
something that I can read?" I gaped. He explained that he was trying to slap
together a js testing harness for a set of REST services that returned
serialized PHP as their transport representation.

A [google search][] turned up [something][] so I went back to listening to the
latest [OMM][] album. Fifteen minutes later the stream of curses coming from
Gallilama started harshing my groove. It turns out that the venerable phpjs
function only handles a particular subset of PHP's `serialize` output.
Specifically it doesn't handle references and objects at all. Google found
a [java implementation][] that looked more complete. I did a quick port of it
to javascript and moved on to my [$wingin' Utter$][] playlist.

The next day I checked in and found out that strange things were afoot with my
port. It turns out that private and protected members `serialize` in an
"interesting" way. PHP prepends the member name with either the class name
(private) or an asterisk (protected) surrounded by null bytes (\u0000). The
hack parser was going into an infinite loop when it tried to extract these
values.

By this point I was fully committed. Nothing less than a TDD validated library
that could handle just about any craziness I threw at it would do. I'm sure
there are still gaps, but this "quick hack" is working for our twisted needs.


Implementation Details
----------------------
PHP's serialization format is not well documented, but this function takes
a best guess approach to parsing and interpreting it. Serialized integers,
floats, booleans, strings, arrays, objects and references are currently
supported.

PHP's array type is a hybrid of javascript's array and object types.
phpUnserialize translates PHP arrays having only 0-based consecutive numeric
keys into javascript arrays. All other arrays are translated into javascript
objects.

Serialized members of a PHP object carry scope information via name mangling.
`phpUnserialize` strips this scope signifier prefix from private and protected
members.

Check out the [tests][] for more details or read the source.


Usage
-----
The `phpUnserialize.js` file implements the [Universal Module Definition][]
pattern which attempts to be compatible with multiple script loaders including
[AMD][], [CommonJS][] and direct usage in an HTML file.

Plain HTML:
```html
<script src="phpUnserialize.js"></script>
<script>
  var foo = phpUnserialize('s:3:"foo";');
</script>
```

With an [AMD][] loader:
```javascript
define(["phpunserialize"], function (phpUnserialize) {
  return {
    foo: phpUnserialize('s:3:"foo";')
  };
});
```

With a [CommonJS][] loader:
```javascript
var phpUnserialize = require('phpunserialize');
var foo = phpUnserialize('s:3:"foo";');
```

Running the Unit Tests
----------------------
```sh
npm install
npm test
```

---
[google search]: https://www.google.com/search?q=php+unserialize+javascript
[something]: http://phpjs.org/functions/unserialize/
[OMM]: http://www.oldmanmarkley.com/
[java implementation]: https://code.google.com/p/serialized-php-parser
[$wingin' Utter$]: http://swinginutters.com/
[tests]: php-unserialize.spec.js
[Universal Module Definition]: https://github.com/umdjs/umd
[AMD]: https://github.com/amdjs/amdjs-api/blob/master/AMD.md
[CommonJS]: http://wiki.commonjs.org/wiki/CommonJS
