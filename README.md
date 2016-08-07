# interval
A set of numbers between two endpoints.

```php
use Jstewmc\Interval;

// create an interval between 2 (exclusive) and 4 (inclusive)
$interval = new Interval('(2, 4]');

// compare values
$interval->compare(1);  // returns -1
$interval->compare(2);  // returns -1
$interval->compare(3);  // returns 0
$interval->compare(4);  // returns 0
$interval->compare(5);  // returns 1

// echo the interval
echo $interval;  // returns "(2, 4]"
```

## Syntax

This library supports standard US interval syntax requiring (in order): 

* an open-bracket (`[`) or open-parentheses (`(`); 
* a number, positive infinity (`INF`), or negative infinity (`-INF`);
* a comma-space (`, `);
* a number, positive infinity (`INF`), or negative infinity (`-INF`); and,
* a close-bracket (`]`) or close-parentheses (`)`). 

Positive or negative floats, integers, and infinity are accepted.

For example:

* `(2, 4]` represents 2 < _x_ <= 4
* `(-10.5, 10.5)` represents -10.5 < _x_ < 10.5
* `[0, INF)` represents 0 <= _x_ < &infin;
* `(-INF, INF)` represents -&infin; < _x_ < &infin;

Keep in mind, this library does not support _reverse-bracket_ syntax (e.g., `]2, 4]`) or _semi-colon separated_ syntax (e.g., `[2; 4]`).

## Usage

You can instantiate an interval from a string or create it manually using the set methods:

```php
use Jstewmc\Interval;

$a = new Interval('(2, 4]');

$b = (new Interval())
    ->setLowerExclusive()
    ->setLower(2)
    ->setUpper(4)
    ->setUpperInclusive();

$a == $b;  // returns true
```

Keep in mind, when instantiating an interval from a string, an `InvalidArgumentException` will be thrown if the interval's syntax is invalid:

```php
use Jstewmc\Interval;

new Interval('[0; 0]');      // throws exception (semicolon syntax not supported)
new Interval(']0, 2]');      // throws exception (reverse brackets not supported)
new Interval('[foo, bar]');  // throws exception (use numbers or INF)
new Interval('[0, 0)');      // throws exception (same endpoint, different boundary)
new Interval('[1, -1]');     // throws exception (upper- is less than lower-bound)
```

Infinity is supported as the string `'INF'` or the `INF` [predefined constant](http://php.net/manual/en/math.constants.php):

```php
use Jstewmc\Interval;

$a = new Interval('(-INF, 0]');

$b = (new Interval())
    ->setLowerExclusive()
    ->setLower(-INF)
    ->setUpper(0)
    ->setUpperInclusive(true);

$a == $b;  // returns true
```

You can compare a value to the interval using the `compare()` method. The `compare()` method will return `-1`, `0`, or `1` if the value is _below_, _inside_, or _above_ the interval, respectively:

```php
use Jstewmc\Interval;

$interval = new Interval('(2, 4]');

$interval->compare(1);  // returns -1
$interval->compare(2);  // returns -1
$interval->compare(3);  // returns 0
$interval->compare(4);  // returns 0
$interval->compare(5);  // returns 1
```

You can get any of the interval's settings with the get methods:

```php
use Jstewmc\Interval;

$interval = new Interval('(2, 4]');

$interval->getIsLowerInclusive();  // returns false
$interval->getLower();             // returns 2
$interval->getUpper();             // returns 4
$interval->getIsUpperInclusive();  // returns true
```

There are a few convenience methods to make getting and setting the boundaries eaiser:

```php
use Jstewmc\Interval;

$interval = new Interval('(2, 4]');

$interval->isLowerInclusive();  // returns false
$interval->isLowerExclusive();  // returns true
$interval->isUpperInclusive();  // returns true
$interval->isUpperExclusive();  // returns false

echo $interval->setLowerInclusive();  // prints "[2, 4]"
echo $interval->setLowerExclusive();  // prints "(2, 4]"
echo $interval->setUpperInclusive();  // prints "(2, 4]"
echo $interval->setUpperExclusive();  // prints "(2, 4)"
```


That's about it!

## License

[MIT](https://github.com/jstewmc/interval/blob/master/LICENSE)

## Author

[Jack Clayton](mailto:clayjs0@gmail.com)

## Version

### 0.2.0, August 7, 2016

* Add support for infinity (e.g., `'(-INF, 0]'`).
* Update error messages to be a little more informative.
* Update README examples.

### 0.1.0, August 6, 2016 

* Initial release
