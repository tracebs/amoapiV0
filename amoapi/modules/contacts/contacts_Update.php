<?php
/**
 * Получение контактов
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class contacts_Update extends set {
    /**
	 * Данные по контакту
     */
	protected $contact;
    /**
	 * Добавление или обновление
     */
	protected $mode = 'update';
	
	/**
	 * Контакт для обновления
	 */
	public function setContact( $contact ){
		
		$this->contact = json_decode( json_encode( $contact ), true );
		$this->setModif();
		$this->id( $this->contact['id'] );
		
		if( empty( $this->contact['custom_fields'] )) return false;

		foreach( $this->contact['custom_fields'] as $cf_field ){
		
			$this->contact['update']['custom'][$cf_field['name']] = array();
			$cf_values = array();

			foreach( $cf_field['values'] as $cf_item ){
				
				$field = array( $cf_item['value'] );
				if( isset($cf_item['enum']) && isset($this->enum[$cf_item['enum']]) && $this->enum[$cf_item['enum']] != $cf_item['value'] ){
					$field[1] = $this->enum[$cf_item['enum']];
				} else {
					$field[1] = 'OTHER';
				}
				$this->contact['update']['custom'][$cf_field['name']][] = $field;
				$cf_values[] = $field;
				
				if( $cf_field['name'] == 'Мгн. сообщения' ){
					$this->contact['setted'][$cf_field['name']][$field[1]][$field[0]] = 1;
				} else {
					$this->contact['setted'][$cf_field['name']][(string)$cf_item['value']] = 1;
				}
			}
			$this->custom( $cf_field['name'], $cf_values );
		}
	}
	
	/**
	 * Идентификатор контакта
	 */
	public function id( $id ){
		
		$this->setValue( 'id', $id );
	}
	
	/**
	 * Время изменения
	 */
	public function setModif( $time = false ){
	
		if( !$time && $this->contact['last_modified'] < time() ){
			$time = time();
		} elseif( !$time ){
			$time = $this->contact['last_modified']+= 3;
		}
		$this->setValue( 'last_modified', $time );
	}
	
	/**
	 * Мгн.сообщения
	 */
	public function im( $name, $value, $replace = false ){
		
		$name = strtoupper($name);
		if( $replace || !isset( $this->contact['setted']['Мгн. сообщения'][$name][$value] )){
		
			$this->custom( 'Мгн. сообщения', array( $value, $name ), $replace );
		}
	}
	
	/**
	 * Имя контакта
	 */
	public function name( $value ){
		
		if( !empty( $value )) $this->setValue( 'name', $value );
	}
	
	/**
	 * Ключевые слова
	 */
	public function tags( $value ){
		
		if( !empty( $value )) $this->setValue( 'tags', $value );
	}
	
	/**
	 * Прикрепленные сделки
	 */
	public function leads( $values ){
	
		if( !is_array( $values )){
			$values = array( $values );
		}
		$this->setValue( 'linked_leads_id', $values );
	}
	
	/**
	 * Номер телефона
	 */
	public function phone( $values, $replace = false ){
	
		if( empty( $values )) return false;
		if( !is_array( $values ) || !is_array( $values[0] )){
			$values = array( $values );
		}
		foreach( $values as $value ){
		
			if( !is_array( $value )){
				$value = array( $value );
			}
			if( $replace || !isset( $this->contact['setted']['Телефон'][(string)$value[0]] )){
			
				$this->custom( 'Телефон', $value, $replace );
			}
		}
	}
	
	/**
	 * Электронная почта
	 */
	public function email( $values, $replace = false ){
	
		if( empty( $values )) return false;
		if( !is_array( $values ) || !is_array( $values[0] )){
			$values = array( $values );
		}
		foreach( $values as $value ){
		
			if( !is_array( $value )){
				$value = array( $value );
			}
			if( $replace || !isset( $this->contact['setted']['Email'][(string)$value[0]] )){

				$this->custom( 'Email', $value, $replace );
			}
		}
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

		if( !empty( $value )) $this->setValue( 'responsible_user_id', $value );
	}
}
