<?php
/**
 * ��������� ������ ����� ���������� � ��������
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class contacts_Links extends get {
	
	/**
	 * ��������� ������ �� ���������
	 */
	public function leadFrom( $values ){
	
		$this->clear();
		$this->setTyp( 'links' );
		
		if( !is_array( $values )){
			$values = array( $values );
		}
		$this->setParam( 'contacts_link', $values );
		if( $data = $this->run()){
		
			if( isset( $data[0] )){
			
				$geted = array();
				foreach( $data as $item ){
					
					$geted[$item->contact_id][] = $item->lead_id;
				}
				return $geted;
			}
		}
		return '';
	}
	
	/**
	 * ��������� ��������� �� �������
	 */
	public function contactFrom( $values ){
	
		$this->clear();
		$this->setTyp( 'links' );
		
		if( !is_array( $values )){
			$values = array( $values );
		}
		$this->setParam( 'deals_link', $values );
		if( $data = $this->run()){
		
			if( isset( $data[0] )){
			
				$geted = array();
				foreach( $data as $item ){
					
					$geted[$item->lead_id][] = $item->contact_id;
				}
				return $geted;
			}
		}
		return '';
	}
}
?>