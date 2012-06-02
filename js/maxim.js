$(document).ready(function() {
    $('.b-pr-viewer-viewer-img-self').click(function() {
		$(this).toggleClass('zoomed');
		$('.b-pr-viewer').toggleClass('zoomed');
		$('.b-project-left-menu').toggleClass('zoomed');
		return false;
	});
    
    $('.b-layout-messages__item-message-text').addClass("ellipsis");
    $('.b-chat__list .b-layout-messages__item-message-text__chat').addClass("ellipsis-chat");
    
    if ( $(".ellipsis").length ) {
    $(".ellipsis").dotdotdot({
        height: 51
    });
    }
    
    if ( $(".ellipsis-chat").length ) {
    $(".ellipsis-chat").dotdotdot({
        height: 20
    });
    }
    
    $('.b-layout-chat__item').on('click', function() {
        $(this).children('.b-layout-messages-chat-right').children('.b-layout-chat__item-message').children(".b-layout-messages__item-message-text__chat").trigger("destroy");
        $(this).removeClass('b-layout-messages__item_unread');
    });

    
    if ( $('.fancy').length ) {
		$('.fancy').fancybox({
        closeBtn: false,
        padding: 0
    });
	}
    
    if ( $('.fancy2').length ) {
		$('.fancy2').fancybox({
        padding: 0
    });
	}

    $('.b-new-more').mouseenter(function() { 
        $(this).parent().find('.b-white, .b-white-popup').show();
        $(this).addClass('b-new-more-act');
    })
    
    $('.b-new-more').mouseleave(function(e) {
    	if (($.browser.msie) && ($.browser.version == 7)) {
    		//return false;
    	}
    
    	if (!$(e.relatedTarget).hasClass('b-white-popup')) {
			$(this).find('.b-white').hide();
			$(this).next('.b-white-popup').hide();
			$(this).removeClass('b-new-more-act');
    	}
    })
    
        $('.b-new-more').parent().mouseleave(function(e) {
    	if (($.browser.msie) && ($.browser.version == 7)) {

			$(this).find('.b-white').hide();
			$(this).find('.b-white-popup').hide();
			$(this).find('.b-new-more').removeClass('b-new-more-act');
    	}
    	})

    
    $('.b-white-popup').mouseleave(function(e) {
    	if (($.browser.msie) && ($.browser.version == 7)) {
    		//console.log('white=pop ', $(e.currentTarget).attr('class'), $(e.relatedTarget).attr('class'));
    		if ( ($(e.relatedTarget).attr('class') == 'b-navigation__item-more') ||
    			($(e.relatedTarget).attr('class') == 'b-logos__img') ||
    			($(e.relatedTarget).attr('class') == 'b-topline-left')   			
    		) {
	    		return false;
	    	}
    	}
    
    	if (!$(e.relatedTarget).hasClass('b-new-more')) {
			$(this).parent().find('.b-white').hide();
			$(this).hide();
			$(this).prev('.b-new-more').removeClass('b-new-more-act');
    	}
    })
    
   /* 
    $(document).click(function(event) {
         if ($(event.target).closest(".b-white-popup").length) return;
        $('.b-white-popup, .b-white').fadeOut('fast');
        $('.b-new-more').removeClass('b-new-more-act');
        return false;
    */
    
    
    $('.b-change-for-pay a').on('click', function(){

        if ($(this).hasClass('abled')){
            $('.b-input-adress-t').attr('disabled','');
            $(this).removeClass('abled');
            $(this).html('Изменить');
            $('.b-change-for-pay span').show();  
        }
        else {
            $('.b-input-adress-t').removeAttr('disabled',' ');
            $('.b-input-adress-t').focus();
            $(this).addClass('abled');
            $(this).html('Отмена');
            $('.b-change-for-pay span').hide();    
        }
        return false;
    })
    
    
    
    $('.b-add-more-spec-button').on('click', function(){
        add_spec = $('.b-settings__select-spec .selectBox-label').html();
        $('.b-uploadeded-specs').append('<li class="b-uploaded-files__item"><span class="b-uploaded-files-name">'+ add_spec +'</span><a class="b-uploaded-files-delete" href="#"></a></li>');
        return false;
    })
    
    $('.b-uploaded-files-delete').live('click', function(){
        $(this).parents('.b-uploaded-files__item').remove();
        return false;
    })
    
    $('.b-what-new__item .b-groups__items-close, .b-what-new__item .i-discuss__items-restore').on('click', function() {
		$(this).parents('.b-what-new__item').find('.i-cont_notif-no, .b-discuss__items-cont_notif').toggle();
	
		return false;
	})
    
    $('.b-button-fancy-close').on('click', function(){
        $('#fancybox-overlay').click();
    })
    
    $('.b-grey-button-standart-upload').on('click', function(){
        $('.b-in-standart-upload').click();
    });
    
    $('.b-upload-foto-right').on('click', function(){
        $('.b-profile-upload-form').show();
        $('.b-standart-upload-form').hide();
        $('.b-upload-foto-left').removeClass('b-upload-foto-active');
        $(this).addClass('b-upload-foto-active');
        return false;
    });
    
    $('.b-upload-foto-left').on('click', function(){
        $('.b-standart-upload-form').show();
        $('.b-profile-upload-form').hide();
        $('.b-upload-foto-right').removeClass('b-upload-foto-active');
        $(this).addClass('b-upload-foto-active');
        return false;
    });
    
    $('.b-red-close').on('click', function(){
        $('.b-red-top-block').remove();
        return false;
    })
    
    $('.b-red-no-mod-full-show').on('click', function(){
        $('.b-red-no-mod-full').show();
        $(this).hide();
    })
    
    $('.b-red-no-mod-full-close').on('click', function(){
        $('.b-red-no-mod-full').hide();
        $('.b-red-no-mod-full-show').show();
    })
    
    $('.b-services-list__item:even').each(function(){
        var a = $(this).height();
        var b = $(this).next('.b-services-list__item:first').height();
        
        if (a > b) {
            $(this).next('.b-services-list__item:first').height(a);
        }
        
        else {
            $(this).height(b);
        }
    })
    
    $('.b-pay-button').on('click', function(){
        $('.b-pay-button').removeClass('active');
        $(this).addClass('active');
        return false;
    })
    
    $('.b-grey-info-link').on('click', function(){
        var notice = '-' + ($('.notice-popup').height())/2 - 2 + 'px';
        $(this).next().next('.notice-popup').css("top", notice);
        $(this).next().next('.notice-popup').show();
         $(this).next('.b-notice-ar').show();
        return false;
    })
    
    $('.notice-popup .b-groups__items-close').on('click', function(){
        $(this).parents('.notice-popup').prev().hide();
        $(this).parents('.notice-popup').hide();
    })
    
    
    $('.b-layout-reviews__item .b-position-actions li:first a.b-position-actions-link').on('click', function(){
    
        $(this).hide();
        $(this).prev('.b-request-position-sep').hide();
    
        var oldrevtext = $(this).parents('.b-what-new__time-posted').prev('.b-layout-reviews__item-user-info-text').text();
        var texth = $(this).parents('.b-what-new__time-posted').prev('.b-layout-reviews__item-user-info-text').height() + 'px';
        $(this).parents('.b-what-new__time-posted').prev('.b-layout-reviews__item-user-info-text').before('<textarea class="b-layout-reviews__item-user-info-text"></textarea>');
        $(this).parents('.b-what-new__time-posted').prev('.b-layout-reviews__item-user-info-text').hide();
        $(this).parents('.b-what-new__time-posted').prev().prev('.b-layout-reviews__item-user-info-text').val(oldrevtext);
        $(this).parents('.b-what-new__time-posted').prev().prev('.b-layout-reviews__item-user-info-text').height(texth);
        $(this).parents('.b-what-new__time-posted').before('<a class="b-grey-button b-grey-button_rev-save" href="#"><span class="b-send-button__text">Сохранить</span></a>');
        
        $(this).parents('.b-what-new__time-posted').prev('.b-grey-button').on('click', function(){
            var newrevtext = $(this).prev().prev('.b-layout-reviews__item-user-info-text').val();
            $(this).prev().prev('.b-layout-reviews__item-user-info-text').remove();
            $(this).prev('.b-layout-reviews__item-user-info-text').text(newrevtext);
            $(this).prev('.b-layout-reviews__item-user-info-text').show();
            $(this).next('.b-what-new__time-posted').children('.b-position-actions').children('li:first').children('a.b-position-actions-link').show();
            $(this).next('.b-what-new__time-posted').children('.b-position-actions').children('li:first').children('.b-request-position-sep').show();
            $(this).remove();
            return false;
        });
        return false;
    })
    
    $('.b-green-button-friend').mouseenter(function() { 
        $(this).addClass('b-red-button');
        $(this).children('.b-add-button__text').html('Из друзей');
        $(this).children('.b-fr-button__plus').addClass('b-add-button__cross');
    })
    
    $('.b-green-button-friend').mouseleave(function() { 
        $(this).removeClass('b-red-button');
        $(this).children('.b-add-button__text').html('В друзьях');
        $(this).children('.b-fr-button__plus').removeClass('b-add-button__cross');
    })
    
    
});

$(window).load(function() {
$('.b-pay-button img').each(function () {
        var height = $(this).parents('.b-pay-button').height();
        var height2 = $(this).height();
        $(this).css('margin-top',((height-height2)/2)+'px');
    });
})
