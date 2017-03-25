<?php 
	$token = $_GET['token'];
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>forgot password</title>
</head>
<body>
	<form action="<?php echo'http://localhost/footer_backend/index.php/api/User/change_pass/'.$token; ?>" method="post">
		<input type="password" name="new_password" placeholder="New Password">
		<input type="submit" value="Proccess" style="font-size: 1.2em; font-weight: bolder;">
	</form>
</body>
</html>