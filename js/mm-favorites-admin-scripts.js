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
});	