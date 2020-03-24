<?php

namespace jun3453\prosp;

/**
 * Class ProcessingSpeedMeasurement
 * @package jun3453\prosp
 */
class ProcessingSpeedMeasurement
{
	/**
	 * @var array [context: string => ms: double]
	 */
	private static $startProcessingTimes = [];

	/**
	 * @var array [context: string [count: int] => ms: double]
	 */
	private static $processingTimes = [];

	/**
	 * @var array [context: string => count: int]
	 */
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
	 * @return array [context: string => {processTimes: float, totalRounds: int} ]
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
	 * @return array {processTimes: float, totalRounds: int}
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
	 * @return array [context: string => [{processTimes: float, rounds: int}] ]
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

	/**
	 * @param float $ms
	 * @return array [context: string => [{processTimes: float, rounds: int}] ]
	 */
	public static function getResultsShorterThanSpecifiedMs(float $ms): array
	{
		$result = [];
		foreach (array_keys(self::$processingTimes) as $context) {
			$round = 0;
			foreach (self::$processingTimes[$context] as $processingTime) {
				$round++;
				if ($processingTime < $ms) {
					$result[$context][] = [
						'processTimes'  => $processingTime,
						'rounds' => $round
					];
				}
			}
		}
		return $result;
	}

	public static function reset()
	{
		static::$startProcessingTimes = [];
		static::$processingTimes = [];
		static::$processingCount = [];
	}
}