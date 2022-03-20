<?php
$status = $_SERVER['REDIRECT_STATUS'];
$codes = array(
	400 => array('400 Bad Request', 'The request cannot be fulfilled due to bad syntax !!'),
	403 => array('403 Forbidden', 'The server has refused to fulfil your request !!'),
	404 => array('404 Not Found', 'The page you requested was not found on this server !!'),
	405 => array('405 Method Not Allowed', 'The method specified in the request is not allowed for the specified resource !!'),
	408 => array('408 Request Timeout', 'Your browser failed to send a request in the time allowed by the server !!'),
	500 => array('500 Internal Server Error', 'The request was unsuccessful due to an unexpected condition encountered by the server !!'),
	502 => array('502 Bad Gateway', 'The server received an invalid response while trying to carry out the request !!'),
	504 => array('504 Gateway Timeout', 'The upstream server failed to send a request in the time allowed by the server !!'),
);

$title = $codes[$status][0];
$message = $codes[$status][1];
if ($title == false || strlen($status) != 3)
{
	$message = 'Please supply a valid HTTP status code.';
}
$stri = (string)$status;
$a = $stri[0];
$b = $stri[1];
$c = $stri[2];
?>

<!DOCTYPE html>
<html dir="ltr">

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>SystemTask | <?php echo $title; ?></title>

	<script type="application/x-javascript">
		addEventListener("load", function() {
			setTimeout(hideURLbar, 0);
		}, false);

		function hideURLbar() {
			window.scrollTo(0, 1);
		}
	</script>

	<link href="/assignment/assets/css/style.css" rel="stylesheet">

</head>

<body>

	<!------------------------------- error start ----------------------------------------->
	<div class="main-wrapper">
		<div class="error-box">
			<div class="error-body text-center">
				<br /><br />
				<h1 class="error-title text-danger"><?php echo $a . $b . $c; ?></h1>
				<h3 class="text-uppercase error-subtitle"><?php echo $message; ?></h3>
				<p class="text-muted m-t-30 m-b-30">YOU SEEM TO BE TRYING TO FIND HIS WAY HOME</p>
				<a href="/assignment/index" class="btn btn-lg btn-danger btn-rounded waves-effect waves-light" style="width: 300px;">Back to home</a>
			</div>
		</div>
	</div>
	<!------------------------------- error end ------------------------------------------->

	<script src="/assignment/assets/libs/jquery/dist/jquery.min.js"></script>
	<script src="/assignment/assets/libs/popper.js/dist/umd/popper.min.js"></script>
	<script src="/assignment/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
	<script>
		$('[data-toggle="tooltip"]').tooltip();
		$(".preloader").fadeOut();
	</script>

</body>

</html>