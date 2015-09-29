<?php

use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaView\View;

function InsertUserFormView($model_user, $model_login)
{
	
	?>
	<form method="post" action="<?php echo $model_login->url_insert; ?>">
	<?php
	
	
	echo View::load_view(array($model_user->forms, $model_login->arr_user_insert), 'forms/modelform');
		

	?>
	<p><input type="submit" value="<?php echo I18n::lang('users', 'register', 'Register in the web'); ?>"/></p>
	</form>
	<?php

}

?>