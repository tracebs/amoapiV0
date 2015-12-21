<?php
/**
 * ���������� ������
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class leads_Set extends set {
	
	/**
	 * �������� ������
	 */
	public function name( $value ){
		
		if(!empty( $value )) $this->setValue( 'name', $value );
	}
	
	/**
	 * �������� �����
	 */
	public function tags( $value ){
		
		if(!empty( $value )) $this->setValue( 'tags', $value );
	}
	
	/**
	 * ID ������� ������
	 */
	public function statusId( $value ){
	
		if(!empty( $value )) $this->setValue( 'status_id', $value );
	}
	
	/**
	 * ������ ������
	 */
	public function status( $value ){
	
		if( isset( $this->lead_status[$value] )) $this->setValue( 'status_id', $this->lead_status[$value] );
	}	
	
	/**
	 * ������ ������
	 */
	public function price( $value ){

		if(!empty( $value )) $this->setValue( 'price', $value );
	}

	/**
	 * ��������� ��������� custom ������ ��� ��������
	 */
	public function custom( $name, $values ){
	
		if( !is_array( $values )){
			$values = array( $values );
		}
		$this->setCustom( $name, $values );
	}
	
	/**
	 * �������������
	 */
	public function respId( $value ){

		if(!empty( $value )) $this->setValue( 'responsible_user_id', $value );
	}
}
?>