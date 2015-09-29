<?php

use PhangoApp\PhaView\View;
use PhangoApp\PhaI18n\I18n;

function AdminIndexAdmin()
{
	
	echo View::load_view(array('title' => I18n::lang('admin', 'welcome_to_admin', 'Welcome to admin'), 'content' => 
	I18n::lang('admin', 'welcome_text', 'Welcome text')), 'admin/content');

}

?>