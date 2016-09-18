<?php

use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaView\View;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaRouter\Routes;

function AdminView($header, $title, $content, $name_modules, $url_modules, $extra_data, $no_show_menu)
{

	View::$js[]='jquery.min.js';
    View::$css[]='font-awesome.min.css';
    
    View::$js_module['admin'][]='responsive-nav.min.js';
	
	View::$css_module['admin'][]='admin.css';
    View::$css_module['admin'][]='responsive-nav.css';
	
    ?>
    <!DOCTYPE html>
    <html>
    <head>                                                                                          
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <title><?php echo $title; ?></title>
    <?php echo View::load_css(); ?>
    <?php echo View::load_js(); ?>
    <?php echo View::load_header(); ?> 
    </head>
    <body>
    <div id="logout">
    <a href="${make_url('admin/logout')}"><i class="fa fa-power-off" aria-hidden="true"></i> Logout</a>
    </div>

    <div id="center_body">
        <div id="header">
            <a href="#nav" id="toggle"><i class="fa fa-bars" aria-hidden="true"></i><span>Menu</span></a>
            <span id="title_phango">Phango</span> <span id="title_framework">Framework!</span> 
            <div id="languages_general">
            <?php

            $arr_selected=array();



            foreach(I18n::$arr_i18n as $lang_item)
            {
                //set

                $arr_selected[Utils::slugify($lang_item)]='no_choose_flag_general';
                $arr_selected[Utils::slugify(I18n::$language)]='choose_flag_general';

                ?>
                <a class="<?php echo $arr_selected[Utils::slugify($lang_item)]; ?>" href="<?php echo Routes::make_module_url( 'lang', 'index', 'home', array('language' => $lang_item));?>"><img src="<?php echo View::get_media_url('images/languages/'.$lang_item.'.png'); ?>" alt="<?php echo $lang_item; ?>"/></a> 
                <?php

            }

            ?>
            </div>
        </div>
        <div class="content_admin">
            <nav id="menu" class="nav-collapse">
                <ul>
                    <li class="menu_title"><i class="fa fa-gear" aria-hidden="true"></i> <?php echo I18n::lang('phangoapp/admin', 'applications', 'Applications'); ?></li>
                    <?php

					foreach($name_modules as $key_module => $name_module)
					{
                        
                        if(!isset($no_show_menu[$key_module]))
                        {
                        
                            if(isset($url_modules[$key_module]))
                            {
                        
                            ?>
                            <li><a href="<?php echo $url_modules[$key_module]; ?>"><i class="fa fa-circle-o" aria-hidden="true"></i> <?php echo $name_module; ?></a></li>
                            <?php
                            
                            }
                            else
                            {
                            
                                echo '<li><div class="father_admin">'.$name_module.'</div></li>'; 
                            
                            }
						}
						//If have $key_module with an extra_url element from extra_data, put here.
						
						if(isset($extra_data['extra_url'][$key_module]))
						{
						
							foreach($extra_data['extra_url'][$key_module]['url_module'] as $key => $url_module)
							{
						
								?>
								<li><a class="sub_module" href="<?php echo $url_module; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-circle-o" aria-hidden="true"></i> <?php echo ucfirst($extra_data['extra_url'][$key_module]['name_module'][$key]); ?></a></li>
								<?php
							}
						
						}
					}

					?>
                </ul>
            </nav>
            <div class="contents">
                    <?php echo View::show_flash(); ?>
					<?php echo $content; ?>
            </nav>
        </div>
    </div>
    <div id="loading_ajax">
    </div>
    <script>
        var navigation = responsiveNav(".nav-collapse", {customToggle: "#toggle"});
    </script>
    </body>
    </html>

    <?php
    
}

?>
