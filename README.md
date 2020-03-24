prosp
===
Processing speed measurement library for PHP.

# Description
I Googled "How to processing time measure with PHP".<br/>
I always forgot how to do it, so I made it a library.

In this library, you can measure the processing time in milliseconds by putting the start and end calls in the processing.<br/>

# Install

```$xslt
$ composer require jun3453/prosp
```

# Usage
## Example
```php
for($i=0;$i<2;$i++) {
	ProcessingSpeedMeasurement::startProcessing("foo");
	/** Write foo processing */

	for($j=0;$j<2;$j++) {
		ProcessingSpeedMeasurement::startProcessing("bar");
		/** Write bar processing  */
		ProcessingSpeedMeasurement::endProcessing("bar");
	}

	ProcessingSpeedMeasurement::endProcessing("foo");
}
```
This result is converted to milliseconds.

#### Get specified context result.
`ProcessingSpeedMeasurement::getProcessingResultByContext("foo")`
```$xslt
array(2) {
  'processTimes' =>
  array(2) {
    [1] =>
    double(0.43487548828125)
    [2] =>
    double(0.08082389831543)
  }
  'totalRounds' =>
  int(2)
}
```

#### Get all context result.
`ProcessingSpeedMeasurement::getAllProcessingResults()`
```
array(2) {
  'bar' =>
  array(2) {
    'processTimes' =>
    array(4) {
      [1] =>
      double(0.042915344238281)
      [2] =>
      double(0.25415420532227)
      [3] =>
      double(0.016927719116211)
      [4] =>
      double(0.016927719116211)
    }
    'totalRounds' =>
    int(4)
  }
  'foo' =>
  array(2) {
    'processTimes' =>
    array(2) {
      [1] =>
      double(0.43487548828125)
      [2] =>
      double(0.08082389831543)
    }
    'totalRounds' =>
    int(2)
  }
}
```

#### Get all context longer than specified milliseconds.
`ProcessingSpeedMeasurement::getResultsLongerThanSpecifiedMs(0.04)`
```
array(2) {
  'bar' =>
  array(2) {
    [0] =>
    array(2) {
      'processTime' =>
      double(0.042915344238281)
      'round' =>
      int(1)
    }
    [1] =>
    array(2) {
      'processTime' =>
      double(0.25415420532227)
      'round' =>
      int(2)
    }
  }
  'foo' =>
  array(2) {
    [0] =>
    array(2) {
      'processTime' =>
      double(0.43487548828125)
      'round' =>
      int(1)
    }
    [1] =>
    array(2) {
      'processTime' =>
      double(0.08082389831543)
      'round' =>
      int(2)
    }
  }
}

```
