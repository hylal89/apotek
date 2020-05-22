<?php
	if (isset($_COOKIE["admin"])) {
		setcookie("admin", null, time() + -60);
		header("location: login.php");
		die();
	}

	else {
		header("location: login.php");
	}
?>
