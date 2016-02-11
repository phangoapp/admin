<?php

use PhangoApp\PhaRouter\Controller;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaLibs\AdminUtils;
use PhangoApp\PhaLibs\LoginClass;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaView\View;

I18n::load_lang('phangoapp/admin');
Webmodel::load_model('vendor/phangoapp/admin/models/models_admin');
Utils::load_config('config_admin', 'settings/admin');

class indexController extends Controller {

	static public $login;

	public function home($module_id='none', $submodule_id='')
	{
        
        if($submodule_id!='')
        {
        
            $module_id=basename(Utils::slugify($module_id)).'/'.basename(Utils::slugify($submodule_id));
        
        }
        
		class_alias('indexController', 'AdminSwitchClass');
		
		AdminSwitchClass::$login=new LoginClass(Webmodel::$model['user_admin'], 'username', 'password', '', $arr_user_session=array('IdUser_admin', 'privileges_user', 'username', 'token_client'), $arr_user_insert=array('username', 'password', 'repeat_password', 'email'));
		
		AdminSwitchClass::$login->field_key='token_client';
		
		ob_start();
		
		//global $model, $lang, PhangoVar::$base_url, PhangoVar::$base_path, $user_data, $arr_module_admin, $config_data, $arr_block, $original_theme, $module_admin, $header;
		
		$header='';
		$content='';
		
		I18n::load_lang('admin');
		//load_libraries(array('utilities/set_admin_link'));

		//settype($module_id, 'string');
		
		$module_id=Utils::slugify($module_id, 1);

		$extra_urls=array();

		//Make menu...
		//Admin was internationalized
		
		if(AdminSwitchClass::$login->check_login())
		{
            
			LoginClass::$session['user_admin']['token_client']=sha1(LoginClass::$session['user_admin']['token_client']);
			
			//variables for define titles for admin page

			$arr_son_module=array();
			
			$title_admin=I18n::lang('admin', 'admin', 'Admin');
			$title_module=I18n::lang('admin', 'home', 'Home');
			
			$content='';

			$name_modules=array();

			$urls=array();
			
			$arr_permissions_admin=array();
			$arr_permissions_admin['none']=1;

			$module_admin=array();

			$arr_admin_script['none']=array('admin', 'phangoapp/admin');
			
			//Define $module_admin[$module_id] for check if exists in database the module

			$module_admin[$module_id]='AdminIndex';
			
			//I18n::$lang[$module_admin[$module_id].'_admin']['AdminIndex_admin_name']=ucfirst(I18n::lang('admin', 'admin', 'Admin'));
			
			//0=> name in uri, 1 => route to script, 2 name of script
			
			$title_admin=I18n::lang('admin', 'admin', 'Admin');
			
			foreach(ModuleAdmin::$arr_modules_admin as $ser_admin_script)
			{	
				
				//load little file lang with the name for admin. With this you don't need bloated with biggest files of langs...
				
				$idmodule=$ser_admin_script[0];
				
				$name_module=$idmodule;
				
				if(gettype($ser_admin_script[1])=='string')
                {
                
                    $name_modules[$name_module]=$ser_admin_script[2];

                    $arr_admin_script[$idmodule]=$ser_admin_script;    
                    
                    $urls[$name_module]=AdminUtils::set_admin_link($idmodule, array());

                    $module_admin[$idmodule]=$name_module;
                    
                    $arr_permissions_admin[$idmodule]=1;
				
				}
				else
				{
                    
                    $name_modules[$name_module]=$ser_admin_script[2];
				
                    //unset(ModuleAdmin::$arr_modules_admin[$idmodule]['title']);
				
                    foreach($ser_admin_script[1] as $ser_admin_script_son)
                    {
                    
                        $idmodule_son=$ser_admin_script_son[0];
                    
                        $name_module_son=$idmodule_son;
				
                        $name_modules[$name_module_son]=$ser_admin_script_son[2];

                        $arr_admin_script[$idmodule_son]=$ser_admin_script_son;    
                        
                        $urls[$name_module_son]=AdminUtils::set_admin_link($idmodule_son, array());

                        $module_admin[$idmodule_son]=$name_module_son;
                        
                        $arr_permissions_admin[$idmodule_son]=1;
				
                    }
				
				}
			
			}

			if(!isset($arr_admin_script[ $module_id ]))
			{
			
				//Need show error.
                $this->route->response404();
            
                die;
			
			}
			
			$file_include=Routes::$base_path.'/vendor/'.$arr_admin_script[ $module_id ][1].'/controllers/admin/admin_'.basename($arr_admin_script[ $module_id ][0]).'.php';
			
			if(LoginClass::$session['user_admin']['privileges_user']==1)
			{
			
				$arr_permissions_admin=array();
				$arr_module_saved=array();
				$arr_module_strip=array();
				
				$arr_permissions_admin[$module_id]=0;
				$arr_permissions_admin['none']=1;
			
				$query=Webmodel::$model['moderators_module']->select('where moderator='.$_SESSION['IdUser_admin'], array('idmodule'), 1);
				
				while(list($idmodule_mod)=$model['moderators_module']->fetch_row($query))
				{
				
					//settype($idmodule_mod, 'integer');
					
					$arr_permissions_admin[$idmodule_mod]=1;
					
					$arr_module_saved[]=$module_admin[$idmodule_mod];
					
				}
				
				$arr_module_strip=array_diff( array_keys($name_modules), $arr_module_saved );
				
				foreach($arr_module_strip as $name_module_strip)
				{
					
					unset($name_modules[$name_module_strip]);
					unset($urls[$name_module_strip]);
				
				}
				
				
			}
			
			if(file_exists($file_include) && $module_admin[$module_id]!='' && $arr_permissions_admin[$module_id]==1)
			{
				
				include($file_include);

				$func_admin=basename($module_admin[$module_id]).'Admin';
				
				if($module_id!='none')
				{
                    $title_admin=$name_modules[$module_id];
				}
				
				if(function_exists($func_admin))
				{	
                    
                    echo '<h1>'.$title_admin.'</h1>';
                    
					$extra_data=$func_admin();
					
					settype($extra_data, 'array');
					
					$extra_data=array_merge($extra_data, $arr_son_module);
					
				}
				else
				{
					
					throw new Exception('Error: no exists function '.ucfirst($func_admin).' for admin application');

				}

			}
			else if($module_admin[$module_id]!='' && $arr_permissions_admin[$module_id]==1)
			{
				
				$output=ob_get_contents();
				
				ob_clean();

				throw new Exception('Error: no exists file '.$file_include.' for admin application');
				
				die;


			}
			else
			{
                
                $this->route->response404();
			
				die;
			
			}

			$content=ob_get_contents();
		
			ob_end_clean();
			
			if(AdminUtils::$show_admin_view==true)
			{
                echo View::load_view(array('header' => $header, 'title' => I18n::lang('admin', 'admin_zone', 'Admin zone'),     'content' => $content, 'name_modules' => $name_modules, 'urls' => $urls , 'extra_data' => $extra_data), 'admin/admin');
                
            }
            else
            {
            
                echo $content;
            
            }

		}
		else
		{	
			
			$url=Routes::make_simple_url(ADMIN_FOLDER.'/login/check');;
			
			die( header('Location: '.$url, true ) );
			
		}
        
	}
	
}

