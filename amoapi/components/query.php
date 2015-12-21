<?php
/**
 * amoCRM класс
 * Запросы к серверу
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class amoCRM_Query {
    /**
	 * curl
     */
	private $curl;
    /**
	 * LastModified
     */
	private $modif;
    /**
	 * last time
     */
	private $last_time;
    /**
	 * log requests
     */
	private $logs = false;
	
	//=======================================================

	public function __construct(){
		/**
		 * start time
		 */
		$this->last_time = microtime(1);
	}
	
	/**
	 * init curl
	 */
	private function curlInit(){
		
		if( !is_null( $this->curl )) curl_close( $this->curl );
		$this->curl = curl_init();
		
		curl_setopt( $this->curl, CURLOPT_USERAGENT, 'Chrome/41.0.2272.118 YaBrowser/15.4.2272.3716' );
		curl_setopt( $this->curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $this->curl, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $this->curl, CURLOPT_SSL_VERIFYHOST, 0 );
	}
	
    /**
	 * LastModified
     */
	public function setModified( $time ){
		
		$datetime = new DateTime( date( 'Y-m-d H:i:s', $time ));
		$this->modif = gmdate("D, d M Y H:i:s", $datetime->getTimestamp() );
		return $this->modif;
	}
	
    /**
	 * Формирование ссылки
     */
    private function createUrl( $account, $url, $data = array()){
	
		$link = 'https://'.$account->domain.'.amocrm.ru'.$url.'?USER_LOGIN='.$account->login.'&USER_HASH='.$account->hash.'&lang=ru';
		if( !empty( $data ) && !isset( $data['request'] )){
			$link .= '&'.http_build_query( $data );
		}
		  return $link;
    }
	
    /**
	 * GET запрос
     */
	public function get( $account, $method, $args = array(), $modified = false ){
	
		$this->curlInit();
		$url = $this->createUrl( $account, $method, $args );
		
		curl_setopt( $this->curl, CURLOPT_URL, $url );
		if( $modified ){
			$since = $this->setModified( $modified );
			curl_setopt( $this->curl, CURLOPT_HTTPHEADER, array( 'if-modified-since: '.$since ));
			$args['if-modified-since'] = $since;
		}
		$response = $this->response();
		$this->log( 'GET', $account->domain, $url, $args, $response );
		
		return new amoCRM_Response( $response['data'], $response['code'] );
	}
	
    /**
	 * POST запрос
     */
	public function post( $account, $method, $args, $modified ){
	
		$this->curlInit();
		$url = $this->createUrl( $account, $method, $args );
		
		curl_setopt( $this->curl, CURLOPT_URL, $url );
		curl_setopt( $this->curl, CURLOPT_CUSTOMREQUEST, 'POST' );
		curl_setopt( $this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt( $this->curl, CURLOPT_POSTFIELDS, json_encode( $args ));
		
		$response = $this->response();
		$this->log( 'POST', $account->domain, $url, $args, $response );
		
		return new amoCRM_Response( $response['data'], $response['code'] );
	}
	
    /**
	 * Ответ сервера
     */
	private function response(){
	
		$latency = $this->getLatency();
		if( $latency > 0 && $latency < 1 ){
			usleep( (1-$latency)*1000000 );
		}
		$resp = array(
			'data' => curl_exec( $this->curl ),
			'code' => curl_getinfo( $this->curl, CURLINFO_HTTP_CODE )
		);
		$this->last_time = microtime(1);
		return $resp;
	}
	
    /**
	 * Получение времени запроса
     */
	private function getLatency(){
	
		return microtime(1) - $this->last_time;
	}
	
    /**
	 * Логирование запросов
     */
	public function log( $method, $domain, $url, $request, $response ){
		
		$data = array( 
					$method.' '.$url,
					'Request: '.print_r( $request,1 ),
					'Response: '.print_r( $response,1 ),
				);
		if( !$this->logs ) return false;
		amo_Logger::log( implode( "\r\n", $data ), $domain.'_requests' );
	}
}