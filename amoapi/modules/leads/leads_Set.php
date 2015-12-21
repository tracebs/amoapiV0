<?php
/**
 * Добавление сделок
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class leads_Set extends set {
	
	/**
	 * Название сделки
	 */
	public function name( $value ){
		
		if(!empty( $value )) $this->setValue( 'name', $value );
	}
	
	/**
	 * Ключевые слова
	 */
	public function tags( $value ){
		
		if(!empty( $value )) $this->setValue( 'tags', $value );
	}
	
	/**
	 * ID статуса сделки
	 */
	public function statusId( $value ){
	
		if(!empty( $value )) $this->setValue( 'status_id', $value );
	}
	
	/**
	 * Статус сделки
	 */
	public function status( $value ){
	
		if( isset( $this->lead_status[$value] )) $this->setValue( 'status_id', $this->lead_status[$value] );
	}	
	
	/**
	 * Бюджет сделки
	 */
	public function price( $value ){

		if(!empty( $value )) $this->setValue( 'price', $value );
	}

	/**
	 * Установка временных custom данных для отправки
	 */
	public function custom( $name, $values ){
	
		if( !is_array( $values )){
			$values = array( $values );
		}
		$this->setCustom( $name, $values );
	}
	
	/**
	 * Ответственный
	 */
	public function respId( $value ){

		if(!empty( $value )) $this->setValue( 'responsible_user_id', $value );
	}
}
?>