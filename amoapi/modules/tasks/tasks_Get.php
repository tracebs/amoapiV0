<?php
/**
 * Получение задач
 *
 * @author Michael Krasnov <krasnov@f5.com.ru>
 */
class tasks_Get extends get {
	
	/**
	 * Поиск по типу задачи
	 */
	public function search( $type = false){
		
		$this->clear();
		if ($type){
			$this->setParam( 'type', $type );
		};
		
		if( $data = $this->run()){
			if( is_array($data) && count($data) === 1 ) return $data[0];
			return $data;
		}
		return '';
	}
	
	/**
	 * Получение по ответственному пользователю
	 */
	public function byRespId( $respId, $type=false ){
		
		$this->clear();
		if ($type){
			$this->setParam( 'type', $type );
		}
		$this->setParam( 'responsible_user_id', $respId );
		
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