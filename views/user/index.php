<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php foreach ($data as $key => $val) { ?>
		<p> <?php echo $val['first_name'] . SPACE . $val['last_name'] . SPACE ?> </p>
	<?php } ?>
</body>
</html>