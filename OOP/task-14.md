# OOP in PHP

## Class vs Object

`Class` in OOP is the blueprint of the object, it has the attributes and behaviors of all the objects that are created from this class.

`Object` is the instance that is from the class, it has its own characteristics.

Real world example
```
Apple
- Class         = Apple blueprint design
- Object        = iPhones that China made
- Application   = Apple store
```

We can convert the above example to a code like that:
```php
class AppleDevice { // Class
    // Properties (NOT variables, Properties)
    public $ram;
    public $inch;
    public $space;
    public $color;

    // Methods (NOT functions, methods)
    public function doublePress(){
        echo "Dawly Dawly";
    }
}

$iphone17ProMax = new AppleDevice(); // Object
```

- We can edit any property of the object using object operator.
- We can also add a property for the object that is not in the class and it will be for this object only.
```php
$iphone17ProMax->color = 'red';
$iphone17ProMax->notch = false; // Property for this object only
$iphone17ProMax->doublePress(); // Method call (Output: Dawly Dawly)
```

## $this
- $this: it is a pseudo variable that refers to the object properties
Example for how can we describe it
```php
class AppleDevice {
    public $owner;

    public function setOwner(){
        if (strlen($this->owner) < 3) {
            echo 'Invalid name, Too short';
        } else {
            echo 'status: 200';
        }
    }
    // Note: this is not the way a property value got set.
}

$iphone17ProMax = new AppleDevice();
$iphone17ProMax->owner = 'Ahmed';
$iphone17ProMax->setOwner(); // Output => status: 200
/* 
    Notice how this function test every object according to its own property value
    This is because $this refers to the owner of the object that calls the function
*/
```

## Self

- If I need to add a constant in the class for any feature I want to add like adding a constant represents the minimum number of characters needed to set the owner name we do it like that

```php
class AppleDevice {
    public $owner;
    const OWNERNAME = 3;

    public function setOwner(){
        if (strlen($this->owner) < self::OWNERNAME) {
            echo 'Invalid name, Too short';
        } else {
            echo 'status: 200';
        }
    }
    // Note: this is not the way a property value got set.
}

// Calling the constant outside the class has 2 ways
$iphone17ProMax = new AppleDevice();
echo AppleDevice::OWNERNAME;
echo $iphone17ProMax::OWNERNAME;
```

## self vs $this

### self
- Refer to current class
- Access static members
- Doesn't use $ because it does not represent a variable, but represent class construction

### $this
- Refer to current object
- Access non sttic members
- Does use $ because it represents a variable