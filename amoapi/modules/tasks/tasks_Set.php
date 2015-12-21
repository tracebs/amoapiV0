<?php
/**
 * Добавление задач
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class tasks_Set extends set {
	
	/**
	 * Текст задачи
	 */
	public function text( $value ){
		
		if(!empty( $value )) $this->setValue( 'text', $value );
	}
	
	/**
	 * Тип привязываемого елемента
	 */
	public function elemType( $value ){
	
		$element = array( 'contact' => 1, 'lead' => 2, 'company' => 3 );
		if(!empty( $value )) $this->setValue( 'element_type', $element[$value] );
	}
	
	/**
	 * ID привязываемого елемента
	 */
	public function elemId( $value ){
	
		if(!empty( $value )) $this->setValue( 'element_id', $value );
	}
	
	/**
	 * Тип задачи
	 */
	public function type( $value ){
	
		if( isset( $this->task_type[$value] )) $this->setValue( 'task_type', $this->task_type[$value] );
	}	
	
	/**
	 * Время на задачу в минутах
	 */
	public function toTime( $value ){
	
		$time = time()+($value*60);
		if(!empty( $value )) $this->setValue( 'complete_till', $time );
	}
	
	/**
	 * Время на задачу timestamp
	 */
	public function setTime( $value ){
	
		if(!empty( $value )) $this->setValue( 'complete_till', $value );
	}
	
	/**
	 * Время на задачу Y-m-d H:i:s
	 */
	public function setDate( $value ){
	
		if(!empty( $value )) $this->setValue( 'complete_till', strtotime( $value ));
	}
	
	/**
	 * Дата создания
	 */
	public function created( $value ){
	
		if(!empty( $value )) $this->setValue( 'date_create', $value );
	}
	
	/**
	 * Статус задачи
	 */
	public function status( $value ){
	
		if(!empty( $value )) $this->setValue( 'status', $value );
	}
	
	/**
	 * Ответственный
	 */
	public function respId( $value ){

		if(!empty( $value )) $this->setValue( 'responsible_user_id', $value );
	}
}
