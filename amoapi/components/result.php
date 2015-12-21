<?php
/**
 * Обработка и выдача результата запроса
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class amoCRM_Result {

	private $data = array();
	private $filter = array();

	public function __construct( $data = '' ){
	
		$this->data = $data;
	}
	
	public function getRespId(){

		$responsId = (int) $this->data->responsible_user_id;
		
		if( isset( $responsId ) && $responsId > 1 ){
			return $responsId;
		} else {
			return null;
		}
	}
	
	public function data( $arr = false ){
	
		// фильтрация
		if( count($this->filter) > 0 && count($this->data) > 0 ){
			$this->data = $this->filterData( $this->data );
		}

		foreach( $this->data as $i=>$entity ){
			
			if( empty( $entity->custom_fields )) continue;

			$this->data[$i]->custom = array();
			
			foreach( $entity->custom_fields as $cfield ){

				$this->data[$i]->custom[$cfield->name][] = $cfield->values[0]->value;
			}
		}
		
		if( !$arr ) return $this->data;
		return json_decode(json_encode( $this->data ), true);
	}
	
	private function filterData(){
	
		$filted = array();
		foreach( $this->data as $item ){
			// фильтр по дате
			if( isset( $this->filter['date_create'] )){
				if( $item->date_create >= $this->filter['date_create']['from'] && $item->date_create <= $this->filter['date_create']['to'] ){
					$filted[] = $item;
				}
			}
		}
			return $filted;
	}
	
	public function setFilter( $arr ){
		
		foreach( $arr as $filter => $vals ){
			
			if( !isset( $this->filter[$filter] )) $this->filter[$filter] = $vals;
		}
	}
	
	public function clearFilter(){
		
		$this->filter = array();
	}
}