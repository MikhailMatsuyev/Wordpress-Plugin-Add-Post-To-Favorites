<?php
//file for all functions definitions

/**
*is_single() - we situated in post? yes or no
*is_user_logged_in() - user logged in? yes or no
*/
function mm_favorites_content($content)
{
	if(!is_single() || !is_user_logged_in()) return $content;
	$img_src=plugins_url('img/loader.gif', __FILE__);
	return '<p class="mm-favorites-link"><span class="mm-favorites-hidden"><img src="'.$img_src.'"></span><a href = "#">Добавить в избранное</a></p>' . $content;
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
	//mm-favorites-scripts - название скрипта, для которого передаем данные
	//wp_create_nonce - Создает уникальный защитный ключ на короткий промежуток времени (24 часа). Для безопасности
	//в HTML увидим
	///* <![CDATA[ */
		//var mmFavorites = {"url":"http:\/\/wp.local\/wp-admin\/admin-ajax.php","nonce":"74ce77fb4f", "postId":"1"};
	/* ]]> */

	//Глобальная переменная WP, которая содержит все данные поста: название, id, 
	global $post;
	wp_localize_script('script', 'mmFavorites', ['url'=> admin_url('admin-ajax.php'), 'nonce'=>wp_create_nonce('mm-favorites'),'postId'=>$post->ID ]  );

}

//action fo handle ajax
function wp_ajax_mm_test()
{
	//Проверяем на совпадение секретной строки после AJAX запроса на сервер
	if(!wp_verify_nonce($_POST['security'], 'mm-favorites')){
		wp_die('Ошибка безопасности');
	}
	echo $_POST['postId'];
	wp_die('Запрос завершен');

}