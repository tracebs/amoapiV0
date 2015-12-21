<?php
/**
 * Обновление компаний
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class company_Update extends set {
    /**
	 * Данные по компании
     */
	protected $company;
    /**
	 * Добавление или обновление
     */
	protected $mode = 'update';
	
	/**
	 * Компания для обновления
	 */
	public function setCompany( $company ){
		
		$this->company = json_decode( json_encode( $company ), true );
		$this->setModif();
		$this->id( $this->company['id'] );
		
		if( empty( $this->company['custom_fields'] )) return false;
		
		foreach( $this->company['custom_fields'] as $cf_field ){
		
			$this->company['update']['custom'][$cf_field['name']] = array();
			
			foreach( $cf_field['values'] as $cf_item ){
			
				$this->company['setted'][$cf_field['name']][$cf_item['value']] = 1;
				$field = array( $cf_item['value'] );
				if( isset( $this->enum[$cf_item['enum']] )){
				
					$field[1] = $this->enum[$cf_item['enum']];
				}
				$this->company['update']['custom'][$cf_field['name']][] = $field;
			}
		}
	}
	
	/**
	 * Идентификатор компании
	 */
	public function id( $id ){
		
		$this->setValue( 'id', $id );
	}
	
	/**
	 * Время изменения
	 */
	public function setModif( $time = false ){
	
		if( !$time && $this->company['last_modified'] < time() ){
			$time = time();
		} elseif( !$time ){
			$time = $this->company['last_modified']+= 3;
		}
		$this->setValue( 'last_modified', $time );
	}
	
	/**
	 * Название компании
	 */
	public function name( $value ){
		
		$this->setValue( 'name', $value );
	}
	
	/**
	 * Ключевые слова
	 */
	public function tags( $value ){
		
		$this->setValue( 'tags', $value );
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
	 * Номер телефона
	 */
	public function phone( $values ){
	
		if( empty( $values )) return false;
		if( !is_array( $values ) || !is_array( $values[0] )){
			$values = array( $values );
		}
		foreach( $values as $value ){
		
			if( !is_array( $value )){
				$value = array( $value );
			}
			if( isset( $this->company['setted']['Телефон'][$value[0]] )) continue;
			$this->company['update']['custom']['Телефон'][] = $value;
		}
		$this->custom( 'Телефон', $this->company['update']['custom']['Телефон'] );
	}
	
	/**
	 * Электронная почта
	 */
	public function email( $values ){
	
	if( empty( $values )) return false;
		if( !is_array( $values ) || !is_array( $values[0] )){
			$values = array( $values );
		}
		foreach( $values as $value ){
		
			if( !is_array( $value )){
				$value = array( $value );
			}
			if( isset( $this->company['setted']['Email'][$value[0]] )) continue;
			$this->company['update']['custom']['Email'][] = $value;
		}
		$this->custom( 'Email', $this->company['update']['custom']['Email'] );
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

		$this->setValue( 'responsible_user_id', $value );
	}

	public function run(){
				
		$this->bind();
		$data = array();
		if( empty( $this->request )) $this->error( 1002 );
		$data['request']['contacts']['update'] = $this->request;
		$resp = $this->query( 'post', '/private/api/v2/json/'.$this->entity.'/set', $data )->getResp();
		$this->clear();
		return true;
	}
}