<?php

use PhangoApp\PhaUtils\Utils;
//use PhangoApp\PhaModels\ModelForm;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaModels\CoreFields\BooleanField;
//use PhangoApp\PhaModels\CoreForms;
use PhangoApp\PhaView\View;

function LoginFormView($model_user, $model_login)
{

    //'no_expire_session'

    $arr_fields_login=array($model_login->field_user, $model_login->field_password);

	/*
	$model_user->forms['no_expire_session']=new ModelForm('form_login', 'no_expire_session', 'PhangoApp\PhaModels\CoreForms::CheckBoxForm', I18n::lang('users', 'automatic_login', 'Automatic login'), new BooleanField(), $required=1, $parameters='');
	
	$model_user->forms['no_expire_session']->label_class='expire_button';*/
	
	?>
	<?php echo View::show_flash(); ?>
	<form method="post" action="<?php echo $model_login->url_login; ?>">
	<?php
		Utils::set_csrf_key();
		
		echo View::load_view(array($model_user->forms, $arr_fields_login), 'forms/modelform');

	?>
	<p><a href="<?php echo $model_login->url_recovery; ?>"><?php echo I18n::lang('users', 'remember_password', 'Remember password'); ?></a></p>
	<p><input type="submit" value="<?php echo I18n::lang('common', 'login', 'Login'); ?>" /></p>
	</form>
	<?php

}

?>
