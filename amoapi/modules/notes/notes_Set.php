<?php
/**
 * Добавление примечаний
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class notes_Set extends set {
	
	/**
	 * Текст примечания
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
	 * Тип примечания
	 */
	public function type( $value ){
	
		if(!empty( $value )) $this->setValue( 'note_type', $value );
	}	
	
	/**
	 * Ответственный
	 */
	public function respId( $value ){

		if(!empty( $value )) $this->setValue( 'responsible_user_id', $value );
	}
}
