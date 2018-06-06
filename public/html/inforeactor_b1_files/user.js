$(document).ready(function(){

	$("#commentThis").click(function () {

		$('html, body').animate({
			scrollTop: $(".comments_conatiner").offset().top - 60
		}, 1000);

	});

	$('.tab').on('click', function(){
		var tab = $(this);
		$('.tab').removeClass('active');
		$(this).addClass('active');
        $('.news_item_column').empty();
        PostsHistory.get();


		//$('.tab, .graphic_block').toggleClass('active');
	});

    // Добавление адреса сайта к копируемому в буфер тексту
    document.addEventListener('copy', function(e) {

        // Текст, который добавится к копируемому
        var hostName = "...\nИсточник: " + document.location.href;

        // Получаем то, что пользователь уже скопировал
        var selectedText = "";
        if(window.getSelection){
            selectedText = window.getSelection().toString();
        }
        else if(document.getSelection){
            selectedText = document.getSelection();
        }
        else if(document.selection){
            selectedText = document.selection.createRange().text;
        }

        // Создаем текст, который положим в буфер
        var textToPutOnClipboard = selectedText + hostName;

        // Определяем IE это или нет и устанавливаем текст в буфер
        if ((navigator.userAgent.toLowerCase().indexOf("msie") != -1
            || navigator.userAgent.toLowerCase().indexOf("trident") != -1)) {
            window.clipboardData.setData('Text', textToPutOnClipboard);
        } else {
            e.clipboardData.setData('text/plain', textToPutOnClipboard);
        }
        e.preventDefault();
    });

});
