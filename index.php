<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>

	<!------------------------------- meta start ------------------------------------------>
	<?php include 'include/common/meta.php'; ?>
	<!------------------------------- meta end -------------------------------------------->

	<title>Assignment | Dashboard</title>

	<!------------------------------- top_link start -------------------------------------->
	<?php include 'include/common/top_link.php'; ?>
	<!------------------------------- top_link end ---------------------------------------->

	<!------------------------------- meta start ------------------------------------------>
	<?php include 'include/common/config.php'; ?>
	<!------------------------------- meta end -------------------------------------------->

</head>

<body class="custom-scrollbar">

	<!------------------------------- loder start ----------------------------------------->
	<div class="preloader">
		<div class="lds-ripple">
			<div class="lds-pos"></div>
			<div class="lds-pos"></div>
		</div>
	</div>
	<!------------------------------- loder end ------------------------------------------->

	<div id="main-wrapper">

		<!------------------------------- table start ------------------------------------------>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-body">
							<table class="table table-striped table-bordered display nowrap" id="table-details" width="100%">
								<thead>
									<tr>
										<th>MainsRCurr</th>
										<th>MainsPosKWh</th>
										<th>DailyMainsPosKWh</th>
										<th>Timestamp</th>
										<th>From Unixtime</th>
										<th>Date</th>
										<th>Diff</th>
										<th>Sum of Diff</th>
										<th>Average of MainsRCurr</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row" id="chart" style="display: none;">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div id="chartContainer" style="height: 370px; width: 100%;"></div>
					</div>
				</div>
			</div>
		</div>
		<!------------------------------- table end -------------------------------------------->

		<div id="form-template1" style="display: none;">
			<select id="table_no" class="form-control form-control-sm">
				<option value="" selected disabled>Select Table</option>
				<option value="data12298">data12298</option>
				<option value="data12335">data12335</option>
				<option value="data12765">data12765</option>
			</select>
		</div>

		<div id="form-template2" style="display: none;">
			<select id="periodicity" class="form-control form-control-sm">
				<option value="" selected disabled>Select periodicity</option>
				<option value="All">All</option>
				<option value="Daily">Daily</option>
				<option value="Weekly">Weekly</option>
			</select>
		</div>

	</div>

	<!------------------------------- bottom_link start ----------------------------------->
	<?php include 'include/common/bottom_link.php'; ?>
	<!------------------------------- bottom_link end ------------------------------------->

	<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
	<script>
		var options = {
			animationEnabled: true,
			title: {
				text: "Daily Energy Summary"
			},
			axisX: {
				title: "Date"
			},
			axisY: {
				title: "Average of MainsRCurr",
				titleFontColor: "#4F81BC",
				lineColor: "#4F81BC",
				labelFontColor: "#4F81BC",
				tickColor: "#4F81BC"
			},
			axisY2: {
				title: "Sum of diff",
				titleFontColor: "#C0504E",
				lineColor: "#C0504E",
				labelFontColor: "#C0504E",
				tickColor: "#C0504E"
			},
			toolTip: {
				shared: true
			},
			legend: {
				cursor: "pointer",
				itemclick: toggleDataSeries
			},
			data: [{
					type: "stackedColumn",
					name: "Average of MainsRCurr",
					showInLegend: true
				},
				{
					type: "stackedColumn",
					name: "Sum of diff",
					axisYType: "secondary",
					showInLegend: true
				}
			]
		};
		$("#chartContainer").CanvasJSChart(options);

		function toggleDataSeries(e) {
			if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
				e.dataSeries.visible = false;
			} else {
				e.dataSeries.visible = true;
			}
			e.chart.render();
		}

		domain_id = '';
		flag = false;
		table = $('#table-details').DataTable({
			dom: '<"row"<"col-sm-12 col-md-8 toolbar"l><"col-sm-12 col-md-4"f>><"row"<"col-sm-12"t>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
			language: {
				searchPlaceholder: "Search records"
			},
			autoWidth: true,
			ordering: false,
			columnDefs: [{
					defaultContent: "",
					targets: "_all",
				},
				{
					visible: false,
					targets: [7, 8]
				}
			]
		});

		jQuery('.dataTable').wrap('<div class="dataTables_scroll" />');

		$("div.toolbar").append('<div style="display:inline-block;margin-left:10px;">' + $('#form-template1').html() + '</div><div style="display:inline-block;margin-left:10px;">' + $('#form-template2').html() + '</div>');

		function toggleTableHeader() {
			if ($.inArray($('#periodicity :selected').val(), ['Daily', 'Weekly']) !== -1) {
				table.columns([0, 1, 2, 3, 4, 6]).visible(false);
				table.columns([7, 8]).visible(true);
				if ($('#periodicity :selected').val() == 'Weekly') {
					options.title.text = "Weekly Energy Summary";
				} else {
					options.title.text = "Daily Energy Summary";
				}
				$('#chart').show();
			} else {
				table.columns([7, 8]).visible(false);
				table.columns([0, 1, 2, 3, 4, 6]).visible(true);
				$('#chart').hide();
			}
		}

		$("#table_no, #periodicity").on("change", function() {
			if ($('#table_no :selected').val()) {
				$.ajax({
					url: "include/fetch/get_table_details.php",
					type: 'GET',
					data: {
						table_no: $('#table_no :selected').val(),
						periodicity: $('#periodicity :selected').val()
					},
					dataType: 'json',
					beforeSend: function() {
						toggleTableHeader();
						$("#table-details").addClass("loading");
						table.columns().checkboxes.deselect(true);
					},
					success: function(data) {
						$("#table-details").removeClass("loading");
						if (data.status == 'success') {
							table.clear();
							options.data[0].dataPoints = [];
							options.data[1].dataPoints = [];
							$.each(data.details, function(index, value) {
								table.row.add([value.MainsRCurr, value.MainsPosKWh, value.DailyMainsPosKWh, value.Timestamp, value.FromUnixtime, value.date, value.diff, value.SumDiff, value.AvgMainsRCurr]);
								options.data[0].dataPoints.push({
									label: value.date,
									y: value.AvgMainsRCurr
								});
								options.data[1].dataPoints.push({
									label: value.date,
									y: value.SumDiff
								});
							});
							table.draw();
							$("#chartContainer").CanvasJSChart().render();
						} else {
							table.clear().draw();
							toastr.error(data.message);
						}
					},
					error: function(xhr, status, error) {
						$("#table-details").removeClass("loading");
						table.clear().draw();
						toastr.error(xhr.responseText);
					}
				})
			} else {
				table.clear().draw();
			}
		});
	</script>

</body>

</html>