<?php

namespace jun3453\prosp;

use PHPUnit\Framework\TestCase;

/**
 * Class ProcessingSpeedMeasurementTest
 * @package jun3453\prosp
 */
class ProcessingSpeedMeasurementTest extends TestCase
{
	public function testSingle()
	{
		ProcessingSpeedMeasurement::startProcessing("foo");
		ProcessingSpeedMeasurement::endProcessing("foo");

		$fooResult = ProcessingSpeedMeasurement::getProcessingResultByContext("foo");
		$this->assertEquals($fooResult["totalRounds"], 1);

		ProcessingSpeedMeasurement::reset();
	}

	public function testNesting()
	{
		ProcessingSpeedMeasurement::startProcessing("foo");
		ProcessingSpeedMeasurement::startProcessing("bar");
		ProcessingSpeedMeasurement::endProcessing("bar");
		ProcessingSpeedMeasurement::endProcessing("foo");

		$fooResult = ProcessingSpeedMeasurement::getProcessingResultByContext("foo");
		$barResult = ProcessingSpeedMeasurement::getProcessingResultByContext("bar");
		$this->assertEquals($fooResult["totalRounds"], 1);
		$this->assertEquals($barResult["totalRounds"], 1);

		ProcessingSpeedMeasurement::reset();
	}

	public function testTwoNesting()
	{
		ProcessingSpeedMeasurement::startProcessing("foo");
		ProcessingSpeedMeasurement::startProcessing("bar");
		ProcessingSpeedMeasurement::startProcessing("baz");
		ProcessingSpeedMeasurement::endProcessing("baz");
		ProcessingSpeedMeasurement::endProcessing("bar");
		ProcessingSpeedMeasurement::endProcessing("foo");

		$fooResult = ProcessingSpeedMeasurement::getProcessingResultByContext("foo");
		$barResult = ProcessingSpeedMeasurement::getProcessingResultByContext("bar");
		$bazResult = ProcessingSpeedMeasurement::getProcessingResultByContext("baz");
		$this->assertEquals($fooResult["totalRounds"], 1);
		$this->assertEquals($barResult["totalRounds"], 1);
		$this->assertEquals($bazResult["totalRounds"], 1);

		ProcessingSpeedMeasurement::reset();
	}

	public function testAlternate()
	{
		ProcessingSpeedMeasurement::startProcessing("foo");
		ProcessingSpeedMeasurement::startProcessing("bar");
		ProcessingSpeedMeasurement::endProcessing("foo");
		ProcessingSpeedMeasurement::startProcessing("baz");
		ProcessingSpeedMeasurement::endProcessing("bar");
		ProcessingSpeedMeasurement::endProcessing("baz");

		$fooResult = ProcessingSpeedMeasurement::getProcessingResultByContext("foo");
		$barResult = ProcessingSpeedMeasurement::getProcessingResultByContext("bar");
		$bazResult = ProcessingSpeedMeasurement::getProcessingResultByContext("baz");
		$this->assertEquals($fooResult["totalRounds"], 1);
		$this->assertEquals($barResult["totalRounds"], 1);
		$this->assertEquals($bazResult["totalRounds"], 1);

		ProcessingSpeedMeasurement::reset();
	}

	public function testMultipleRounds()
	{
		for($i=0; $i<2; $i++) {
			ProcessingSpeedMeasurement::startProcessing("foo");
			for($j=0; $j<2; $j++) {
				ProcessingSpeedMeasurement::startProcessing("bar");
				ProcessingSpeedMeasurement::endProcessing("bar");
			}
			ProcessingSpeedMeasurement::endProcessing("foo");
		}

		$fooResult = ProcessingSpeedMeasurement::getProcessingResultByContext("foo");
		$barResult = ProcessingSpeedMeasurement::getProcessingResultByContext("bar");
		$this->assertEquals($fooResult["totalRounds"], 2);
		$this->assertEquals($barResult["totalRounds"], 4);

		ProcessingSpeedMeasurement::reset();
	}

	public function testGetResultsShorterThanSpecifiedMs()
	{
		ProcessingSpeedMeasurement::startProcessing("foo");
		usleep(100000);
		ProcessingSpeedMeasurement::endProcessing("foo");

		$fooResult = ProcessingSpeedMeasurement::getResultsShorterThanSpecifiedMs(500);
		$this->assertCount(1, $fooResult["foo"]);

		$emptyResult = ProcessingSpeedMeasurement::getResultsShorterThanSpecifiedMs(50);
		$this->assertArrayNotHasKey("foo", $emptyResult);

		ProcessingSpeedMeasurement::reset();
	}

	public function testGetResultsLongerThanSpecifiedMs()
	{
		ProcessingSpeedMeasurement::startProcessing("foo");
		usleep(100000);
		ProcessingSpeedMeasurement::endProcessing("foo");

		$fooResult = ProcessingSpeedMeasurement::getResultsLongerThanSpecifiedMs(50);
		$this->assertCount(1, $fooResult["foo"]);

		$emptyResult = ProcessingSpeedMeasurement::getResultsLongerThanSpecifiedMs(5000);
		$this->assertArrayNotHasKey("foo", $emptyResult);

		ProcessingSpeedMeasurement::reset();
	}
}
