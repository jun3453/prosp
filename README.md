# Usage
```$xslt
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

```$xslt
var_dump(ProcessingSpeedMeasurement::getProcessingResultByContext("foo"));

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

```
var_dump(ProcessingSpeedMeasurement::getAllProcessingResults());

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

```
var_dump(ProcessingSpeedMeasurement::getResultsLongerThanSpecifiedMs(0.04));

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