<?php
/*
load_libraries(array('login'));
load_model('admin');
load_config('admin');
*/

use PhangoApp\PhaRouter\Controller;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaLibs\AdminUtils;
use PhangoApp\PhaLibs\LoginClass;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaView\View;

Webmodel::load_model('modules/admin/models/models_admin');
Utils::load_config('config_admin', 'modules/admin/settings/');
I18n::load_lang('admin');

#Utils::load_libraries('loginclass');
#Utils::load_libraries(array('utilities/set_admin_link'));

class indexController extends Controller {

	static public $login;

	public function home($module_id='none')
	{
        
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
		
			AdminSwitchClass::$login->session['token_client']=sha1(AdminSwitchClass::$login->session['token_client']);
			
			//variables for define titles for admin page

			$title_admin=I18n::lang('admin', 'admin', 'Admin');
			$title_module=I18n::lang('admin', 'home', 'Home');
			
			$content='';

			$name_modules=array();

			$urls=array();
			
			$arr_permissions_admin=array();
			$arr_permissions_admin['none']=1;

			$module_admin=array();

			$arr_admin_script['none']=array('admin', 'admin');
			
			//Define $module_admin[$module_id] for check if exists in database the module

			$module_admin[$module_id]='AdminIndex';
			
			I18n::$lang[$module_admin[$module_id].'_admin']['AdminIndex_admin_name']=ucfirst(I18n::lang('admin', 'admin', 'Admin'));
			
			foreach(ModuleAdmin::$arr_modules_admin as $idmodule => $ser_admin_script)
			{	
				$name_module=$idmodule;
				
				$arr_admin_script[$idmodule]=$ser_admin_script;
				
				//load little file lang with the name for admin. With this you don't need bloated with biggest files of langs...

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
			
			}

			
			if(!isset($arr_admin_script[ $module_id ]))
			{
			
				//Need show error.
			
				die;
			
			}
			
			$file_include=Routes::$base_path.'/modules/'.$arr_admin_script[ $module_id ][0].'/controllers/admin/admin_'.$arr_admin_script[ $module_id ][1].'.php';
			
			if(AdminSwitchClass::$login->session['privileges_user']==1)
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

				$func_admin=$module_admin[$module_id].'Admin';
				
				if(function_exists($func_admin))
				{	

					echo '<h1>'.I18n::$lang[$module_admin[$module_id].'_admin'][$module_admin[$module_id].'_admin_name'].'</h1>';

					$extra_data=$func_admin();

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
			
				die;
			
			}

			$content=ob_get_contents();
		
			ob_end_clean();
			
			echo View::load_view(array('header' => $header, 'title' => I18n::lang('admin', 'admin_zone', 'Admin zone'), 'content' => $content, 'name_modules' => $name_modules, 'urls' => $urls , 'extra_data' => $extra_data), 'admin/admin');

		}
		else
		{	
			
			$url=Routes::make_url('login');
			
			die( header('Location: '.$url, true ) );
			
		}
	
	}
	
}

?>