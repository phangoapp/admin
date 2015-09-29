<?php

use PhangoApp\PhaRouter\Controller;
use PhangoApp\PhaRouter\Routes;
use PhangoApp\PhaView\View;
use PhangoApp\PhaModels\Webmodel;
use PhangoApp\PhaI18n\I18n;
use PhangoApp\PhaUtils\Utils;
use PhangoApp\PhaLibs\LoginClass;

Webmodel::load_model('modules/admin/models/models_admin');

class LoginController extends Controller {

	protected $login;

	public function __construct($route, $name)
	{
	
		//parent::__construct($route);
		
		$this->route=$route;
	
		$this->login=new LoginClass(Webmodel::$model['user_admin'], 'email', 'password', '', $arr_user_session=array('IdUser_admin', 'privileges_user'), $arr_user_insert=array('email', 'user_admin', 'password', 'repeat_password' ));
	
		$this->login->field_name='email';
	
		$this->login->url_login=Routes::make_url('login', 'login');//make_fancy_url(PhangoVar::$base_url, 'admin', 'login_check', array());
		
		$this->login->url_insert=Routes::make_url('login', 'register');//make_fancy_url(PhangoVar::$base_url, 'admin', 'register_insert', array(1));
		
		$this->login->url_recovery=Routes::make_url('login', 'recovery');//make_fancy_url(PhangoVar::$base_url, 'admin', 'recovery_password', array(1));
		
		$this->login->url_recovery_send=Routes::make_url('login', 'recovery_send'); //make_fancy_url(PhangoVar::$base_url, 'admin', 'recovery_password_send', array(1));
		
		$this->login->accept_conditions=0;
		
		$this->login->field_key='token_client';
		
		if(!defined('SENDER_EMAIL'))
		{
		
            throw new \Exception('Need define SENDER_EMAIL constant for send basic emails');
		
		}
		
		$this->login->sender=SENDER_EMAIL;
		
		parent::__construct($route, $name);
		
	}

	public function home()
	{
		ob_start();
		
		$c_users=Webmodel::$model['user_admin']->select_count();
		
		if($c_users>0)
		{
			
				$this->login->login_form();
				
				$cont_index=ob_get_contents();
				
				ob_end_clean();
				
				echo View::load_view(array($cont_index), 'loginadmin');
			
		}
		else
		{
			
			$url_return=Routes::make_url('login', 'register');
			
			Routes::redirect($url_return);
		
		}
	
	}
	
	public function login()
	{
	
		settype($_POST['no_expire_session'], 'integer');
		settype($_POST['email'], 'string');
		settype($_POST['password'], 'string');
		
		if(!$this->login->login($_POST['email'], $_POST['password'], $_POST['no_expire_session']))
		{
			
			ob_start();
				
			$this->login->login_form();
			
			$cont_index=ob_get_contents();
			
			ob_end_clean();
			
			echo View::load_view(array($cont_index), 'loginadmin');
		
		}
		else
		{
			
			$url_return=Routes::make_url('index');
			
			Routes::redirect($url_return);
		
		}
	
	}
	
	public function recovery()
	{
	
		if(!$this->login->check_login())
		{
	
			ob_start();
				
			$this->login->recovery_password_form();
				
			$cont_index=ob_get_contents();
			
			ob_end_clean();
			
			echo View::load_view(array($cont_index), 'loginadmin');
			
		}
	
	}
	
	public function recovery_send()
	{
	
		if(!$this->login->check_login())
		{
	
			ob_start();
				
			$this->login->recovery_password();
				
			$cont_index=ob_get_contents();
			
			ob_end_clean();
			
			echo View::load_view(array($cont_index), 'loginadmin');
			
		}
	
	}
	
	public function register($update=0)
	{
	
		$c_users=Webmodel::$model['user_admin']->select_count();
		
		if($c_users==0)
		{
			
			ob_start();
			
			if(Routes::$request_method=='GET')
			{
				
				$this->login->create_account_form();
				
			}
			else
			if(Routes::$request_method=='POST')
			{
			
				if($this->login->create_account())
				{
					View::set_flash(I18n::lang('admin', 'user_added_success', 'The user was added successfully'));
					
					$url_return=Routes::make_url('login');
			
					Routes::redirect($url_return);
				
				}
				else
				{
					
					$this->login-> create_account_form();
				
				}
			
			}
			
			$cont_index=ob_get_contents();
			
			ob_end_clean();
			
			echo View::load_view(array($cont_index), 'loginadmin');
		
		}
	
	}
	
	public function logout()
	{
	
		ob_start();
			
		$this->login->logout();
		
		$cont_index=ob_get_contents();
		
		ob_end_clean();
		
		Routes::redirect( Routes::make_url('login') );
	
	}
	

}

?>
