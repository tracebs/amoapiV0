<?php
/**
 * amoCRM класс
 * Получение из amoCRM
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class get extends amoCRM {
    /**
	 * Сущность
     */
	protected $entity;
    /**
	 * Параметры поиска
     */
	protected $search;
    /**
	 * Период от (метка)
     */
	protected $time_from = false;
    /**
	 * Листинг или связи
     */
	protected $typ = 'list';
	
	//=======================================================
	
	public function __construct( $entity = false ){
		/**
		 * С чем работаем
		 */
		$this->entity = $entity;
		/**
		 * Установка начальных параметров
		 */
		$this->clear();
	}
	
	/**
	 * За период от
	 */
	public function timeFrom( $time ){
		
		$this->time_from = $time;
	}
	
	/**
	 * Установка типа поиска
	 */
	public function setTyp( $typ ){
		
		$this->typ = $typ;
	}
	
	/**
	 * Установка параметра поиска
	 */
	public function setParam( $key, $value ){
		
		$this->search[$key] = $value;
	}
	
	/**
	 * Получение сущностей
	 */
	public function run(){
		
		$key = $this->entity;
		if( $this->typ == 'links' ) $key = $this->typ;
		if( $this->entity == 'company' ) $key = 'contacts';
		
		if( $this->search['limit_rows'] === 1 ){
			
			if( !$data = $this->getData()) return false;
			if( !empty( $data->{$key}[0] )){
				return $this->result( $data->{$key} )->data();
			}
		} else {
			$i = $this->search['limit_rows'];
			$geted = array();
			while( $i >= $this->search['limit_rows'] ){

				if( !$data = $this->getData()) break;
				$count = count( $data->{$key} );
				$i = $count;
				
				if( $i > 0 ){
					foreach( $data->{$key} as $entity ){
						$geted[] = $entity;
					}
				}
				if( $this->search['limit_rows'] == $i && $this->search['limit_rows'] < 500 ){
					break;
				} else {
					$this->search['limit_offset']+= $i;
				}
			}
			$data = $this->result( $geted )->data();
			return $data;
		}
	  return false;
	}
	
	/**
	 * Запрос для получения
	 */
	protected function getData(){

		$data =  $this->query( 'get', '/private/api/v2/json/'.$this->entity.'/'.$this->typ, $this->search, $this->time_from );
		if( is_object( $data )){
		
			return $data->getResp();
		}
		  return false;
	}
	
	/**
	 * Очистка формата поиска
	 */
	protected function clear(){
		
		$this->search = array(
			/**
			 * Лимит в 500 единиц за 1 запрос
			 */
			'limit_rows' => 500,
			/**
			 * Начальное смещение
			 */
			'limit_offset' => 0
		);
	}
	
	/**
	 * Обработка ответа
	 */
	protected function result( $data ){

		return new amoCRM_Result( $data );
	}
	
	/**
	 * Инициализация
	 */
	protected function inits( $entity ){
	
		if( $entity ) $this->entity = $entity;
	}
}
