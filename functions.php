<?php
//file for all functions definitions

/**
*is_single() - we situated in post? yes or no
*is_user_logged_in() - user logged in? yes or no
*/
function mm_favorites_content($content)
{
	if(!is_single() || !is_user_logged_in()) return $content;
	return '<p class="mm-favorites-link"><a href = "#">Добавить в избранное</a></p>' . $content;
}

function mm_favorites_scripts()
{
	if(!is_single() || !is_user_logged_in()) return;
	//wp_enqueue_script() - Правильно подключает скрипт (JavaScript файл) на страницу.
	//script - script.js
	//plugins_url - Получает URL на директорию плагинов (без слэша на конце)
	//array('jquery') - system path to jquery lib
	//null - удаляет версию jquery из адреса
	//true - строка подлключения скрипта в html будет в футере

	wp_enqueue_script('script', plugins_url('/js/script.js', __FILE__), array('jquery'), null,true);

	//hook for include css or js into html
	wp_enqueue_style ('style_css',  plugins_url('/css/style.css', __FILE__));

}