# OOP in PHP (Part 3)

## Magic methods
- Methods that start with 2 underscores in the beginning of its name

 **__construct()**
  - Called when object is created
  - Can be inherited

 **__destruct()**
  - Called when object is destroyed

 **__call($method, $params)**
  - Called when a method that is not found or inaccessable is called
  - `$method` represents the name of the function called
  - `$params` represents the parameters of the called function

**__set($property, $value)**
 - Called when setting a property that is not found or inaccessable
 - `$property` represents the name of the property
 - `$value` represents the value of the property

**__get($property)**

- Called when getting a property that is not found or inaccessable

**__clone()**

- Used to ensure that the nested objects don't share the same memory when copying

```php
class IPhone {
    public $ram;
    public $storage;

    public function __construct($r, $s) {
        $this->ram = $r;
        $this->storage = $s;
    }
}

$iphone17 = new IPhone("2GB", "256GB");
```
## clone
- Used when you want to copy an object but in another memory location
- Prevents calling by reference by shallow copying
- The problem appears when you clone an object that has another object as a property
 - this nested object will share the same memory for the 2 objects
 - This problem can be solved with `__clone()`

```php
class Ram {
    public $size;
}

class Iphone {
    public $name;
    public $ram  = new Ram();

    public function __construct($n) {
        $this->name = $n;
    }

    public function __clone() {
        $this->name = clone $this->name;
        $this->ram  = clone $this->ram;
    }
}

$iphone11 = new Iphone("11");
$iphone12 = $iphone11;
$iphone12->name = "12";
// Now the two objects have different names
```

## static keyword
- Static properties or methods are specified to the class not to the object
- You don't need an object to access them
- They are accessed by resolution scope operator.
- An object can not access a static property, but can access a static method
- It keeps its value fixed if not updated all the time

```php
class Iphone {
    public static $ram;

    public static function sayHello() {
        echo "Hello, Dawly";
    }
}

Iphone::$ram = "2GB";
Iphone::sayHello();
$iphone = new Iphone();
$iphone->sayHello(); // Valid, Dawly
```

## Method chaining

- Used to call more than one method at the same time

```php
class Iphone {
    public function sayHello() {
        echo "Hello";
        return $this;
    }

    public function selectLanguage() {
        echo "EN or AR";
        return $this;
    }
}

$phone = new Iphone();
$phone->sayHello()->selectLanguage(); // Method chaining
```

## Traits
- In PHP, you can't apply multiple inheritance, but you can use traits to inherit methods & properties from multiple resources
- Can't be instantiated
- Can't extend or implement
- It supports class and can have methods
- It has priority over the class

```php
trait FingerPrint {
    public $timeDelay;
}

trait FaceCam {
    public $resolution;
    public function sayHello() {
        echo "Hello from trait";
    }
}

trait AllFeatures {
    use FingerPrint, FaceCam;
}

class Iphone {
    use FingerPrint;
    public function sayHello() {
        echo "Hello from class";
    }
}

class IphonePro extends Iphone {
    use AllFeatures;
}

$iphone = new IphonePro();
$iphone->sayHello();
/**
 * Output:
 * Hello from trait
 * Hello from class
 * /
```

**Solving naming collisions in Traits**
- We can solve it by defining priorities and alias from the simillar functions

```php
trait trait1 {
    public function feature() {
        // TODO
    }
}

trait trait2 {
    public function feature() {
        // TODO
    }
}

class Iphone {
    use trait1, trait2 {
        trait1::feature insteadof trait2;
        trait2::feature as featureOfTrail2;
    }

    /**
     * The benifit of this that we now have methods from
     * different trails which was impossible to achieve
     * with multiple inheritance in classes as PHP doesn't
     * support it 
     */
}

$iphone = new Iphone();
$iphone->feature; // Of trail1
$iphone->featureOfTrail2;
```

## Namespace

- Used to organize classes, functions, and constants and avoid naming collisions
- If 2 files have the same class/function name, namespace makes each one unique
- You can import from another namespace using `use`
- Best practice is one namespace per file

```php
namespace Apple;

class Phone {
    public function info() {
        echo "Apple phone";
    }
}

function createPhone() {
    return new Phone();
}

namespace Store;

use Apple\Phone;
use function Apple\createPhone;

$phone1 = new Phone();
$phone2 = createPhone();
```


## Autoload classes
- You can use `spl_autoload_register()` if you want to autoload the used classes in the file, regardless the amount of classes
- The class should be the same name of the file you want to require to use its class

```php
require 'classes/Testing1.php'
require 'classes/Testing2.php'
require 'classes/Testing3.php'
// Notice how it is frustrating to require every class you are using

// Instead do:
spl_autoload_register(function($class) {
    require 'classes' . $class . '.php';
});

$test = new Testing1();
```