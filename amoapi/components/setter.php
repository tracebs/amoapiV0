<?php
/**
 * amoCRM класс
 * Добавление в amoCRM
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class set extends amoCRM {
    /**
	 * Сущность
     */
	protected $entity;
    /**
	 * Данные для отправки
     */
	protected $request = array();
    /**
	 * Временные данные
     */
	protected $temp = array();
    /**
	 * Дополнительные поля по name
     */
	protected $custom = array();
    /**
	 * Добавление или обновление
     */
	protected $mode = 'add';
    /**
	 * Enum id и их значения
     */
	protected $enum = array();
    /**
	 * Статусы сделок
     */
	protected $lead_status = array();
    /**
	 * Типы примечаний
     */
	protected $note_type = array();
    /**
	 * Типы задач
     */
	protected $task_type = array();
	
	//=======================================================
	
	public function __construct( $entity = false ){
		/**
		 * С чем работаем
		 */
		$this->setEntity( $entity );
		/**
		 * Установка начальных параметров
		 */
		$this->clear();
	}
	
	/**
	 * Установка временных данных для отправки
	 */
	public function setValue( $key, $value ){
		
		$this->temp[$key] = $value;
	}
	
	/**
	 * Установка временных custom данных для отправки
	 */
	public function setCustom( $name, $values, $replace = false, $multi = false ){

		if( !isset( $this->custom[$name] )){
			$this->error( 1003, $name );
		}
		
		if( !$multi && !is_array( $values[0] )) $values = array($values);
		$custom_values = array();
		
		foreach( $values as $val ){
			if( $multi ){
				if( !empty( $this->custom[$name]->enums->{$val} )){
					$custom_values[] = $this->custom[$name]->enums->{$val};
				}
			} else {
				if( !isset( $val[1] )) $val[1] = 'OTHER';
				$custom_values[] = array(
					'value' => $val[0],
					'enum' => $val[1]
				);
			}
		}
		if( $replace ){
			if(!empty( $this->temp['custom_fields'] )){
				foreach( $this->temp['custom_fields'] as $k=>$c_field ){
					if( $c_field['id'] == $this->custom[$name]->id ) unset( $this->temp['custom_fields'][$k] );
				}
			}
		}
		$this->temp['custom_fields'][] = array(
												'id' => $this->custom[$name]->id,
												'name' => $name,
												'values' => $custom_values
											);
	}
	
	/**
	 * Множественное добавление
	 */
	public function bind(){
		
		if( !empty( $this->temp )){
			$this->request[] = $this->temp;
			$this->temp = array();
		}
	  return $this->request;
	}
	
	/**
	 * Добавление
	 */
	public function run( $request = false ){
		
		$this->bind();
		$data = array();
		if( $request ) $this->request = $request;
		if( empty( $this->request )) return false;
		
		$data['request'][$this->entity][$this->mode] = $this->request;
		$resp = $this->query( 'post', '/private/api/v2/json/'.$this->entity.'/set', $data );
		$resp = $resp->getResp();

		if( !isset( $resp->server_time )) $this->error( 1004, $this->entity, $this->mode );
		$this->clear();
		
		if( isset( $resp->{$this->entity}->{$this->mode}[0]->id )) return $resp->{$this->entity}->{$this->mode}[0]->id;
		if( $this->mode == 'update' && isset( $resp->server_time )) return $resp->server_time;

		return false;
	}
	
	/**
	 * Массовое добавление
	 */
	public function mrun( $request = false ){
		
		$this->bind();
		$p = 0; $i = 0;
		
		$parts = array();
		$results = array();
		
		foreach( $this->request as $request ){
			$parts[$p][] = $request;
			if( $i == 250 ){
				$i = 0;
				$p++;
			} else {
				$i++;
			}
		}
		foreach( $parts as $part ){
			$this->run( $part );
		}
	}
	
	/**
	 * Установка сущности
	 */
	protected function setEntity( $entity ){
	
		if( $entity ) $this->entity = $entity;
	}
	
	/**
	 * Инициализация
	 */
	protected function inits( $entity ){

		if( $entity ) $this->entity = $entity;
		$custom_entity = $this->entity;
		if( $this->entity == 'company' ) $custom_entity = 'companies';
		// дополнительные поля
		if( isset( $this->config['account']->custom_fields->{$custom_entity} )){
		
			foreach( $this->config['account']->custom_fields->{$custom_entity} as $custom_field ){
			
				if( !empty( $custom_field->enums )){
				
						foreach( $custom_field->enums as $eid=>$enam ){
							$eid = (int) $eid;
							$this->enum[$eid] = $enam;
						}
					$custom_field->enums = $this->objFlip( $custom_field->enums );
				}
				$this->custom[$custom_field->name] = $custom_field;
			}
		}
		
		// статусы сделок
		foreach( $this->config['account']->leads_statuses as $least ){
			
			$this->lead_status[$least->name] = $least->id;
		}
		
		// типы примечаний
		foreach( $this->config['account']->note_types as $noty ){
			
			$this->note_type[$noty->code] = $noty->id;
		}
		
		// типы задач
		foreach( $this->config['account']->task_types as $tasky ){
			
			$this->task_type[$tasky->name] = $tasky->id;
		}
	}
	
	/**
	 * Очистка данных для отправки
	 */
	public function clear(){
		
		$this->request = array();
		$this->temp = array();
	}
	
	/**
	 * Очистка данных для отправки
	 */
	protected function objFlip( $obj ){
		$obj = (array) $obj;
		return (object) array_flip( $obj );
	}
}
