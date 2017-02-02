<?php
//Main file of plugin. Name can use index.php, current plugin name (mm-favorites.php) or any other 
/*
Plugin Name: Добавление статей в Избранное
Description: Плагин добавляет для пользователей ссылку к статьям, позволяющую добавить статью в список избранных статей
Author: Михаил
Plugin URI: 
Version: 1.0
*/

//all functions we will put into file functions.php
require __DIR__ . '/functions.php';

add_filter('the_content', 'mm_favorites_content');
add_action('wp_enqueue_scripts', 'mm_favorites_scripts');
add_action('wp_ajax_mm_add', 'wp_ajax_mm_add');
add_action('wp_ajax_mm_del', 'wp_ajax_mm_del');

//Регистрируем добавление нового виджета. Для этого вешаем на хук wp_dashboard_setup() нашу функцию mm_favorites_dashboard_widget()
add_action('wp_dashboard_setup', 'mm_favorites_dashboard_widget');

add_action('admin_enqueue_scripts', 'mm_favorites_admin_scripts');
