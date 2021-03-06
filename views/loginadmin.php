<?php

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaView\View;

function LoginAdminView($content)
{

?>
<!DOCTYPE html>
<html>
	<head>
	<title><?php echo I18n::lang('users', 'login', 'Login'); ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
	<?php 
		View::$css_module['admin'][]='login.css';
	
		echo View::load_js();
		echo View::load_css();
		echo View::load_header();
	?>
	</head>
	<body>
		<div id="logo_phango"></div>
		<div id="login_block">
			<?php echo $content; ?>
		</div>
	</body>
</html>

<?php

}

?>
