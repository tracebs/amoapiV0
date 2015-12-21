<?php
/**
 * Получение сделок
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class leads_Get extends get {
	/**
	 * Хранение данных
	 */
	private $data = array();
	/**
	 * Хранение ID для получения
	 */
	private $ids = array();
	
	/**
	 * Поиск сделок
	 */
	public function search( $query, $name = false, $max = 0 ){
	
		$this->clear();
		
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
			foreach( $result as $lead ){
					
				if( !isset( $lead->custom[$name] )) continue;

				foreach( $lead->custom[$name] as $cfval ){
					
					if( $this->format($cfval,$name) == $query ) return $lead;
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
		$this->setParam( 'id', $id );
		
		if( is_array( $id )){
			
			$max = 349;
			$this->ids = $id;
			
			while( count($this->ids) > 0 ){
			
				$i = 0;
				$part = array();
				
				foreach( $this->ids as $k=>$lid ){
					
					$part[] = $lid;
					unset( $this->ids[$k] );
					$i++;
					if( $i == $max ) break;
				}
				
				$this->setParam( 'id', $part );
				$resp = $this->run();
				
				foreach( $resp as $item ){
					
					$this->data[] = $item;
				}
			}
			return $this->data;
			
		} elseif( !empty( $id )){
			
			$this->setParam( 'limit_rows', 1 );
			$data = $this->run();
			
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