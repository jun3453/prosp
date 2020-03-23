<?php

namespace prosp;

/**
 * Class ProcessingSpeedMeasurement
 * @package prosp
 */
class ProcessingSpeedMeasurement
{
	private static $startProcessingTimes = [];

	private static $processingTimes = [];

	private static $processingCount = [];

	/**
	 * @param string $context
	 */
	public static function startProcessing(string $context)
	{
		self::$startProcessingTimes[$context] = microtime(true);

		if (!isset(self::$processingCount[$context])) {
			self::$processingCount[$context] = 0;
		}
	}

	/**
	 * @param string $context
	 */
	public static function endProcessing(string $context)
	{
		if (!isset(self::$startProcessingTimes[$context])) {
			// Not started
			return;
		}

		// Increment processing count
		$count = self::$processingCount[$context] += 1;

		// End measurement. Convert to ms.
		self::$processingTimes[$context][$count] = (microtime(true) - self::$startProcessingTimes[$context]) * 1000;
	}

	/**
	 * @return array
	 */
	public static function getAllProcessingResults(): array
	{
		$result = [];
		foreach (array_keys(self::$processingTimes) as $context) {
			$result[$context] = [
				'processTimes'  => self::$processingTimes[$context],
				'totalRounds' => self::$processingCount[$context]
			];
		}
		return $result;
	}

	/**
	 * @param string $context
	 * @return array
	 */
	public static function getProcessingResultByContext(string $context): array
	{
		return [
			'processTimes'  => self::$processingTimes[$context],
			'totalRounds' => self::$processingCount[$context]
		];
	}

	/**
	 * @param float $ms
	 * @return array
	 */
	public static function getResultsLongerThanSpecifiedMs(float $ms): array
	{
		$result = [];
		foreach (array_keys(self::$processingTimes) as $context) {
			$round = 0;
			foreach (self::$processingTimes[$context] as $processingTime) {
				$round++;
				if ($processingTime > $ms) {
					$result[$context][] = [
						'processTimes'  => $processingTime,
						'rounds' => $round
					];
				}
			}

		}
		return $result;
	}
}