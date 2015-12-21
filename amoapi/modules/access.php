<?php
/**
 * amoCRM класс
 * Доступ к модулям по GET API hash
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class access {
    /**
	 * Ключ api
     */
	private $key;
    /**
	 * ID аккаунта амо
     */
	private $account_id;
	
	//=======================================================
	
	public function __construct(){}
	
	/**
	 * Установка параметров
	 */
	public function setConfig( $cnf ){
	
		/**
		 * ID аккаунта
		 */
		$this->account_id = $cnf['account']->id;
		/**
		 * Установка ключа доступа - первые 5 символов API хеша
		 */
		$this->key = substr( $cnf['hash'], 0, 5 );
	}
	
	/**
	 * Получение ключа доступа
	 */
	public function key(){
		
		return $this->key;
	}
	
	/**
	 * Инициализация
	 */
	public function inits( $entity ){}
}
?>