<?php
require_once "DBController.php";

class Assignment extends DBController
{
	/*------------------------------- table start ------------------------------------------*/

	function getAllySummary($table_no)
	{
		$query = "SELECT *, from_unixtime(`Timestamp`) AS `FromUnixtime`, from_unixtime(`Timestamp`, '%Y-%m-%d') AS `date`, ROUND(IF(`DailyMainsPosKWh` - LAG(`DailyMainsPosKWh`) over (order by `Timestamp`) > 0, `DailyMainsPosKWh` - LAG(`DailyMainsPosKWh`) over (order by `Timestamp`), 0), 1) AS `diff` FROM $table_no";

		$tableResult = $this->getDBResult($query);
		return $tableResult;
	}

	function getDailySummary($table_no)
	{
		$query = "SELECT *, ROUND(SUM(`diff`), 1) AS `SumDiff`, ROUND(AVG(`MainsRCurr`), 2) AS `AvgMainsRCurr` FROM (SELECT *, from_unixtime(`Timestamp`) AS `FromUnixtime`, from_unixtime(`Timestamp`, '%Y-%m-%d') AS `date`, ROUND(IF(`DailyMainsPosKWh` - LAG(`DailyMainsPosKWh`) over (order by `Timestamp`) > 0, `DailyMainsPosKWh` - LAG(`DailyMainsPosKWh`) over (order by `Timestamp`), 0), 1) AS `diff` FROM $table_no) AS `newtable` GROUP BY `date`";

		$tableResult = $this->getDBResult($query);
		return $tableResult;
	}

	function getWeeklySummary($table_no)
	{
		$weekArr = array(
			'Monday' => 0,
			'Tuesday' => 1,
			'Wednesday' => 2,
			'Thursday' => 3,
			'Friday' => 4,
			'Saturday' => 5,
			'Sunday' => 6
		);

		$startWeekDay = $weekArr['Wednesday'];

		$query = "SELECT *, CONCAT(`date`, ' - ', `date` + INTERVAL 6 DAY) AS `date`, IF((WEEKDAY(`date`) - $startWeekDay) >= 0, TO_DAYS(`date`) - (WEEKDAY(`date`) - $startWeekDay), TO_DAYS(`date`) - (7 - ($startWeekDay - WEEKDAY(`date`)))) AS `sortDay`, ROUND(SUM(`diff`), 1) AS `SumDiff`, ROUND(AVG(`MainsRCurr`), 2) AS `AvgMainsRCurr` FROM (SELECT *, from_unixtime(`Timestamp`) AS `FromUnixtime`, from_unixtime(`Timestamp`, '%Y-%m-%d') AS `date`, ROUND(IF(`DailyMainsPosKWh` - LAG(`DailyMainsPosKWh`) over (order by `Timestamp`) > 0, `DailyMainsPosKWh` - LAG(`DailyMainsPosKWh`) over (order by `Timestamp`), 0), 1) AS `diff` FROM $table_no) AS `newtable` GROUP BY `sortDay`";

		$tableResult = $this->getDBResult($query);
		return $tableResult;
	}

	/*------------------------------- table end --------------------------------------------*/
}
