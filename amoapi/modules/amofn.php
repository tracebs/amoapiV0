<?php
/**
 * amoCRM класс
 * Функции для работы
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 */
class amofn {

	//=======================================================
	
	public function __construct(){}
	
	/**
	 * Получение месяца на русском
	 */
	public function monthRu( $date ){
		
		$a = array('/January/','/February/','/March/','/April/','/May/','/June/','/July/','/August/','/September/','/October/','/November/','/December/');
		$b = array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
		
		$date = preg_replace( $a, $b, $date );
		return $date;
	}
	
	public function setConfig( $cnf ){}
	public function inits( $entity ){}
}
