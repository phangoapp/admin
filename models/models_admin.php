<?php

//use PhangoApp\Framework\StdModels\UserPhangoModel;

#Utils::load_libraries('models/userphangomodel');
#Utils::load_libraries('fields/passwordfield');

use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaModels\CoreFields\CharField;
use PhangoApp\PhaModels\CoreFields\IntegerField;
use PhangoApp\PhaModels\CoreFields\PasswordField;
use PhangoApp\PhaModels\CoreFields\EmailField;
use PhangoApp\PhaModels\CoreFields\ChoiceField;
use PhangoApp\PhaModels\CoreFields\ForeignKeyField;
use PhangoApp\PhaModels\ExtraModels\UserPhangoModel;
use PhangoApp\PhaI18n\I18n;

I18n::load_lang('admin');

class ChoiceAdminField extends ChoiceField {

	static public $arr_options_formated;
	
	static public $arr_options_select;

	public function show_formatted($value)
	{
	
		return ChoiceAdminField::$arr_options_formated[$value];
	
	}

}

ChoiceAdminField::$arr_options_formated=array(0 => I18n::lang('admin', 'administrator', 'Administrator'), 1 => I18n::lang('admin', 'moderator', 'Moderator'));

ChoiceAdminField::$arr_options_select=array(0 => I18n::lang('admin', 'administrator', 'Administrator'), 1 => I18n::lang('admin', 'moderator', 'Moderator'));

Webmodel::$model['user_admin']=new UserPhangoModel('user_admin');

Webmodel::$model['user_admin']->register('user_admin', new CharField(25), 1);

Webmodel::$model['user_admin']->register('password', new PasswordField(255), 1);

Webmodel::$model['user_admin']->register('email', new EmailField(255), 1);

Webmodel::$model['user_admin']->register('privileges_user', new ChoiceAdminField($size=11, $type='integer', $arr_values=array(0, 1), $default_value=1));

Webmodel::$model['user_admin']->register('token_client', new CharField(255));

Webmodel::$model['user_admin']->register('token_recovery', new CharField(255));

Webmodel::$model['user_admin']->username='email';

Webmodel::$model['login_tried_admin']=new Webmodel('login_tried_admin');

Webmodel::$model['login_tried_admin']->register('ip', new CharField(255));

Webmodel::$model['login_tried_admin']->register('num_tried', new IntegerField(11));
Webmodel::$model['login_tried_admin']->register('time', new IntegerField(11));

Webmodel::$model['moderators_module']=new Webmodel('moderators_module');

Webmodel::$model['moderators_module']->register('moderator', new ForeignKeyField(Webmodel::$model['user_admin']), 1);
Webmodel::$model['moderators_module']->components['moderator']->name_field_to_field='user_admin';

Webmodel::$model['moderators_module']->components['moderator']->fields_related_model=array('user_admin');

Webmodel::$model['moderators_module']->register('idmodule', new ChoiceField($size=255, $type='string', $arr_values=array(), $default_value=''), 1);

Webmodel::$model['moderators_module']->components['idmodule']->unique=1;


class ModuleAdmin {

	//Example: 'admin' => array('admin', 'admin')

	static public $arr_modules_admin=array();

}

?>