// common functions
function in_array( needle, haystack, strict ) {
    var strict = !!strict;

    for(var key in haystack){
        if( (strict && haystack[key] === needle) || (!strict && haystack[key] == needle) ){
            return true;
        }
    }
    return false;
}

// ajax loader
function refreshBlock(data){
    if (typeof(data.param.output) !== 'undefined'){
        $(data.param.output).html(data.content)
    }

    if (typeof(data.param.pager) !== 'undefined'){
        $(data.param.pager).html(data.pager);
    }

    if(typeof(data.replacement) !== 'undefined'){
        for(i in data.replacement){
            $(data.replacement[i].selector).html(data.replacement[i].content)
        }
    }
}

function getFormData(form) {

    var request = {data : {}, param : {}};
    $(form).find('input').each(function(){
        var name = $(this).attr('name');
        var type = $(this).attr('type');
        var value = '';

        // checkboxes
        if (type == 'checkbox') {
            if ($(this).attr('checked'))
                request.data[name] = 1;
            else
                request.data[name] = 0;
        // radio
        } else if (type == 'radio') {
            if ($(this).attr('checked')) {
                request.data[name] = $(this).val();
            }
        // other
        } else {
            $(this).val() !== undefined ? value = $(this).val() : value = '';

            if (name && name.length > 5 && name.substr(0,5) == 'param') {
                request.param[name.substr(6)] = value;
            } else {
                request.data[name] = value;
            }
        }
    });

    // textareas
    $(form).find('textarea').each(function(){
        request.data[$(this).attr('name')] = $(this).val();
    });

    // select
    $(form).find('select').each(function(){
        request.data[$(this).attr('name')] = $(this).find('option:selected').val();
    });

    return request; //$('#'+form_id).serializeArray();
}


$(document).ready(function(){
    //$.ajaxSetup({scriptCharset: "utf-8" , contentType: "application/json; charset=utf-8"});

    $(".pager a").live('click', function(){
        var form = $('#' + $(this).parents('.pager').attr('form'));

        var value = parseInt($(this).html());
        if (isNaN(value)) {
            value = $(this).attr('value');
        }

        $(form).find('input[name="page"]').val(value);
        $(form).submit();

        return false;
    });

    $('.switcher select').change(function(){
        var value = $(this).find('option:selected').val();
        var name = $(this).attr('name');
        var form = $(this).parents('.switcher').attr('form');

        $('#'+form+' input[name="'+name+'"]').val(value);
        $('#'+form).submit();
    });

    /*
     *  Makes possible index page filters
     */
    $('ul.switcher > li > a').click(function(){
        var value = $(this).parent().index()+1
        var switcher = $(this).parents('.switcher')
        var form = switcher.attr('form')
        var name = switcher.attr('name')

        $('#'+form+' input[name="'+name+'"]').val(value)
        $('#'+form).submit()

        return false;
    });

    /*
     *  Switches active class for index page filters
     */
    $('ul.b-left-menu__sublist.switcher > li > a').click(function(){
        $(this).parent().parent().find('.b-left-menu__items-title_active').removeClass('b-left-menu__items-title_active')
        $(this).addClass('b-left-menu__items-title_active')
    });

    $('.save').live('click', function(){
        $(this).parents('form').submit();
        return false;
    });

    $('form.ajax').live('submit',function(){
        // if the form needs to be checked
        var check = $('input[name="param.check_form"]').val();
        if (check == 1 && !formDataChecker(this)) {
            alertbox('Заполните обязательные поля!', 'error');
            return false;
        }

        var request = getFormData(this);
        console.log(request);

        if (request.param.url.length){
            $.post(
                request.param.url,
                request,
                function(response){
                    console.log(response);
                    if (typeof(response.msg) !== 'undefined') {
                        alertbox(response.msg, response.status);
                    } else if (response.status == 'error') {
                        alertbox("Error!", 'error');
                    }

                    // do redirect if needed
                    // (ex: user alias change => full reload)
                    if (typeof(response.goto) !== 'undefined') {
                        document.location.href = response.goto;
                    }

                    refreshBlock(response);
                    refreshCheckbox();

                    if (typeof(request.param.callback) !== 'undefined') {
                        var func_name = request.param.callback + '(request.data, response)';
                        //console.log(func_name);
                        eval(func_name);
                    }

                    if (typeof(response.debug_info) !== 'undefined' && $('#debug_content')) {
                        $('#debug_content').append('<p><span class="debug_url">'
                            + request.param.url + '</span> :: <span class="debug_table">'
                            + response.exec_time + '</span> msec</p>');
                        for (var i = 0; i < response.debug_info.length; i++) {
                            $('#debug_content').append(response.debug_info[i]);
                        }
                    }
                },
                'JSON');
        }

        return false;
    });

    $('form.ajax').each(function() {
        if ($(this).attr('reload') == '1')
            $(this).submit();
    });
});



