<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
		  type="text/css"/>
	<link href="style.css" type="text/css" rel="stylesheet"/>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.2.1/js/bootstrap.min.js"></script>
	<title>Login</title>
</head>
<body>
<div class="wrapper">
	<?php require_once 'left.php'; ?>
	<div class="login">
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" id="username" placeholder="Enter username">
			<span class="err"></span>
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Password</label>
			<input type="password" class="form-control" id="password" placeholder="Password">
			<span class="err"></span>
		</div>
		<button class="btn btn-primary btn-login">Submit</button>
	</div>
</div>
</body>
<script type="text/javascript">
	$(document).on('click', '.btn-login', function () {
		if ($('#username').val() == "") {
			$('#username').next().html('You must enter your email');
		} else {
			$('#username').next().html('');
		}
		if ($('#password').val() == "") {
			$('#password').next().html('You must enter your password');
		} else {
			$('#password').next().html('');
		}
		if ($('#password').val() && $('#username').val()) {
			$('#modal').modal('show')
			$('.err').empty()
			$.ajax({
				type: 'post',
				url: '/post.php',
				data: {
					username: $('#username').val(),
					password: $('#password').val()
				},
				success: function (data) {
					$('#modal').modal('hide')
					if (data = 1) {
						window.location.href = '/guestbook.php';
					}
				}
			})
		}
	})
</script>
<?php require_once 'modal.php'; ?>
