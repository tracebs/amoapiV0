<?php
/**
 * Получение контактов
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class contacts_Get extends get {
	
	/**
	 * Поиск контактов
	 */
	public function search( $query, $name = false, $max = 0 ){
	
		$this->clear();
		$this->setParam( 'type', 'contact' );
		
		if( $max > 0 ){
			$this->setParam( 'limit_rows', $max );
		}
		if( $name ){
			$query = $this->format( $query, $name );
		}
		$this->setParam( 'query', $query );
		$result = $this->run();

		if( !empty( $result )){
		
			if( !$name ){
				return $result;
			}
			foreach( $result as $contact ){
				
				if( !isset( $contact->custom[$name] )) continue;

				foreach( $contact->custom[$name] as $cfval ){

					if( $this->format($cfval,$name) == $query ) return $contact;
				}
			}
		}
	  return false;
	}
	
	/**
	 * Получение всех контактов
	 */
	public function all(){
		
		$this->clear();
		$this->setParam( 'type', 'contact' );
		return $this->run();
	}
	
	/**
	 * Получение по ID
	 */
	public function byId( $id = 0 ){
	
		if( empty( $id )){
			return false;
		}
		$this->clear();
		$this->setParam( 'type', 'contact' );
		$this->setParam( 'id', $id );
		$this->setParam( 'limit_rows', 1 );
		
		if( $data = $this->run()){
			if( is_array($data) && count($data) === 1 ) return $data[0];
			return $data;
		}
		return false;
	}
	
	/**
	 * Форматирование поискового запроса
	 */
	public function format( $query, $name = false ){
	
		if( $name == 'Телефон' && strlen( $query ) >= 10 ){
			$query = preg_replace('#[\s\-\(\)]+#Uis', '', $query );
			$query = strrev( substr( strrev( $query ), 0, 10 ));
		}
	  return $query;
	}
}
