<?php
session_start();
if (isset($_SESSION['userid'])){
  header("Location: autos.php");
}
  require 'pdo.php';

	if(isset($_POST['login'])) {
		$errMsg = '';

		// Get data from FORM
		$email = htmlentities(trim($_POST['email']));
		$password = htmlentities(trim($_POST['password']));

		if((empty($email)) OR (empty($password))){
			$errMsg = 'Email and password are required';
    }
    elseif(!preg_match("/.+@.+/",$email)){
      $errMsg= 'Email must have an at-sign (@)';
}
		if($errMsg == '') {
			try {
				$stmt = $pdo->prepare("SELECT userid,email,name,password FROM users WHERE email = :email");
				$stmt->execute(array(
					':email' => $email
					));
				$data = $stmt->fetch(PDO::FETCH_ASSOC);

				if($data == false){
					$errMsg = "User $email not found.";
				}
				else {
					if($password == $data['password']) {
						$_SESSION['name'] = $data['name'];
						$_SESSION['userid'] = $data['userid'];
            $_SESSION['email'] = $data['email'];
            error_log("Login success".$_POST['email']);
						header("Location: autos.php?name=".urlencode($_POST['email']));
						exit;
					}
					else
						$errMsg = 'Incorrect password';
            error_log("Login fail".$_POST['email']."$check");
				}
			}
			catch(PDOException $e) {
				$errMsg = $e->getMessage();
			}
		}
	}
?>

<html>
<head><title>Ariel Leon Socio Bonfim</title></head>
	<style>
	html, body {
		margin: 1px;
		border: 0;
	}
	</style>
<body>
	<div align="center">
		<div style=" border: solid 1px #006D9C; " align="left">
			<?php
				if(isset($errMsg)){
					echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
				}
			?>
			<div style="background-color:#006D9C; color:#FFFFFF; padding:10px;"><b>Login</b></div>
			<div style="margin: 15px">
				<form action="" method="post">
					<input type="text" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>" autocomplete="off" class="box"/><br /><br />
					<input type="password" name="password" value="<?php if(isset($_POST['password'])) echo $_POST['password'] ?>" autocomplete="off" class="box" /><br/><br />
					<input type="submit" name='login' value="Login" class='submit'/><br />
				</form>
			</div>
		</div>
	</div>
</body>
</html>
