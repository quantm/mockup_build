<?php session_start();
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'): ?>
	<?php
	require_once 'db.php';

	switch ($_POST['type']) {
		case 'update':
			if (!mysqli_query($con, "update guest_book set message='" . $_POST['message'] . "' , auth_name='" . $_POST['name'] . "' where id=" . $_POST['id'] . "")) {
				echo("Error update data: " . mysqli_error($con));
			}
			break;
		case 'insert':
			if (!mysqli_query($con, "INSERT INTO guest_book (message, auth_name) VALUES ('" . $_POST['message'] . "','" . $_POST['name'] . "')")) {
				echo("Error insert data: " . mysqli_error($con));
			}
			break;
		case 'delete':
			if (!mysqli_query($con, "delete from guest_book where id=" . $_POST['id'])) {
				echo("Error insert data: " . mysqli_error($con));
			}
			break;
	}
	
	mysqli_close($con);
	?>
<?php elseif ($_POST['username'] && $_POST['password'] && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'): ?>
	<?php
	if (($_POST['username'] == 'admin') && ($_POST['password'] == '123456')) {
		$_SESSION['admin'] = 'admin';
		echo 1;
	}
	?>
<?php else: ?>
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
	<title><?php if ($_GET['id']): ?>Update <?php else: ?>Write<?php endif; ?> Guestbook</title>
</head>
<body>
<div class="wrapper">
	<?php require_once 'left.php'; ?>
	<div class="edit-post">
		<?php if ($_GET['id']): ?>
			<?
			require_once 'db.php';

			$query = mysqli_query($con, "SELECT message, auth_name FROM guest_book where id=" . $_GET['id']);
			$row = mysqli_fetch_row($query);
			empty($row) && header('Location: /guestbook.php');
			?>
			<div class="form-group">
				<label for="guestName">Name</label>
				<input type="text" class="form-control" id="guestName" value="<?= $row[1] ?>" placeholder="Enter name">
				<span class="error"></span>
			</div>
			<div class="form-group">
				<label for="guestMessage">Message</label>
				<textarea class="form-control" id="guestMessage"
						  placeholder="Please leave your message"><?= $row[0] ?></textarea>
				<span class="error"></span>
			</div>
			<div class="form-group">
				<button class="btn btn-primary btn-update">Update</button>
				<span class="error"></span>
			</div>
		<?php else: ?>
			<div class="form-group">
				<label for="guestName">Name</label>
				<input type="text" class="form-control" id="guestName" placeholder="Enter name">
				<span class="error"></span>
			</div>
			<div class="form-group">
				<label for="guestMessage">Message</label>
				<textarea class="form-control" id="guestMessage" placeholder="Please leave your message"></textarea>
				<span class="error"></span>
			</div>
			<button class="btn btn-primary btn-write">Write guest book</button>
		<?php endif; ?>
	</div>
</div>
<script type="text/javascript">
	$(document).on('click', '.btn-write', function () {
		if ($('#guestName').val() == "") {
			$('#guestName').next().html('You must enter your name');
		} else {
			$('#guestName').next().html('');
		}
		if ($('#guestMessage').val() == "") {
			$('#guestMessage').next().html('You must enter your message');
		} else {
			$('#guestMessage').next().html('');
		}
		if ($('#guestName').val() && $('#guestMessage').val()) {
			$('#modal').modal('show')
			$('.err').empty()
			$.ajax({
				type: 'post',
				url: '/post.php',
				data: {
					type: 'insert',
					name: $('#guestName').val(),
					message: $('#guestMessage').val()
				},
				success: function (data) {
					$('#modal').modal('hide')
				}
			})
		}
	}).on('click', '.btn-update', function () {
		$('#modal').modal('show')
		$.ajax({
			type: 'post',
			url: '/post.php',
			data: {
				id: "<?=$_GET['id']?>",
				type: 'update',
				name: $('#guestName').val(),
				message: $('#guestMessage').val()
			},
			success: function (data) {
				$('#modal').modal('hide')
				$('.btn-update').next().html('Successfully updated')
			}
		})
	})
</script>
</body>
<?php require_once 'modal.php'; ?>
<?php endif; ?>
