# interval
A set of numbers between two endpoints.

```php
use Jstewmc\Interval;

// create an between 2 (exclusive) and 4 (inclusive)
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

This library supports standard US interval syntax (e.g., `(2, 4]`). The first character MUST be an open-bracket (`[`) or open-parentheses (`(`); the last character MUST be a close-bracket (`]`) or close-parentheses (`)`); and, in-between MUST be two numbers separated by a comma-space (e.g., `, `). Positive or negative floats or integers are accepted.

On the other hand, this library does not support a few things:

* It does not support _infinity_. 
* It does not support _reverse-bracket_ syntax (e.g., `]2, 4]`).
* It does not support _semi-colon separated_ syntax (e.g., `[1.000; 2.000]`).

## Usage

You can instantiate an interval from a string or create it manually using the set methods:

```php
use Jstewmc\Interval;

$a = new Interval('(2, 4]');

$b = (new Interval())
    ->setIsLowerInclusive(false)
    ->setLower(2)
    ->setUpper(4)
    ->setIsUpperInclusive(true);

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

That's about it!

## License

[MIT](https://github.com/jstewmc/interval/blob/master/LICENSE)

## Author

[Jack Clayton](mailto:clayjs0@gmail.com)

## Version

### 0.1.0, August 6, 2016 

* Initial release
