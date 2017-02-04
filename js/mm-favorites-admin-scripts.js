jQuery(document).ready(function($){
	$('.mm-favorites-del').click(function(e){
		e.preventDefault();
		//alert(1);
		if(!confirm("Удалить?")) return false;
		//post=$(this).data('post');
		//alert(post);
		var post=$(this).data('post'),
			parent = $(this).parent(),
			loader = parent.next(),
			li = $(this).closest('li');

		$.ajax({

     		type: 'POST',
     		//url: '/wp-admin/admin-ajax.php',
     		url:ajaxurl,
     		data:
     		{
     			security:mmFavorites.nonce,
     			action: 'mm_del',//action - здесь переменная var action = $(this).data('action'); Приходит из 
                    // <a data-action ="del" или <a data-action ="add" functions.php (Для избежания дублирования кода)
     			postId: post
     		},
     		beforeSend: function(){
     			parent.fadeOut(300, function(){
     				loader.fadeIn();     				
     			});
     		},
     		success: function(res)
     		{
     			loader.fadeOut(300,function(){
     				li.html(res);
     			});
     			//console.log(res);
     		},
     		error: function()
     		{
     			alert('Ошибка');
     		}
     	});	
			//alert(1);
		//console.log('st');	
		//console.log(post);


	});


	// Обработка кнопки Удалить все избранное
	$('#mm-favorites-del-all').click(function(e){
		e.preventDefault();
		//alert(1);
		if(!confirm("Удалить?")) return false;
			var $this =$(this),
			loader=$this.next(),  //т.к. loader (картинка загрузки) вставлен после ссылке
			parent=$this.parent(),
			list=parent.prev();
			//console.log(list);
			$.ajax({

	     		type: 'POST',
	     		//url: '/wp-admin/admin-ajax.php',
	     		url:ajaxurl,
	     		data:
	     		{
	     			security:mmFavorites.nonce,
	     			action: 'mm_del_all',//action - здесь переменная var action = $(this).data('action'); 
	     			
	     		},
	     		beforeSend: function()
	     		{
	     			$this.fadeOut(300, function(){
	     				loader.fadeIn();     				
	     			});
	     		},
	     		success: function(res)
	     		{
	     			loader.fadeOut(300, function(){
	     				if(res==='List is clear'){
	     					parent.html(res);
	     					list.fadeOut();
	     				}else{
	     					$this.fadeIn();
	     					alert(res);
	     				}
	     				
	     			});
	     			//console.log(res);
	     		},
	     		error: function()
	     		{
	     			alert('Error');
	     		}
     	});	

	});	




});	