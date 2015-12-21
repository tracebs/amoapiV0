<?php
/**
 * Класс для работы с amoCRM
 *
 * @name amoCRM
 * @author Vlad Ionov <vlad@f5.com.ru>
 * @version 3.16
 */
class amoCRM {
    /**
	 * Конфигурация
     */
	protected $config;
    /**
	 * Класс запросов
     */
	protected static $_query;
    /**
	 * Метка авторизации
     */
	public $authorized = false;
	/*
	 * Регистр модулей
     */
	private $sub = array();
	
	//=======================================================
	
	public function __construct( $account = false ){
	
		define( 'AMOAPP', dirname(__FILE__));
		define( 'AMODULES', AMOAPP.'/modules');
		define( 'AMOTEMP', AMOAPP.'/temp');
		/**
		 * Установка данных по аккаунту
		 */
		if( !$account ) include( AMOAPP.'/config/account.php');
		/**
		 * Установка пользовательских параметров
		 */
		$this->setConfig( $account );
		/**
		 * Первичная инициализация
		 */
		$this->init();
	}
	
	/**
	 * Первичная инициализация
	 */
	private function init(){
		/**
		 * Первичная инициализация
		 */
		if( is_null( self::$_query )){
		/**
		 * Инициалиация класса запросов
		 */
			include( AMOAPP.'/components/query.php');
		/**
		 * Инициалиация класса ответа сервера
		 */
			include( AMOAPP.'/components/response.php');
		/**
		 * Инициалиация класса обработки результата запроса
		 */
			include( AMOAPP.'/components/result.php');
		/**
		 * Инициалиация класса получения информации
		 */
			include( AMOAPP.'/components/getter.php');
		/**
		 * Инициалиация класса добавления информации
		 */
			include( AMOAPP.'/components/setter.php');
		/**
		 * Инициалиация класса логирования
		 */
			include( AMOAPP.'/components/logger.php');
			amo_Logger::setPath( AMOTEMP );
		/**
		 * Регистр классов amoCRM
		 */
			include( AMOAPP.'/components/registry.php');
			amo_Registry::set( 'amo', $this );
		}
		/**
		 * Класс запросов
		 */
		self::$_query = new amoCRM_Query();
		/**
		 * Авторизация
		 */
		$this->authorize();
	}
	
	/**
	 * Установка пользовательских параметров
	 */
	protected function setConfig( $cnf ){
	
		if( is_array( $cnf )) $this->config = $cnf;
	}
	
	/**
	 * Установка заголовка last modified
	 */
	public function setModified( $time ){
	
		self::$_query->setModified( $time );
	}
	
    /**
	 * Запрос к серверу
     */
    protected function query( $type, $url, $args = false, $from = false ){
	
		return self::$_query->$type( 
			(object) array(
						'domain' => $this->config['domain'],
						'login' => $this->config['login'],
						'hash' => $this->config['hash'],
					),
			$url, $args, $from
		);
    }
	
    /**
	 * Получение модуля
     */
    public function get( $entity ){
	
		$get = new get( $entity );
		$get->setConfig( $this->config );
		return $get;
    }
	
    /**
	 * Добавление модуля
     */
    public function set( $entity ){
	
		$set = new set( $entity );
		$set->setConfig( $this->config );
		$set->init();
		return $set;
    }
	
    /**
	 * Авторизация
     */
	private function authorize(){

		// сразу конфигурация аккаунта
		$data = $this->query('get','/private/api/v2/json/accounts/current');
		$resp = $data->getResp();
		if( !isset( $resp->account )) $this->error( 1000, $data->getCode());
		
		$this->config['account'] = $resp->account;
		$this->authorized = true;
		return true;
	}

    /**
	 * Удобное обращение
	 * Автоподгрузка модулей
     *
     */
    public function __get( $val ){
	
		if( isset( $this->sub[$val] )){
			return $this->sub[$val];
		} else {
			return $this->setModule( $val );
		}
	}
	
    /**
	 * Определение модулей
     */
    private function setModule( $class_name ){
		
		$entity = false;
		$class_path = '';
		if( strpos( $class_name, '_')){
			$names = explode( '_', $class_name );
			$class_path = $names[0].'/';
			$entity = $names[0];
		}

		if(!file_exists( AMOAPP.'/modules/'.$class_path.$class_name.'.php')) $this->error( 999, $class_name );
		if(!class_exists( $class_name )) include( AMOAPP.'/modules/'.$class_path.$class_name.'.php');
		if( isset( $this->sub[$class_name] )) unset( $this->sub[$class_name] );
		
		$this->sub[$class_name] = new $class_name();
		$this->sub[$class_name]->setConfig( $this->config );
		$this->sub[$class_name]->inits( $entity );
		return $this->sub[$class_name];
    }
	
    /**
	 * Обработка кодов ошибок
     */
    protected function error( $key, $text = '' ){
		
		$error = array(
			999  => 'Module is not exists',
			1000 => 'Unauthorized',
			1001 => 'Error load data',
			1002 => 'Empty set data',
			1003 => 'Invalid custom field',
			1004 => 'Error set'
		);
		exit( $error[$key].' '.$text );
	}
}
