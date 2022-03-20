<?php
header('Content-type: application/json');
$response = array();
require_once "../class/Assignment.php";
$assignment = new Assignment();
if (isset($_GET["table_no"]))
{
	if ($_GET["periodicity"] == 'Daily')
	{
		$details_array = $assignment->getDailySummary($_GET["table_no"]);
	}
	elseif ($_GET["periodicity"] == 'Weekly')
	{
		$details_array = $assignment->getWeeklySummary($_GET["table_no"]);
	}
	else
	{
		$details_array = $assignment->getAllySummary($_GET["table_no"]);
	}

	if (!empty($details_array))
	{
		$response['status'] = 'success';
		$response['details'] = $details_array;
	}
	else
	{
		$response['status'] = 'error';
		$response['message'] = 'Details not available';
	}
}
else
{
	$response['status'] = 'error';
	$response['message'] = 'Missing parameters, Try again...';
}
echo json_encode($response);
