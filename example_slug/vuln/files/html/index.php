<?php 
	include "db.php";
	$role = "";
	
	if(isset($_GET["back"])) {
		setcookie ("CookiesSet", "", time() - 3600);
		header("location:index.php");
	}else if(isset($_COOKIE["CookiesSet"])) {
		if ($_GET["page"] == "register"){
			header("location:index.php");
		}
	
		$user = base64_decode($_COOKIE["Username"]);
		$pw = base64_decode($_COOKIE["Password"]);
		
		$sql = "SELECT ROLE FROM users WHERE USERNAME='".$user."' AND PASSWORD='".$pw."'";
		
		foreach ($pdo->query($sql) as $row) {
			$role=$row["ROLE"];
		}		
		
		if(!empty($role)){
			$users = array();
			$sql = "SELECT USERNAME FROM users;";
			
			foreach ($pdo->query($sql) as $row) {
				$username=$row["USERNAME"];
				array_push($users, $username);
			}
		}
	}

	$action = NULL;
	if (isset($_POST["action"])){
		$action = $_POST["action"];

		if($action == "register" && isset($_POST["username"]) && isset($_POST["password"])){
			$sql = "INSERT INTO users (username, password, role, apitoken) VALUES (?, MD5(?), 'USER', ?)";

			$st = $pdo->prepare($sql);
			$st->bindParam(1, $_POST["username"]);
			$st->bindParam(2, $_POST["password"]);
			#$st->bindParam(3, base64_encode($_POST["username"]));
			$st->bindParam(3, hash("sha256", $_POST["username"]));
			

			$register_success = $st->execute();

		}else if($action == "addsniff" && $role != ""){
			$sql = "INSERT INTO secrets (server, username, password, owner, permitted) VALUES (?, ?, ?, ?, '')";

			$st = $pdo->prepare($sql);
			$st->bindParam(1, $_POST["server"]);
			$st->bindParam(2, $_POST["username"]);
			$st->bindParam(3, $_POST["password"]);
			$st->bindParam(4, $user);

			$addsniff_success = $st->execute();
		
		}else if($action == "login"){
			if(isset($_POST["username"]) && isset($_POST["password"])) {
				$user = $_POST["username"];
				$pw = base64_encode(hash('MD5', $_POST["password"]));
			
				$user = str_replace('"', "", $user);
				$user = str_replace("'", "", $user);
				$user = str_replace(";", "", $user);
				
				$us = base64_encode($user);
				
				setcookie("CookiesSet", true, time()+300);
				setcookie("Username", $us, time()+300);
				setcookie("Password", $pw, time()+300);
				header("location:index.php");
			}
		}
	}

	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Federal Password Database</title>
	
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	
	<script src="js/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"></script>
	<script src="js/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"></script>
	<script src="js/bootstrap.js"></script>
