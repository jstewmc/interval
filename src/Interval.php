<?php
/**
 * The file for an interval
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\Interval;

use InvalidArgumentException;

/**
 * A numeric interval
 * 
 * @since  0.1.0
 * @since  0.2.0  add support for infinity
 */
class Interval
{
    /* !Constants */
    
    /**
     * @var    string  the placeholder for positive infinity
     * @since  0.2.0
     */
    const INFINITY_POSITIVE = 'INF';
    
    /**
     * @var    string  the placeholder for negative infinity
     * @since  0.2.0
     */
    const INFINITY_NEGATIVE = '-INF';
    
    
    /* !Private properties */
    
    /**
     * @var    bool  a flag indicating whether or not the lower-bound is inclusive
     *     (optional; defaults to false)
     * @since  0.1.0
     */
    private $isLowerInclusive = false;
    
    /**
     * @var    bool  a flag indicating whether or not the upper-bound is inclusive
     *     (optional; defaults to false)
     * @since  0.1.0
     */
    private $isUpperInclusive = false;
    
    /**
     * @var    mixed  the interval's lower bound
     * @since  0.1.0
     */
    private $lower;
    
    /**
     * @var    string  the interval's separator
     * @since  0.1.0
     */
    private $separator = ', ';
    
    /**
     * @var    mixed  the interval's upper bound
     * @since  0.1.0
     */
    private $upper;


    /* !Magic methods */
    
    /**
     * Called when the interval is constructed
     *
     * @param  string|null  $string  the interval (e.g., "[0, 2]") (optional)
     * @since  0.1.0
     */
    public function __construct(string $interval = null) 
    {
        if ($interval !== null) {
            $this->parse($interval);
        }
    }
    
    /**
     * Called when the interval is treated like a string
     *
     * @return  string
     * @since   0.1.0
     * @since   0.2.0  add support for infinity
     */
    public function __toString(): string
    {
        $string = '';
        
        // append the lower boundary
        $string .= $this->isLowerInclusive ? '[' : '(';
        
        // get the endpoints as an array
        $endpoints = [$this->lower, $this->upper];
        
        // loop through the endpoints
        foreach ($endpoints as &$endpoint) {
            // get the value as a string based on its value
            if ($endpoint === -INF) {
                $endpoint = self::INFINITY_NEGATIVE;
            } elseif ($endpoint === INF) {
                $endpoint = self::INFINITY_POSITIVE;
            } else {
                $endpoint = (string) $endpoint;
            }
        }
        
        // append the lower bound, separator, and upper bound
        $string .= implode($this->separator, $endpoints);
        
        // append the upper boundary
        $string .= $this->isUpperInclusive ? ']' : ')';
        
        return $string;
    }
    
    
    /* !Get methods */
    
    /**
     * Returns the interval's is-lower-inclusive flag
     *
     * @return  bool
     * @since   0.1.0
     */
    public function getIsLowerInclusive(): bool
    {
        return $this->isLowerInclusive;
    }
    
    /**
     * Returns the interval's is-upper-inclusive flag
     *
     * @return  bool
     * @since   0.1.0
     */
    public function getIsUpperInclusive(): bool
    {
        return $this->isUpperInclusive;
    }
    
    /**
     * Returns the interval's lower value
     *
     * @return  int|float
     * @since   0.1.0
     */
    public function getLower()
    {
        return $this->lower;
    }
    
    /**
     * Returns the interval's separator
     *
     * @return  string
     * @since   0.1.0
     */
    public function getSeparator(): string
    {
        return $this->separator;
    }
    
    /**
     * Returns the interval's lower bound
     *
     * @return  int|float
     * @since   0.1.0
     */
    public function getUpper()
    {
        return $this->upper;
    }
    
    
    /* !Set methods */
    
    /**
     * Sets the interval's is-lower-inclusive flag
     *
     * @param   bool  $isLowerInclusive
     * @return  self
     * @since   0.1.0
     */
    public function setIsLowerInclusive(bool $isLowerInclusive): self
    {
        $this->isLowerInclusive = $isLowerInclusive;
        
        return $this;
    }
    
    /**
     * Sets the interval's is-upper-inclusive flag
     *
     * @param   bool  $isUpperInclusive
     * @return  self
     * @since   0.1.0
     */
    public function setIsUpperInclusive(bool $isUpperInclusive): self
    {
        $this->isUpperInclusive = $isUpperInclusive;
        
        return $this;
    }
    
    /**
     * Sets the interval's lower value
     *
     * @param   int|float  $lower
     * @return  self
     * @throws  InvalidArgumentException  if $lower is not a number
     * @since   0.1.0
     */
    public function setLower($lower): self
    {
        // if $lower is not a number, short-circuit
        if ( ! is_numeric($lower)) {
            throw new InvalidArgumentException(
                __METHOD__ . "() expects parameter one, lower, to be a number"
            );
        }
        
        $this->lower = +$lower;
        
        return $this;
    }
    
    /**
     * Sets the interval's separator
     *
     * @param   string  $separator
     * @return  self
     * @since   0.1.0
     */
    public function setSeparator(string $separator): self
    {
        $this->separator = $separator;
        
        return $this;
    }
    
    /**
     * Sets the interval's upper value
     *
     * @param   int|float  $upper
     * @return  self
     * @throws  InvalidArgumentException  if $upper is not a number
     * @since   0.1.0
     */
    public function setUpper($upper): self
    {
        if ( ! is_numeric($upper)) {
            throw new InvalidArgumentException(
                __METHOD__ . "() expects parameter one, upper, to be a number"
            );    
        }
        
        $this->upper = $upper;
        
        return $this;
    }
    
    
    /* !Public methods */
    