function alertbox(content, type){
    type = typeof type !== 'undefined' ? type : '';

    if(type != ''){
        type = 'alert-'+type
    }
    time = new Date().getTime()
    var itemID = 'notification-'+time
    alertHTML = '<div class="alert alert-block '+type+' fade in" id="'+itemID+'">'
        +'<button class="close" data-dismiss="alert">×</button><strong>'
        + content
        +'</strong></div>';

    $('#alertsContainer').prepend(alertHTML)


    setTimeout(function(){
        $('#'+itemID).fadeOut(300, function(){
            $(this).remove()
        })
    }, 4000);

    $('.alert .close').click(function(){
        $(this).parent().remove();
    });
}

function alert(message){
    alertbox(message, 'error')
}

function setAuthError(data, response) {
    if (typeof(response.msg) !== 'undefined') {
        $('#auth_error_block').html(response.msg).parent().removeClass('hidden');
    }
}

function refreshSelect(select) {
    $(select)
        .selectBox('destroy')
        .selectBox()
}

function refreshCheckbox(obj){
    if (typeof(obj) === 'undefined') {
        $('input[type="checkbox"]').each(function(){
            $(this).checkbox();
        });
    } else {
        $(obj).checkbox();
    }
}

function formDataChecker(form) {    var valid = true;

    // inputs
    $(form).find('input[required], textarea[required]')
        .each(function() {
            if ($(this).val() == '') {
                valid = false;
            }
        }
    );
    // selectboxes
    $(form).find('select[required]')
        .each(function() {
            var selected = $(this).find('option:selected').val();
            if (!selected || selected == 0) {
                valid = false;
            }
        }
    );

    return valid;
}

/*
    Adding to friends
 */

$(document).ready(function() {
    $('#change_friend').live('click', function() {
        $('#form_friend_change').submit();
        $.fancybox.close();
        return false;
    });

    $('.friend_change_button').live('click', function() {
        if ($(this).attr('status') == 'add') {
            $('#confirmation_text')
                .html('Вы действительно хотите добавить пользователя ' +
                $(this).attr('user') +
                ' в друзья?');
        } else {
            $('#confirmation_text')
                .html('Вы действительно хотите удалить пользователя ' +
                $(this).attr('user') +
                ' из друзей?');
        }
    });
});

function toggleFriendButton(data, response) {
    if (response.status == 'ok') {
        var button = $('#friend_block_container .friend_change_button');
        var span = $(button).find('span');
         if($(button).hasClass('b-green-button-friend')) {
             $(button)
                 .removeClass('b-green-button-friend')
                 .addClass('b-blue-button')
                 .attr('status', 'add');

             $(span).eq(0)
                 .removeClass('b-fr-button__plus')
                 .addClass('b-add-button__plus')
             $(span).eq(1)
                 .html('В друзья')
         } else {
             $(button)
                 .removeClass('b-blue-button')
                 .addClass('b-green-button-friend')
                 .attr('status', 'del');
             $(span).eq(0)
                 .removeClass('b-add-button__plus')
                 .addClass('b-fr-button__plus')
             $(span).eq(1)
                 .html('В друзьях')
         }
    }
}