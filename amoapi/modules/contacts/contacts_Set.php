<?php
/**
 * Добавление контактов
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class contacts_Set extends set {
	
	/**
	 * Имя контакта
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
	 * Связанные сделки
	 */
	public function leads( $values ){
	
		if( !is_array( $values )){
			$values = array( $values );
		}
		$this->setValue( 'linked_leads_id', $values );
	}
	
	/**
	 * Связанные компании
	 */
	public function company( $value ){
	
		$this->setValue( 'linked_company_id', $value );
	}
	
	/**
	 * Номер телефона
	 */
	public function phone( $values ){

		if(!empty( $values )) $this->custom( 'Телефон', $values );
	}
	
	/**
	 * Электронная почта
	 */
	public function email( $values ){
	
		if(!empty( $values )) $this->custom( 'Email', $values );
	}
	
	/**
	 * Мгн.сообщения
	 */
	public function im( $name, $value ){

		$this->custom( 'Мгн. сообщения', array( $value, strtoupper($name) ));
	}
	
	/**
	 * Установка временных custom данных для отправки
	 */
	public function custom( $name, $values, $replace = false, $multi = false ){
	
		if( !is_array( $values )){
			$values = array( $values );
		}
		$this->setCustom( $name, $values, $replace, $multi );
	}
	
	/**
	 * Ответственный
	 */
	public function respId( $value ){

		if(!empty( $value )) $this->setValue( 'responsible_user_id', $value );
	}
	
}
?>