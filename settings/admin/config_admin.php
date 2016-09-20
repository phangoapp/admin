<?php

PhangoApp\PhaI18n\I18n::load_lang('users');

ModuleAdmin::$arr_modules_admin[]=array('ausers', 'vendor/phangoapp/admin/controllers/admin/admin_ausers', PhangoApp\PhaI18n\I18n::lang('users', 'users_admin', 'Users admin'));

?>
