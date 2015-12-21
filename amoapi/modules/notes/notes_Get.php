<?php
/**
 * Получение примечаний
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class notes_Get extends get {
	
	/**
	 * Поиск по типу примечания
	 */
	public function search( $type, $type_id ){
	
		$this->clear();
		$this->setParam( 'type', $type );
		$this->setParam( 'note_type', $type_id );
		
		if( $data = $this->run()){
			if( is_array($data) && count($data) === 1 ) return $data[0];
			return $data;
		}
		return '';
	}
	
	/**
	 * Получение по ID
	 */
	public function byId( $id ){
	
		$this->clear();
		$this->setParam( 'id', $id );
		
		if( $data = $this->run()){
			if( is_array($data) && count($data) === 1 ) return $data[0];
			return $data;
		}
		return '';
	}

}
?>