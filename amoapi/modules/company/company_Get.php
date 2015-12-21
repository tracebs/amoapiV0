<?php
/**
 * Получение компаний
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class company_Get extends get {
	
	/**
	 * Поиск компаний
	 */
	public function search( $query, $name = false, $max = 0 ){
	
		$this->clear();
		$this->setParam( 'type', 'company' );
		
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
			foreach( $result as $company ){
					
				if( !isset( $company->custom[$name] )) continue;

				foreach( $company->custom[$name] as $cfval ){

					if( $this->format($cfval,$name) == $query ) return $company;
				}
			}
		}
	  return false;
	}
	
	/**
	 * Получение по ID
	 */
	public function byId( $id ){
	
		$this->clear();
		$this->setParam( 'type', 'company' );
		$this->setParam( 'id', $id );
		$this->setParam( 'limit_rows', 1 );
		
		if( $data = $this->run()){
			if( is_array($data) && count($data) === 1 ) return $data[0];
			return $data;
		}
		return '';
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
