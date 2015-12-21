<?php
/**
 * Logger
 * Класс для логирования
 *
 * @name amo_Logger
 * @author Vlad Ionov <vlad@f5.com.ru>
 * @version 1.02
 */
class amo_Logger {
    /**
	 * имя лога
     */
	private static $_name = 'events';
    /**
	 * путь к логам
     */
	private static $_path = 'log';
    
	//=============================================
	
    /**
	 * запись в лог
     */
	public static function log( $data, $name = false ){
		
		$log_name = self::$_name;
		if( $name ){
			$log_name = $name;
		}
		if( is_array( $data )){
			$data = print_r( $data,1 );
		}
		$data = date( 'd M Y H:i:s', time()).' - '.$data."\r\n";
		file_put_contents( self::$_path.'/'.$log_name.'.log', $data, FILE_APPEND | LOCK_EX );
	}
	
    /**
	 * постановка пути лога
     */
	public static function setPath( $path ){
		
		if( !empty( $path )) self::$_path = $path;
	}
	
    /**
	 * постановка имени лога
     */
	public static function setName( $name ){
		
		if( !empty( $name )) self::$_name = $name;
	}
}
