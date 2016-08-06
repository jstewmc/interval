<?php
/**
 * The file for the interval tests
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\Interval;

use Jstewmc\TestCase\TestCase;

/**
 * Tests for the interval class
 */
class IntervalTest extends TestCase
{
    /* !__construct() */
    
    /**
     * __construct() should return object if interval does not exist
     */
    public function testConstructIfIntervalDoesNotExist()
    {
        $interval = new Interval();
        
        $this->assertTrue($interval instanceof Interval);
    }
    
    /**
     * __construct() should return object if interval does exist
     */
    public function testConstructIfIntervalDoesExist()
    {
        $interval = new Interval('[0, 1]');
        
        $this->assertTrue($interval instanceof Interval);
        $this->assertTrue($this->getProperty('isLowerInclusive', $interval));
        $this->assertEquals(0, $this->getProperty('lower', $interval));
        $this->assertEquals(1, $this->getProperty('upper', $interval));
        $this->assertTrue($this->getProperty('isUpperInclusive', $interval));
        
        return;
    }
    
    
    /* !__toString() */
    
    /**
     * __toString() should return a string
     */
    public function testToStringReturnsString()
    {
        $interval = (new Interval())
            ->setIsLowerInclusive(false)
            ->setLower(1)
            ->setUpper(2)
            ->setIsUpperInclusive(true);
        
        $this->assertEquals('(1, 2]', (string) $interval);
        
        return;
    }
    
    
    /* !getIsLowerInclusive() */
    
    /**
     * getIsLowerInclusive() should return bool
     */
    public function testGetIsLowerInclusive()
    {
        $interval = new Interval();
        
        $this->setProperty('isLowerInclusive', $interval, true);
        
        $this->assertTrue($interval->getIsLowerInclusive());
        
        return;
    }
    
    
    /* !getIsUpperInclusive() */
    
    /**
     * getIsUpperInclusive() should return bool
     */
    public function testGetIsUpperInclusive()
    {
        $interval = new Interval();
        
        $this->setProperty('isUpperInclusive', $interval, true);
        
        $this->assertTrue($interval->getIsUpperInclusive());
        
        return;
    }
    
    
    /* !getLower() */
    
    /**
     * getLower() should return null if lower does not exist
     */
    public function testGetLowerReturnsNullIfLowerDoesNotExist()
    {
        return $this->assertNull((new Interval())->getLower());
    }
    
    /**
     * getLower() should return number if lower does exist
     */
    public function testGetLowerReturnsNumberIfLowerDoesExist()
    {
        $lower = 1;
        
        $interval = new Interval();
        
        $this->setProperty('lower', $interval, $lower);
        
        $this->assertEquals($lower, $interval->getLower());
        
        return;
    }
    
    
    /* !getSeparator() */
    
    /**
     * getSeparator() should return string
     */
    public function testGetSeparator()
    {
        return $this->assertTrue(is_string((new Interval())->getSeparator()));
    }
    
    
    /* !getUpper() */
    
    /**
     * getUpper() should return null if upper does not exist
     */
    public function testGetUpperReturnsNullIfUpperDoesNotExist()
    {
        return $this->assertNull((new Interval())->getUpper());
    }
    
    /**
     * getUpper() should return number if upper does exist
     */
    public function testGetUpperReturnsNumberIfUpperDoesExist()
    {
        $upper = 1;
        
        $interval = new Interval();
        
        $this->setProperty('upper', $interval, $upper);
        
        $this->assertEquals($upper, $interval->getUpper());
        
        return;
    }
    
    
    /* !compare() */
    
