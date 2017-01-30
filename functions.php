<?php
//file for all functions definitions

/**
*is_single() - we situated in post? yes or no
*is_user_logged_in() - user logged in? yes or no
*/
function mm_favorites_content($content){
	if(!is_single() || !is_user_logged_in()) return $content;
	return '<p class="mm-favorites-link"><a href = "#">Добавить в избранное</a></p>' . $content;
}