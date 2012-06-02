$(document).ready(function() {
	if ( $('select').length ) {
		$('select')
			.selectBox()
			.focus(function() {
				$(this).selectBox('showMenu');
			})
	}

	if ( $('input:radio').length ) {
		$('input:radio').checkbox();	
	}

	if ( $('input:checkbox').length ) {
		$('input:checkbox').checkbox();
	}
	
	/*$('a.selectBox').on('click', function() {
		if ( $(this).hasClass('selectBox-menuShowing') ) {
			$('ul.selectBox-dropdown-menu').addClass( $(this).prev().attr('id')  );
			$('ul.selectBox-dropdown-menu li').removeClass('selectBox-hover');
		}
	})*/
	
    if ( $('input[placeholder]').length ) {
	Placeholder.init({
        normal: "#000000",
        placeholder: "c0c0c0"
    });
    }
	
	$("#file_input").change(function(){
		document.getElementById('fileInputText').value = this.value;
	});
	$("#file_input").mouseenter(function(){
		$("#browse_button").addClass("hover");
	});
	$("#file_input").mouseout(function(){
		$("#browse_button").removeClass("hover");
	});
	
	$("#browse_button").click(function() {
		$("#file_input").click();
		return false;
	})
	
	$("#b-new-theme__browse_button").click(function() {
		$("#b-new-theme__file-upload").click();
		return false;
	})
	
	/* Лайки */
	
	$('.b-like').on('mouseenter', function() {
		if ($(this).hasClass('i-like_small') && (!$(this).children('.b-like__heart_red').length )  ) {
			$(this).children('i').html('Нравится?');
		}
	})
	
	$('.b-like').on('mouseleave', function() {
		if ($(this).hasClass('i-like_small') && (!$(this).children('.b-like__heart_red').length )  ) {
			$(this).children('i').html('');
		}
	})
	
	$('.b-like').on('click', function() {
		if ((!$(this).children('.b-like__heart_red').length )  ) {
			$(this).children('i').html('Мне нравится');
			$(this).find('.b-like__num').text( $(this).find('.b-like__num').text()*1 + 1  );
		} else {
			$(this).children('i').html('Нравится?');	
			$(this).find('.b-like__num').text( $(this).find('.b-like__num').text()*1 - 1  );
		}
	
		$(this).children('.b-like__heart').toggleClass('b-like__heart_red');
		

	})
	
	$('.b-like-project').on('click', function() {

		if (!$(this).hasClass('i-like')) {
			$(this).find('i').html('Мне нравится!');
			$(this).find('.b-like-count').text( $(this).find('.b-like-count').text()*1 + 1  );
		} else {
			$(this).find('i').html('Нравится?');
			$(this).find('.b-like-count').text( $(this).find('.b-like-count').text()*1 - 1  );
		}
		
		$(this).toggleClass('i-like');
	})
	
	/* Закрыть на сообщении */
	
	$('.b-groups__items-close, .i-discuss__items-restore').on('click', function() {
		$(this).parents('.b-discuss__items').find('.i-cont_notif-no, .b-discuss__items-cont_notif').toggle();
	
		return false;
	})
	
	
});



