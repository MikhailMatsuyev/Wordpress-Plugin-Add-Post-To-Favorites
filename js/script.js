jQuery(document).ready(function($){
     //alert('ok'); //for test includinf Jquery
     $('.mm-favorites-link a').click(function(e){

     	//alert(mmFavorites.url);

     	$.ajax({

     		type: 'POST',
     		//url: '/wp-admin/admin-ajax.php',
     		url:mmFavorites.url,
     		data:
     		{
     			security:mmFavorites.nonce,
     			action: 'mm_test',
     			postId: mmFavorites.postId
     		},
     		beforeSend: function(){
     			$('.mm-favorites-link a').fadeOut(300, function(){
     				$('.mm-favorites-hidden').fadeIn();     				
     			});
     		},
     		success: function(res)
     		{
     			$('.mm-favorites-link').html(res);
     			//console.log(res);
     		},
     		error: function()
     		{
     			alert('Ошибка');
     		}
     	});

     	//return false;//отменяем дефолтное поведение ссылки 1 вар
     	e.preventDefault();//отменяем дефолтное поведение ссылки 2 вар
     });
     	
});