    /**
     * Compare a value to the interval
     *
     * If $x is below the interval, I'll return -1. If $x is above the interval, I'll
     * return 1. Otherwise, $x is inside the interval, and I'll return 0.
     *
     * @param   int|float  $x  the value to compare
     * @return  int 
     */
    public function compare($x): int
    {
        // if $x is not numeric, short-circuit
        if ( ! is_numeric($x)) {
            throw new InvalidArgumentException(
                __METHOD__ . "() expects parameter one, x, to be a number"
            );    
        }
        
        if (
            $x < $this->lower 
            || ( ! $this->isLowerInclusive && $x == $this->lower)
        ) {
            return -1;
        } elseif (
            $x > $this->upper 
            || ( ! $this->isUpperInclusive && $x == $this->upper)
        ) {
            return 1;
        } else {
            return 0;
        }
    }
    
    /**
     * Returns true if the lower-bound is exclusive
     *
     * @return  bool
     * @since   0.1.0
     */
    public function isLowerExclusive(): bool
    {
        return ! $this->isLowerInclusive;
    }
    
    /**
     * Returns true if the lower-bound is inclusive
     *
     * @return  bool
     * @since   0.1.0
     */
    public function isLowerInclusive(): bool
    {
        return $this->isLowerInclusive;
    }
   
    /**
     * Returns true if the upper-bound is exclusive
     *
     * @return  bool
     * @since   0.1.0
     */
    public function isUpperExclusive(): bool
    {
        return ! $this->isUpperInclusive;
    }
     
    /**
     * Returns true if the upper-bound is inclusive
     *
     * @return  bool
     * @since   0.1.0
     */
    public function isUpperInclusive(): bool
    {
        return $this->isUpperInclusive;
    }
    
    /**
     * Parses an interval string
     *
     * @param   string  $string  the string to parse
     * @return  self
     * @throws  InvalidArgumentException  if $string is not a valid interval
     * @throws  InvalidArgumentException  if the upper is greater than lower
     * @throws  InvalidArgumentException  if the endpoints and equal but the 
     *     boundaries are not equal (e.g., (1, 1])
     * @since   0.1.0
     * @since   0.2.0  add support for infinity
     */
    public function parse(string $string): self
    {
        // if the $string is not valid interval, short-circuit
        $pattern = '/^[\[\(]-?(\d*[\.]?\d+|INF), -?(\d*[\.]?\d+|INF)[\]\)]$/';
        if ( ! preg_match($pattern, $string)) {
            throw new InvalidArgumentException(
                __METHOD__ . "() expects parameter one, string, to be a valid "
                    . "interval; see the README for details"
            );
        }
        
        // otherwise, check the boundaries
        $this->isLowerInclusive = substr($string, 0, 1) === '[';
        $this->isUpperInclusive = substr($string, -1, 1) === ']';
        
        // get the endpoints
        $endpoints = explode($this->separator, substr($string, 1, -1));
        
        // loop through the endpoints
        foreach ($endpoints as &$endpoint) {
            // if the endpoint is negative or positive infinity, convert the value 
            //     to the PHP constant; otherwise, cast the value to int or float
            //
            if ($endpoint === self::INFINITY_NEGATIVE) {
                $endpoint = -INF;
            } elseif ($endpoint === self::INFINITY_POSITIVE) {
                $endpoint = INF;
            } else {
                $endpoint = +$endpoint;
            }    
        }
        
        // if the endpoints are out of order, short-circuit
        if ($endpoints[1] < $endpoints[0]) {
            throw new InvalidArgumentException(
                __METHOD__ . "() expects parameter one, string, to be a valid "
                    . "interval, however, the upper bound appears to be greater "
                    . "than the lower bound"
            );
        }
        
        // if the endpoints are equal, the boundaries must match
        if (
            $endpoints[0] == $endpoints[1] 
            && $this->isLowerInclusive !== $this->isUpperInclusive
        ) {
            throw new InvalidArgumentException(
                __METHOD__ . "() expects parameter one, string, to be a valid "
                    . "interval, however, the endpoints are the same but the "
                    . "boundaries are different"
            );
        }
        
        // otherwise, set the endpoints
        $this->lower = $endpoints[0];
        $this->upper = $endpoints[1];
        
        return $this;
    }
    
    /**
     * Sets the is-lower-inclusive flag to false
     *
     * @return  self
     * @since   0.1.0
     */
    public function setLowerExclusive(): self
    {
        $this->isLowerInclusive = false;
        
        return $this;
    }
    
    /**
     * Sets the is-lower-inclusive flag to true
     *
     * @return  self
     * @since   0.1.0
     */
    public function setLowerInclusive(): self
    {
        $this->isLowerInclusive = true;
        
        return $this;
    }
    
    /**
     * Sets the is-upper-inclusive flag to false
     *
     * @return  self
     * @since   0.1.0
     */
    public function setUpperExclusive(): self
    {
        $this->isUpperInclusive = false;
        
        return $this;
    }
    
    /**
     * Sets the is-upper-inclusive flag to true
     *
     * @return  self
     * @since   0.1.0
     */
    public function setUpperInclusive(): self
    {
        $this->isUpperInclusive = true;
        
        return $this;
    }
}
