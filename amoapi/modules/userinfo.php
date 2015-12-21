<?php
/**
 * amoCRM класс
 * Получение информации о менеджерах
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 * @version 1.01
 */
class userinfo {
    /**
	 * Менеджеры аккаунта по id
     */
	private $user_id = array();
    /**
	 * Менеджеры аккаунта по имени
     */
	private $user_names = array();
    /**
	 * Менеджеры аккаунта по email
     */
	private $user_logins = array();
	
	//=======================================================
	
	public function __construct(){}
	
	/**
	 * Установка параметров
	 */
	public function setConfig( $cnf ){
	
		foreach( $cnf['account']->users as $user ){
		
			$this->user_id[$user->id] = $user;
			if( !empty( $user->last_name )) $user->name = $user->name.' '.$user->last_name;
			$this->user_name[trim($user->name)] = $user->id;
			$this->user_logins[$user->login] = $user->id;
		}
	}
	
	/**
	 * Получение информации о менеджере по id
	 */
	public function byId( $user_id ){
		
		if( isset( $this->user_id[$user_id] )) return $this->user_id[$user_id];
		return (object) array( 'name' => $user_id );
	}
	
	/**
	 * Получение информации о менеджере по имени
	 */
	public function byName( $user_name, $info = true ){
		
		if( isset( $this->user_name[trim($user_name)] )){
			if( $info ) return $this->byId($this->user_name[$user_name]); else return $this->user_name[$user_name];
		}
		return false;
	}
	
	/**
	 * Получение информации о менеджере по логину
	 */
	public function byLogin( $user_login ){
		
		if( isset( $this->user_logins[$user_login] )) return $this->user_logins[$user_login];
		return 1;
	}
	
	/**
	 * Получение массива пользователей
	 */
	public function users(){
		
		return $this->user_id;
	}
	
	/**
	 * Инициализация
	 */
	public function inits( $entity ){}
}
?>