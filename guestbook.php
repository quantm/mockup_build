<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
		  type="text/css"/>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.2.1/js/bootstrap.min.js"></script>
	<link href="style.css" type="text/css" rel="stylesheet"/>
	<title>Guestbook</title>
</head>
<body>
<div class="wrapper">
	<?php require_once 'left.php'; ?>
	<div class="message">
		<?
		require_once 'db.php';

		$query = mysqli_query($con, "SELECT * FROM guest_book");
		$result = mysqli_fetch_all($query, MYSQLI_ASSOC);
		$output = '<div class="container-fluid">';

		for ($index = 0; $index < count($result); $index++) {
			$output .= '<div class="row">';

			//two column
			if (fmod($index, 2)) {
				$dateEven = explode(' ', $result[$index - 1]['date_created']);
				$dateOdd = explode(' ', $result[$index - 1]['date_created']);

				$output .= '<div class="col">';
				$output .= '<div class="content">' . $result[$index - 1]['message'];
				$output .= '<div class="author"><span class="author-name">' . $result[$index - 1]['auth_name'] . '</span><br><span class="time-post">' . $dateEven[0] . ' at ' . $dateEven[1] . '</span></div>';
				$output .= '<div class="action"><span><a href="/post.php?id=' . $result[$index - 1]['id'] . '"><i class="fa fa-edit"></i></a></span> <span><a href="#"><i data-id="' . $result[$index - 1]['id'] . '" class="fa fa-trash"></i></a></span></div></div>';
				$output .= '</div>';

				$output .= '<div class="col">';
				$output .= '<div class="content">' . $result[$index]['message'];
				$output .= '<div class="author"><span class="author-name">' . $result[$index]['auth_name'] . '</span><br><span class="time-post">' . $dateOdd[0] . ' at ' . $dateOdd[1] . '</span></div>';
				$output .= '<div class="action"><span><a href="' . $result[$index - 1]['id'] . '"><i class="fa fa-edit"></i></a></span> <span><a href="#"><i data-id="' . $result[$index - 1]['id'] . '" class="fa fa-trash"></i></a></span></div></div>';
				$output .= '</div>';
			}
			$output .= '</div>';
		}
		//if row is odd number add empty col
		if (fmod(count($result), 2)) {
			$date = explode(' ', $result[$index - 1]['date_created']);
			$output .= '<div class="row">';
			$output .= '<div class="col">';
			$output .= '<div class="content">' . $result[$index - 1]['message'];
			$output .= '<div class="author"><span class="author-name">' . $result[$index - 1]['auth_name'] . '</span><br><span class="time-post">' . $date[0] . ' at ' . $date[1] . '</span></div>';
			$output .= '<div class="action"><span><a href="/post.php?id=' . $result[$index - 1]['id'] . '"><i class="fa fa-edit"></i></a></span> <span><a href="#"><i data-id="' . $result[$index - 1]['id'] . '" class="fa fa-trash"></i></a></span></div></div>';
			$output .= '</div>';

			$output .= '<div class="col">';
			$output .= '<div class="content">';
			$output .= '<div class="author"><span class="author-name"></span><br><span class="time-post"></span></div>';
			$output .= '<div class="action"></div></div>';
			$output .= '</div>';

			$output .= '</div>';

		}
		$output .= '</div>';
		echo $output;

		mysqli_close($con);
		?>
		<div class="container">
			<div class="row">
				<div class="offset-lg-3">
					<ul class="pagination">
						<li><a href="#"> < </a></li>
						<li><a href="#"> 1 </a></li>
						<li><a class="current" href="#"> 2</a></li>
						<li><a href="#"> 3 </a></li>
						<li><a href="#"> > </a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).on('click', '.fa-trash', function () {
		<?php if($_SESSION['admin']):?>
		$('.modal-body').hide()
		$('#modal').modal('show').find('.modal-title').html('Area you want to delete ?')
		if ($('.btn-delete').length == 0) {
			$('.modal-footer').prepend('<button delete-id=' + $(this).attr('data-id') + ' type="button" class="btn btn-danger fa fa-trash btn-delete">Delete</button>')
		}
		if ($(this).attr('delete-id')) {
			$('.modal-body').show()
			$.ajax({
				type: 'post',
				url: '/post.php',
				data: {
					id: $(this).attr('delete-id'),
					type: 'delete',
				},
				success: function (data) {
					$('.btn-delete').remove()
					$('.modal-body').html('Succesfully deleted')
				}
			})
		}
		<?php else:?>
		$('#modal').modal('show').find('.modal-title').html("You don't have the right to delete ?")
		<?php endif;?>
	})
</script>
<?php require_once 'modal.php'; ?>
</body>
</html>
