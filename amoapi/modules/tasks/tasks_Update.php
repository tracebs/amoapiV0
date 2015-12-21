<?php
/**
 * Обновление задач
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class tasks_Update extends set {
    /**
	 * Данные по сделке
     */
	protected $task;
    /**
	 * Добавление или обновление
     */
	protected $mode = 'update';
	
	/**
	 * Сделка для обновления
	 */
	public function settask( $task ){
		
		$this->task = json_decode( json_encode( $task ), true );
		$this->setModif();

		$this->setValue( 'id', $this->task['id'] );
		$this->setValue( 'element_type', $this->task['element_type'] );
		$this->setValue( 'element_id', $this->task['element_id'] );
		$this->setValue( 'task_type', $this->task['task_type'] );
		$this->setValue( 'text', $this->task['text'] );
		$this->setValue( 'complete_till', $this->task['complete_till'] );
	}
	
	/**
	 * Идентификатор контакта
	 */
	public function id( $id ){
		
		$this->setValue( 'id', $id );
	}
	
	/**
	 * Время изменения
	 */
	public function setModif( $time = false ){
	
		if( !$time && $this->task['last_modified'] < time() ){
			$time = time();
		} elseif( !$time ){
			$time = $this->task['last_modified']+= 3;
		}
		$this->setValue( 'last_modified', $time );
	}
	
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
	public function created( $value ){
	
		if(!empty( $value )) $this->setValue( 'status', $value );
	}
	
	/**
	 * Ответственный
	 */
	public function respId( $value ){
		
		if(!empty( $value )) $this->setValue( 'responsible_user_id', $value );
	}
}
