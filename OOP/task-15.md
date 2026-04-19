# OOP in PHP (Part 2)

## Inheritance

Inheritance means a child class can reuse properties and methods from a parent class.

Main benefit:
- Reuse code instead of writing the same logic again
- Organize related classes in a clean way

Simple example:
```php
class AppleDevice {
	public $owner;
	public $color;

	public function ring(){
		echo "Ringing...";
	}
}

class IPhone extends AppleDevice {
	public $camera;
}

$iphone = new IPhone();
$iphone->owner = "Ahmed";
$iphone->ring(); // From parent class
```

## final Keyword

If you put `final` before a class:
- No class can inherit from it

If you put `final` before a method:
- Child class cannot override this method

Example:
```php
final class AppleDevice {
	public function info(){
		echo "Apple Device";
	}
}

// class IPhone extends AppleDevice {} // Error: cannot extend final class
```

```php
class AppleDevice {
	final public function serial(){
		echo "SN-123";
	}
}

class IPhone extends AppleDevice {
	// public function serial(){} // Error: cannot override final method
}
```

Why use `final`:
- Protect important logic from being changed by mistake
- Keep behavior fixed in all child classes

## Overriding Methods

Override means child class writes its own version of a parent method.

Example:
```php
class AppleDevice {
	public function boot(){
		echo "Apple device is booting";
	}
}

class IPhone extends AppleDevice {
	public function boot(){
		echo "iPhone boot animation";
	}
}

$iphone = new IPhone();
$iphone->boot(); // Output: iPhone boot animation
```

Call original parent method from child using `parent::`:
```php
class IPhone extends AppleDevice {
	public function boot(){
		parent::boot();
		echo " + Face ID ready";
	}
}
```

## Abstract Class vs Interface

Both are templates, but there is a difference:

Abstract Class:
- Can contain implemented methods + abstract methods
- Class uses `extends` to inherit it

Interface:
- Contains method signatures only (no method body)
- Class uses `implements`

Example:
```php
abstract class AppleDevice {
	public function logo(){
		echo "Apple Logo";
	}

	abstract public function unlock();
}

interface WirelessCharge {
	public function charge();
}

interface FaceID {
	public function scanFace();
}

class IPhone extends AppleDevice implements WirelessCharge, FaceID {
	public function unlock(){
		echo "Unlocked";
	}

	public function charge(){
		echo "Charging wirelessly";
	}

	public function scanFace(){
		echo "Face scanned";
	}
}
```

Can a class implement multiple interfaces?
- Yes, one class can implement many interfaces

## Polymorphism

Polymorphism means same method name, different behavior based on object type.

Simple example:
```php
class AppleDevice {
	public function openApp(){
		echo "Opening app in default Apple way";
	}
}

class IPhone extends AppleDevice {
	public function openApp(){
		echo "Opening app on iPhone";
	}
}

class IPad extends AppleDevice {
	public function openApp(){
		echo "Opening app on iPad split screen";
	}
}

function runDevice(AppleDevice $device){
	$device->openApp();
}

runDevice(new IPhone()); // Opening app on iPhone
runDevice(new IPad());   // Opening app on iPad split screen
```

Same method (`openApp`) but each object does it in its own way.