function get_admin_modules($arr_admin_script, $name_modules, $urls, $module_admin, $arr_permissions_admin)
{

    $arr_admin_script[$idmodule]=$ser_admin_script;
                
    $name_module=$idmodule;

    $dir_lang_admin=$name_module.'/';

    if($arr_admin_script[$idmodule][0]!=$arr_admin_script[$idmodule][1])
    {

        $dir_lang_admin=$arr_admin_script[$idmodule][0].'/';

    }

    I18n::load_lang($dir_lang_admin.$name_module.'_admin');
    
    if(!isset(I18n::$lang[$name_module.'_admin'][$name_module.'_admin_name']))
    {

        $name_modules[$name_module]=ucfirst($name_module);
        I18n::$lang[$name_module.'_admin'][$name_module.'_admin_name']=ucfirst($name_modules[$name_module]);
    
    }
    else
    {
        
        $name_modules[$name_module]=ucfirst(I18n::$lang[$name_module.'_admin'][$name_module.'_admin_name']);

    }

    $urls[$name_module]=AdminUtils::set_admin_link($idmodule, array()); //(PhangoVar::$base_url, 'admin', 'index', $name_module, array('IdModule' => $idmodule));

    $module_admin[$idmodule]=$name_module;
    
    $arr_permissions_admin[$idmodule]=1;
    
    return [$arr_admin_script, $name_modules, $urls, $module_admin, $arr_permissions_admin];

}

?>