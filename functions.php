<?php
//file for all functions definitions

function mm_favorites_dashboard_widget(){
	wp_add_dashboard_widget('mm_favorites_dashboard', 'Ваш список избранного', 'mm_show_dashboard_widget');


}

//функция для добавления работы с виджетом
function mm_show_dashboard_widget(){
	$user = wp_get_current_user();
	$favorites = get_user_meta($user->ID, 'mm_favotites');
	if(!$favorites){
		echo "Список избранного пока пуст";
		return;

	}
	$img_src=plugins_url('img/loader.gif', __FILE__);
	//$str=implode(',',$favorites);
	//$mm_posts = get_posts(['include'=>$str]); для вывода, если нужно, любой инфо о посте (картинку, текста, ...)
	//var_dump($mm_posts);
	echo '<ul>';
	foreach($favorites as $favorite)
	{

		echo '<li>
				<a href="' .get_permalink($favorite).'">'.get_the_title($favorite).'</a>
				<span><a href="#" data-post="'.$favorite.'" class = "mm-favorites-del">&#10008;</a></span>
				
				<span class="mm-favorites-hidden"><img src="'.$img_src.'"></span>
			 </li>';
		/*$data[$favorite]=
		[
			'title'=>get_the_title($favorite),
			'link'=>get_permalink($favorite)
		];*/
	}
	echo '</ul>';

}




/**
*is_single() - we situated in post? yes or no
*is_user_logged_in() - user logged in? yes or no
*/
function mm_favorites_content($content)
{
	if(!is_single() || !is_user_logged_in()) return $content;
	$img_src=plugins_url('img/loader.gif', __FILE__);

	global $post;
	if(mm_is_favorites($post->ID)){
		return '<p class="mm-favorites-link"><span class="mm-favorites-hidden"><img src="'.$img_src.'"></span><a data-action ="del" href = "#">Удалить из избранного</a></p>' . $content;
	}


	return '<p class="mm-favorites-link"><span class="mm-favorites-hidden"><img src="'.$img_src.'"></span><a data-action ="add" href = "#">Добавить в избранное</a></p>' . $content;
}

//Для подключения скриптов и стилей только дл ядминской части только для главной страницы (для нее стартовый файл index.php). Для других страниц админской части нужно подпирать var_dump($hook)
function mm_favorites_admin_scripts($hook)
{
	//echo $hook - стартовый файл скрипта index.php и т.д.

	if($hook!='index.php')return;
	wp_enqueue_script('mm-favorites-admin-scripts', plugins_url('/js/mm-favorites-admin-scripts.js', __FILE__), array('jquery'),null,true);
	wp_enqueue_style('mm-favorites-admin-style', plugins_url('/css/mm-favorites-admin-style.css', __FILE__));
	//Проверить подключение скрипта так: создать файл, который подключаем, поместить там body{	color:red;} и перейти в админ часть. Часть текста будет красным.


	//В админ части уже определена переменная ajaxurl = '/wp-admin/admin-ajax.php'; Проверить можно, перейдя в админ часть и нажав F12. В пользовательской части она не определена, поэтому нужно вручну ее добавлять
	//mm-favorites-admin-scripts - имя файла
	wp_localize_script('mm-favorites-admin-scripts', 'mmFavorites', ['nonce'=>wp_create_nonce('mm-favorites')]  );


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
//returns answer to ajax
function wp_ajax_mm_add()
{
	//Проверяем на совпадение секретной строки после AJAX запроса на сервер
	if(!wp_verify_nonce($_POST['security'], 'mm-favorites')){
		wp_die('Ошибка безопасности');
	}
	$post_id = (int)$_POST['postId'];
	$user = wp_get_current_user();

	//Если в таблице в БД есть информация о добавленной статье, 
	//то программа завершит работу
	if(mm_is_favorites($post_id)) wp_die();

	//add_user_meta() - добавить в таблицу wordpress.wp_usermeta  к-л данные о пользователе:
	//2(user_id) 	mm_favotites(meta_key)-переменная 	6(meta_value)-id статьи
	if (add_user_meta($user->ID, 'mm_favotites', $post_id)){
		wp_die('Пост добавлен в избранное!');
	};
	wp_die('Запрос завершен');

}

function wp_ajax_mm_del()
{
	//Проверяем на совпадение секретной строки после AJAX запроса на сервер
	if(!wp_verify_nonce($_POST['security'], 'mm-favorites')){
		wp_die('Ошибка безопасности');
	}
	$post_id = (int)$_POST['postId'];
	$user = wp_get_current_user();

	//Если в таблице в БД нет информации о добавленной статье, 
	//то программа завершит работу
	if(!mm_is_favorites($post_id)) wp_die();

	//delete_user_meta() - удалить из таблицы wordpress.wp_usermeta  к-л данные о пользователе:
	//2(user_id) 	mm_favotites(meta_key)-переменная 	6(meta_value)-id статьи
	if (delete_user_meta($user->ID, 'mm_favotites', $post_id)){
		wp_die('Удалено');
	};
	wp_die('Ошибка удаления');

}


//Проверяем, есть ли уже данные о избранной статье для конкретного юзера в 
//wordpress.wp_usermeta
function mm_is_favorites($post_id){
	$user = wp_get_current_user();
	//get_user_meta()-Получить значение данных пользователя (mm_favotites) 
	//по его id и названию переменной 
	//из таблицы wordpress.wp_usermeta, т.е. получить значение переменной mm_favotites=
	$favorites = get_user_meta($user->ID, 'mm_favotites');

	foreach ($favorites as $favorite) {
		if($favorite==$post_id) return true;
	}

	return false;
}