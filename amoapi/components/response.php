<?php
/**
 * amoCRM класс
 * Ответ сервера
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class amoCRM_Response {
    /**
	 * Код ответа сервера
     */
	private $code;
    /**
	 * Текст ответа сервера
     */
	private $data;
	
	//=======================================================

	public function __construct(  $data, $code ){
		/**
		 * Код ответа сервера
		 */
		$this->code = $code;
		/**
		 * Текст ответа сервера
		 */
		$this->data = json_decode( $data );
	}
	
    /**
	 * Код ответа сервера
     */
	public function getCode(){
		
		return $this->code;
	}
	
	/**
	 * Текст ответа сервера
	 */
	public function getResp(){
	
		if( isset( $this->data->response->error )) $this->error();
		if( isset( $this->data->response )) return $this->data->response;
		return $this->data;
	}
	
	/**
	 * Просмотр ошибки
	 */
	private function error(){
	
		echo "<br>\r\nОтвет сервера: ".$this->code.
			 "<br>\r\nТекст ответа: ".$this->data->response->error.
			 "<br>\r\nДата: ".date( 'd m Y H:i:s',$this->data->response->server_time ).
			 "<br>\r\n(".$this->data->response->server_time.")<br>\r\n";
	}
}