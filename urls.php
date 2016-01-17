<?php

use PhangoApp\PhaRouter\Routes;

//Routes::$urls['welcome\/([0-9]+)\/(\w+)']=array('index', 'page');

Routes::$urls[ADMIN_FOLDER.'\/(\w+)']=array('index', 'home');

?>