<?php
require_once "include/class/Assignment.php";
$assignment = new Assignment();
$temp_directory = './output/';
$table_array = ["data12298", "data12335", "data12765"];
foreach ($table_array as $table_no)
{
	$heading = false;
	@$handle = fopen($temp_directory . $table_no . '.csv', 'w');
	if (!$handle)
	{
		echo "Resource temporarily unavailable !!";
		exit(1);
	}
	$details_array = $assignment->getAllySummary($table_no);
	if (!empty($details_array))
	{
		foreach ($details_array as $item)
		{
			if (!$heading)
			{
				fputcsv($handle, array_keys($item));
				$heading = true;
			}
			fputcsv($handle, $item);
		}
	}
	fclose($handle);
}
echo "Table Generated Successfully !!, Please navigate to output folder to see generated table.";
