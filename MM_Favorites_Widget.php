<?php
class MM_Favorites_Widget extends WP_Widget{

	//Настройка вида (название, описание) виджета в списе виджетов
	public function __construct(){
		$args=[
			'name' => 'Favorite posts',
			'description' => 'Showing list of favorite user posts'

		];

		//mm-favorites-widget - идентификатор, применяется в class
		parent::__construct('mm-favorites-widget','',$args);
	}

	//Форма виджета в админке
	public function form($instance){
		var_dump($instance);
		extract($instance);
		$title = !empty($title)? esc_attr($title):'Favorite posts';

		?>

			<p>
				<label for ="<?php echo $this->get_field_id('title') ?>">Title:</label>
				<input type="text" name="<?php echo $this->get_field_name('title')?> " value="<?php echo $instance['title'] ?>" id="<?php echo $this->get_field_id('title') ?>" class="widefat">

			</p>

		<?php

		
	}

	// виджет в пользовательской части
	//$args - массив разметки
	public function widget($args, $instance){
		if(!is_user_logged_in())return;

		echo $args['before_widget'];
		echo $args['before_title'];
		echo $instance['title'];
		echo $args['after_title'];
		mm_show_dashboard_widget();
		echo $args['after_widget'];
	}




}