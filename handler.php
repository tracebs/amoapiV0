<?php
/**
 * amoCRM - Интегратор заявок в амо
 * Прием данных с сайта!
 *
 * @author Vlad Ionov <vlad@f5.com.ru>
 * @developer Evgeny Vakhrushev <ev@f5.com.ru>
 */
define( 'ROOT', dirname(__FILE__));
header('Access-Control-Allow-Origin: *'); 
header('Content-Type:text/html; charset=utf8'); 

include ROOT.'/amoapi/amo.class.php';
$amo = new amoCRM();

// входные данные
if( !$data = json_decode( stripslashes( $_REQUEST['data'] ))) exit('Forbidden');

// имя
if( empty( $data->name )) $data->name = 'Новый контакт';
// номер
if( empty( $data->phon )) $data->phon = '';
// email
if( empty( $data->mail )) $data->mail = '';
// сообщение
if( empty( $data->text )) $data->text = '';

if( !empty( $_SERVER['HTTP_REFERER'] )){
	$url = parse_url( urldecode( $_SERVER['HTTP_REFERER'] ));
	if( !empty( $url['query'] )){
		parse_str( $url['query'], $utm );
	}
}

// ищем контакт по номеру телефона
if(!empty( $data->phon )){
	$contact = $amo->contacts_Get->search( $data->phon, 'Телефон' );
	// или по email
} elseif(!empty( $data->mail )){
	$contact = $amo->contacts_Get->search( $data->mail, 'Email' );
} else {
	exit('search params empty');
}

// если контакт найден
if( is_object($contact) && isset( $contact->id )){
	$contact_id = $contact->id;
	$resp_id = $contact->responsible_user_id;
	// если контакт не найден
} else {
	$contact_id = false;
	$resp_id = 594492;
}

// добавление контакта
if( !$contact_id ){
	
	// создание сделки
	$leadSet = $amo->leads_Set;
	$leadSet->name( 'Новая сделка (сайт www.sailid.ru)' );
	$leadSet->status( 'Первичный контакт' );
	$leadSet->respId( $resp_id );
	$leadSet->price( 0 );
	$lead_id = $leadSet->run();
	
	// создание контакта
	$setContact = $amo->contacts_Set;
	$setContact->name( $data->name );
	$setContact->respId( $resp_id );
	$setContact->leads( $lead_id );
	$setContact->phone( $data->phon );
	$contact_id = $setContact->run();
	
	// создание задачи
	$taskSet = $amo->tasks_Set;
	$taskSet->respId( $resp_id );
	$taskSet->text( 'Позвонить' );
	$taskSet->type( 'Follow-up' );
	$taskSet->elemType( 'contact' );
	$taskSet->elemId( $contact_id );
	$taskSet->toTime( 1 );
	$taskSet->run();

	// работа с найденным контактом
} else {
	
	// создание задачи
	$taskSet = $amo->tasks_Set;
	$taskSet->respId( $resp_id );
	$taskSet->text( 'Повторная заявка. Позвонить' );
	$taskSet->type( 'Follow-up' );
	$taskSet->elemType( 'contact' );
	$taskSet->elemId( $contact_id );
	$taskSet->toTime( 1 );
	$task_id = $taskSet->run();
}

echo "\r\ndone";