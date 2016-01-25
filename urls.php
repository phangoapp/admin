<?php

use PhangoApp\PhaRouter\Routes;

//Routes::$urls['welcome\/([0-9]+)\/(\w+)']=array('index', 'page');

Routes::$urls[ADMIN_FOLDER.'\/login\/recovery_send']=array('login', 'recovery_send');

Routes::$urls[ADMIN_FOLDER.'\/login\/register']=array('login', 'register');

Routes::$urls[ADMIN_FOLDER.'\/login\/recovery']=array('login', 'recovery');

Routes::$urls[ADMIN_FOLDER.'\/login\/check']=array('login', 'login');

Routes::$urls[ADMIN_FOLDER.'\/login']=array('login', 'home');

Routes::$urls[ADMIN_FOLDER.'\/(\w+)']=array('index', 'home');

?>