    /**
     * compare() should throw exception if $x is not a number
     */
    public function testCompareIfXIsNotNumber()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new Interval())->compare('foo');
        
        return;
    }
     
    /**
     * compare() should return int if $x is below interval
     */
    public function testCompareReturnsIntIfXIsBelowInterval()
    {
        $x = 1;
        
        $interval = (new Interval())->setLower(999)->setUpper(999 + 1);
        
        $this->assertEquals(-1, $interval->compare($x));
        
        return;
    }
    
    /**
     * compare() should return int if $x is above interval
     */
    public function testCompareReturnsIntIfXIsAboveInterval()
    {
        $x = 999;
        
        $interval = (new Interval())->setLower(0)->setUpper(0 + 1);
        
        $this->assertEquals(1, $interval->compare($x));
        
        return;
    }
    
    /**
     * compare() should return int if $x is inside interval
     */
    public function testCompareReturnsIntIfXIsInsideInterval()
    {
        $x = 0;
        
        $interval = (new Interval())->setLower(-999)->setUpper(+999);
        
        $this->assertEquals(0, $interval->compare($x));
        
        return;
    }
    
    /**
     * compare() should return int if $x is equal to exclusive lower bound
     */
    public function testCompareReturnsIntIfXIsEqualToExclusiveLowerBound()
    {
        $x = 1;
        
        $interval = (new Interval())
            ->setIsLowerInclusive(false)
            ->setLower(1)
            ->setUpper(1 + 1);
        
        $this->assertEquals(-1, $interval->compare($x));
        
        return;
    }
    
    /**
     * compare() should return int if $x is equal to inclusive lower bound
     */
    public function testCompareReturnsIntIfXIsEqualToInclusiveLowerBound()
    {
        $x = 1;
        
        $interval = (new Interval())
            ->setIsLowerInclusive(true)
            ->setLower(1)
            ->setUpper(1 + 1);
        
        $this->assertEquals(0, $interval->compare($x));
        
        return;
    }
    
    /**
     * compare() should return int if $x is equal to exclusive upper bound
     */
    public function testCompareReturnsIntIfXIsEqualToExclusiveUpperBound()
    {
        $x = 2;
        
        $interval = (new Interval())
            ->setLower(1)
            ->setUpper(1 + 1)
            ->setIsUpperInclusive(false);
        
        $this->assertEquals(1, $interval->compare($x));
        
        return;
    }
    
    /**
     * compare() should return int if $x is equal to exclusive upper bound
     */
    public function testCompareReturnsIntIfXIsEqualToInclusiveUpperBound()
    {
        $x = 2;
        
        $interval = (new Interval())
            ->setLower(1)
            ->setUpper(1 + 1)
            ->setIsUpperInclusive(true);
        
        $this->assertEquals(0, $interval->compare($x));
        
        return;
    }
    
    
    /* !parse() */
    
    /**
     * parse() should throw exception if interval is not valid
     */
    public function testParseThrowsExceptionIfIntervalIsNotValid()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new Interval())->parse('foo');
        
        return;
    }
    
    /**
     * parse() should throw exception if endpoints are invalid (e.g., out-of-order)
     */
    public function testParseThrowsExceptionIfEndpointsIsNotValid()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new Interval())->parse('[999, 1]');
        
        return;
    }
    
    /**
     * parse() should throw exception if boundaries are invalid (e.g., "[0,0)")
     */
    public function testParseThrowsExceptionIfBoundariesAreInvalid()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new Interval())->parse('[0, 0)');
        
        return;
    }
    
    /**
     * parse() should return self if interval is valid
     */
    public function testParseThrowsExceptionIfIntervalIsValid()
    {
        $expected = (new Interval())
            ->setIsLowerInclusive(true)
            ->setLower(1)
            ->setUpper(2)
            ->setIsUpperInclusive(false);
        
        $actual = (new Interval())->parse('[1, 2)');
        
        $this->assertEquals($expected, $actual);
        
        return;
    }
    
    
    /* !setIsLowerInclusive() */
    
    /**
     * setIsLowerInclusive() should return self
     */
    public function testSetIsLowerInclusive()
    {   
        $interval = new Interval();
        
        $this->assertSame($interval, $interval->setIsLowerInclusive(true));
        $this->assertTrue($this->getProperty('isLowerInclusive', $interval));
        
        return;
    }
    
    
    /* !setIsUpperInclusive() */
    
    /**
     * setIsUpperInclusive() should return self
     */
    public function testSetIsUpperInclusive()
    {
        $interval = new Interval();
        
        $this->assertSame($interval, $interval->setIsUpperInclusive(true));
        $this->assertTrue($this->getProperty('isUpperInclusive', $interval));
        
        return;
    }
    
    
    /* !setLower() */
    
    /**
     * setLower() should throw exception if $lower is not a number
     */
    public function testSetLowerIfLowerIsNotNumber()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new Interval())->setLower('foo');
        
        return;
    }
    
    /**
     * setLower() should return self
     */
    public function testSetLowerIfLowerIsNumber()
    {
        $lower = 1;
        
        $interval = new Interval();
        
        $this->assertSame($interval, $interval->setLower($lower));
        $this->assertEquals($lower, $this->getProperty('lower', $interval));
        
        return;
    }
    
    /* !setSeparator() */
    
    /**
     * setSeparator() should return self
     */
    public function testSetSeparator()
    {
        $separator = 'foo';
        
        $interval = new Interval();
        
        $this->assertSame($interval, $interval->setSeparator($separator));
        $this->assertEquals($separator, $this->getProperty('separator', $interval));
        
        return;
    }
    
    
    /* !setUpper() */
    
    /**
     * setUpper() should throw exception if $upper is not a number
     */
    public function testSetUpperIfUpperIsNotNumber()
    {
        $this->setExpectedException('InvalidArgumentException');
        
        (new Interval())->setUpper('foo');
        
        return;
    }
    
    /**
     * setUpper() should return self if $upper is number
     */
    public function testSetUpperIfUpperIsNumber()
    {
        $upper = 1;
        
        $interval = new Interval();
        
        $this->assertSame($interval, $interval->setUpper($upper));
        $this->assertEquals($upper, $this->getProperty('upper', $interval));
        
        return;
    }
}
