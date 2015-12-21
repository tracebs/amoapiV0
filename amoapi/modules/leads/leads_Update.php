<?php
/**
 * Обновление сделок
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class leads_Update extends set {
    /**
	 * Данные по сделке
     */
	protected $lead;
    /**
	 * Добавление или обновление
     */
	protected $mode = 'update';
	
	/**
	 * Сделка для обновления
	 */
	public function setLead( $lead ){
		
		$this->lead = json_decode( json_encode( $lead ), true );
		$this->setModif();
		$this->id( $this->lead['id'] );
		
		if( empty( $this->lead['custom_fields'] )) return false;
		
		foreach( $this->lead['custom_fields'] as $cf_field ){
		
			$this->lead['update']['custom'][$cf_field['name']] = array();
			
			foreach( $cf_field['values'] as $cf_item ){
			
				$this->lead['setted'][$cf_field['name']][$cf_item['value']] = 1;
				$field = array( $cf_item['value'] );
				
				if( isset( $this->enum[$cf_item['enum']] )){
				
					$field[1] = $this->enum[$cf_item['enum']];
				}
				$this->lead['update']['custom'][$cf_field['name']][] = $field;
			}
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
	
		if( !$time && $this->lead['last_modified'] < time() ){
			$time = time();
		} elseif( !$time ){
			$time = $this->lead['last_modified']+= 3;
		}
		$this->setValue( 'last_modified', $time );
	}
	
	/**
	 * Название сделки
	 */
	public function name( $value ){
		
		if( !empty( $value )) $this->setValue( 'name', $value );
	}
	
	/**
	 * Теги
	 */
	public function tags( $value ){
		
		if( !empty( $value )) $this->setValue( 'tags', $value );
	}
	
	/**
	 * Теги
	 */
	public function price( $value ){
		
		if( !empty( $value )) $this->setValue( 'price', $value );
	}
	
	/**
	 * Статус сделки
	 */
	public function status( $value ){
	
		if( isset( $this->lead_status[$value] )) $this->setValue( 'status_id', $this->lead_status[$value] );
	}	
	
	/**
	 * Ключевые слова
	 */
	public function leads( $values ){
	
		if( !is_array( $values )){
			$values = array( $values );
		}
		$this->setValue( 'linked_leads_id', $values );
	}
	
	
	/**
	 * Установка временных custom данных для отправки
	 */
	public function custom( $name, $values, $replace = false ){
	
		if( !is_array( $values )){
			$values = array( $values );
		}
		$this->setCustom( $name, $values, $replace );
	}
	
	/**
	 * Ответственный
	 */
	public function respId( $value ){

		if( !empty( $value )) $this->setValue( 'responsible_user_id', $value );
	}
	
}
?>