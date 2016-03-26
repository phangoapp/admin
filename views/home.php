<?php

use PhangoApp\PhaView\View;

function HomeView($title, $content)
{

?>
<!DOCTYPE html>
<html>
	<head>
	<title><?php echo $title; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<?php 
		echo View::load_js();|n
		echo View::load_css();|n
		echo View::load_header();|n
	?>
	</head>
	<body>
		<?php echo $content; ?>
	</body>
</html>

<?php

}

?>