</head>
<body>
	<h2 class="text-center fpd-head">Federal Password Database</h2>
	<div class="container-fluid">
		<?php 	

			if($action == "register" && isset($_POST["username"]) && isset($_POST["password"])){
				if($register_success){
					$error = '<div class="col-sm-4 col-sm-offset-4">
								<div class="panel panel-success">
									<div class="panel-heading"><h4>SUCCESS</h4></div>
									<div class="panel-body">Registration successful!</div>
								</div>
							</div>';
					
					echo $error;

					$registered = TRUE;
				}else{
					$error = '<div class="col-sm-4 col-sm-offset-4">
								<div class="panel panel-danger">
									<div class="panel-heading"><h4>ERROR</h4></div>
									<div class="panel-body">Registration failed!</div>
								</div>
							</div>';
					
					echo $error;
				}
			}else if($action == "addsniff" && $role != "" && !$addsniff_success){
				$error = '<div class="col-sm-8 col-sm-offset-2">
							<div class="panel panel-danger">
								<div class="panel-heading"><h4>ERROR</h4></div>
								<div class="panel-body">Adding sniffed password failed!</div>
							</div>
						</div>';
				
				echo $error;
			}

			if(!$_COOKIE["CookiesSet"]){
				if($_GET["page"] != "register" || $registered){
					$form = '<form class="form-signin" method="POST" action="">
							<input name="username" type="text" id="inputUser" class="form-control" placeholder="Username" required autofocus>
							<input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
							<input name="action" type="hidden" value="login">
							<button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
							<p><a href="?page=register">Register</a></p>
						</form>';
					
					echo $form;
				}else{
					$form = '<form class="form-signin" method="POST" action="">
							<input name="username" type="text" id="inputUser" class="form-control" placeholder="Username" required autofocus>
							<input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required>
							<input name="action" type="hidden" value="register">
							<button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Register</button>
						</form>';
					
					echo $form;
				}
			}else{
				if ($role == ""){	
					$form = '<form class="form-signin" method="GET" action="">						
							<button name="back" value="1" class="btn btn-lg btn-primary btn-block" type="submit">Back</button>
							</form>';
					
					$error = '<div class="col-sm-4 col-sm-offset-4">
								<div class="panel panel-danger">
									<div class="panel-heading"><h4>ERROR</h4></div>
									<div class="panel-body">Wrong Username or Password!<br><br>'.$form.'</div>
								</div>
							</div>';
					
					echo $error;
				}else{
					$sql = "SELECT * FROM secrets;";
					$useroutput = "";
					$secretsoutput = "";
					foreach($users as &$u){
						$useroutput.="<tr><td>".$u."</td></tr>";
					}
					
					foreach ($pdo->query($sql) as $row) {
						$realpw = $row["Password"];
						$pass = "***********";
						
						if($role == "ADMIN"){
							$tr = "<tr><td>".$row["ID"]."</td><td>".$row["Server"]."</td><td>".$row["Username"]."</td><td>".$realpw."</td><td>";
						}else{
							$isPermitted = false;
							$permittedusers = explode(";", $row["PERMITTED"]);
							$owner = $row["Owner"];

							foreach ($permittedusers as $puser){
								if($puser == $user){
									$isPermitted = true;
									break;
								}
							}

							if($isPermitted || $owner == $user){
								$tr = "<tr><td>".$row["ID"]."</td><td>".$row["Server"]."</td><td>".$row["Username"]."</td><td>".$realpw."</td><td>";
							}else{
								$tr = "<tr><td>".$row["ID"]."</td><td>".$row["Server"]."</td><td>".$row["Username"]."</td><td>".$pass."</td><td>";
							}
						}
						
						$secretsoutput.=$tr;
					}
					
					$users = '<div class="col-md-2 col-md-offset-2 col-sm-12">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h4>FPD Users</h4>
									</div>
									<div class="panel-body fixed-panel">
										<table class="table">'.$useroutput.'</table>
									</div>
									<div class="panel-footer">
									<form class="form-signin" method="GET" action="" style="padding:0;margin:0">						
										<button name="back" value="1" class="btn btn-primary btn-block btn-signout" type="submit">Logout</button>
									</form>
								</div>
								</div>
							</div>';
					
					$sql = "SELECT apitoken FROM users WHERE username='".$user."';";
					$stmt = $pdo->prepare($sql);
					$stmt->execute();
					$q = $stmt->fetch();
					$token = $q["apitoken"];

					$creds = '<div class="col-md-6 col-sm-12">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<div class="row">
											<div class="col-md-4">
												<h4>Database Output</h4>
											</div>
											<div class="col-md-8 text-right">
												API Token: '.$token.'
											</div>
										</div>
									</div>
									<div class="panel-body fixed-panel">
										<table class="table">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th scope="col">Server</th>
												<th scope="col">Username</th>
												<th scope="col">Password</th>
											</tr>
										</thead>
										<tbody>'.$secretsoutput.'</tbody>
										</table>
									</div>
									<div class="panel-footer">
										<!-- Button trigger modal -->
										<button type="button" class="btn btn-primary btn-block btn-signout" data-toggle="modal" data-target="#myModal">
										  Add entry
										</button>
									</div>
								</div>
							</div>';							
					$page = $users.$creds;
					
					echo $page;
				}
			}
		?>
    </div>

	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">Add entry</h4>
	      </div>
	      <div class="modal-body">
		<form method="POST" action="" class="form-signin" id="formAddEntry">
			<input name="server" type="text" id="inputServer" class="form-control" placeholder="Server" required>
			<input name="username" type="text" id="inputUser" class="form-control" placeholder="Username" required>
			<input name="password" type="text" id="inputPassword" class="form-control" placeholder="Password" required>
			<input name="action" type="hidden" value="addsniff">
		</form>
	      </div>
	      <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="button" class="btn btn-primary" onclick="$('#formAddEntry').submit()">Add entry</button>
	      </div>
	    </div>
	  </div>
	</div>

	<footer class="footer hidden-sm">
		<div class="container text-center">
			<span><i class="fa fa-copyright" aria-hidden="true"></i> 2017 by Super Secret Agency</span> 
		</div>
    </footer>
</body>
</html>
<!--
ISC License

Copyright (c) 2018, Marcel "h4ckd0tm3" Schnideritsch
Copyright (c) 2018, Keyboardninjas Hacking Team
Copyright (c) 2018, Michael "mickdermack" Ehrenreich

Permission to use, copy, modify, and/or distribute this software for any
purpose with or without fee is hereby granted, provided that the above
copyright notice and this permission notice appear in all copies.

THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES
WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR
ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES
WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN
ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF
OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.



-->
