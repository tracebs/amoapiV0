<?php
/**
 * Добавление компаний
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class company_Set extends set {
	
	/**
	 * Имя компании
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
	
	/**
	 * Добавление
	 */
	public function run(){
			
		$this->bind();
		$data = array();
		if( empty( $this->request )) $this->error( 1002 );
		$data['request']['contacts']['add'] = $this->request;

		$resp = $this->query( 'post', '/private/api/v2/json/'.$this->entity.'/set', $data )->getResp();
		if( !isset( $resp->server_time )) $this->error( 1004, $this->entity, $this->mode );
		$this->clear();
		if( isset( $resp->contacts->add[0]->id )) return $resp->contacts->add[0]->id;
		return true;
	}
}
?>