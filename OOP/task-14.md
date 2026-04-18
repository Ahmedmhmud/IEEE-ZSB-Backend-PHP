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

## Access Modifiers (Encapsulation)

Access modifiers control who can access a property or method.

### public
- Can be accessed from anywhere (inside or outside the class)
- No restrictions

### protected
- Can be accessed only inside the class and by child classes (inheritance)
- Not accessible from outside

### private
- Can be accessed only inside the class where it is defined
- Most restrictive access level

**Example: Why make a property private?**

```php
class AppleDevice {
    public $owner;
    private $price; // Private to prevent direct modification
    
    public function setPrice($newPrice) {
        if ($newPrice > 0) {
            $this->price = $newPrice;
            echo "Price set to: $newPrice";
        } else {
            echo "Invalid price!";
        }
    }
    
    public function getPrice() {
        return $this->price;
    }
}

$iphone = new AppleDevice();
$iphone->setPrice(999); // Output: Price set to: 999
echo $iphone->getPrice(); // Output: 999
$iphone->price = -500; // Error! Cannot access private property
```

**Why private is useful:** It prevents someone from setting the price to a negative number. The `setPrice()` method validates before changing the price.

## Typed Properties

Typed properties allow you to specify what type of value a property must hold. They were introduced in PHP 7.4.

```php
class AppleDevice {
    public string $owner;          // Must be a string
    public int $inch;              // Must be an integer (screen size)
    public float $price;           // Must be a float
    public array $colors;          // Must be an array (available colors)
    public ?string $serial;        // String or null (? means nullable)
    
    public function getSpecs() {
        echo "Owner: {$this->owner} - Screen: {$this->inch}inch - Price: ${$this->price}";
    }
}

$iphone = new AppleDevice();
$iphone->owner = "Ahmed";          // OK
$iphone->inch = 6;                 // OK
$iphone->colors = ["Red", "Blue"]; // OK
$iphone->price = 999.99;           // OK
$iphone->inch = "Six";             // Error! Type mismatch
```

**Benefits of typed properties:**
- Catches bugs early (before runtime)
- Makes code more readable (clear what type each property should be)
- Prevents unexpected type conversions
- IDE can provide better autocomplete and error detection

## Constructor Methods

The `__construct()` method is a special method that runs automatically when you create a new object.

```php
class AppleDevice {
    public string $owner;
    public int $inch;
    public string $color;
    
    // Constructor method
    public function __construct($owner, $inch, $color) {
        $this->owner = $owner;
        $this->inch = $inch;
        $this->color = $color;
    }
    
    public function describe() {
        echo "Device owner: {$this->owner} - Screen: {$this->inch}inch - Color: {$this->color}";
    }
}

$iphone = new AppleDevice("Ahmed", 6, "Red"); // Constructor runs automatically
$iphone->describe(); // Output: Device owner: Ahmed - Screen: 6inch - Color: Red
```

**Why constructors are useful:**
- Initialize properties automatically when creating an object
- Ensures object starts with required values
- Reduces code repetition (no need to set properties after creating object)
- Validates initial data before the object is ready to use

```php
class AppleDevice {
    public string $owner;
    public int $inch;
    
    public function __construct($owner, $inch) {
        if ($inch < 4 || $inch > 7) {
            throw new Exception("Invalid screen size");
        }
        $this->owner = $owner;
        $this->inch = $inch;
    }
}

$device1 = new AppleDevice("Ahmed", 6);  // Valid
$device2 = new AppleDevice("Ali", 10);   // Error! Screen size out of range
```