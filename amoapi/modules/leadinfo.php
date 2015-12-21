<?php
/**
 * amoCRM класс
 * Получение информации о сделках
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 * @version 1.00
 */
class leadinfo {
    /**
	 * В распределении
     */
	private $lead_status = array();
	
	//=======================================================
	
	public function __construct(){}
	
	/**
	 * Установка параметров
	 */
	public function setConfig( $cnf ){
	
		// статусы сделок
		foreach( $cnf['account']->leads_statuses as $k=>$status ){
		
			$status->pos = $k+1;
			$this->lead_status[$status->id] = $status;
		}
	}
	
	/**
	 * Получение статусов
	 */
	public function statuses(){
		
		return $this->lead_status;
	}
	
	/**
	 * Получение статуса сделки по id статуса
	 */
	public function status( $status_id ){
		
		if( isset( $this->lead_status[$status_id] )) return $this->lead_status[$status_id];
		return (object) array( 'name' => $status_id );
	}
	
	/**
	 * Инициализация
	 */
	public function inits( $entity ){}
}
?>