
$(document).ready(function() {
    var uploader_stack = new Array();

    // for each upload button
    $('.uploader_init').each(function(){
        // uploader param
        var multi = $(this).find('input[name="multi_selection"]').val();
        if (typeof(multi) !== 'undefined' && multi == '1')
            multi = true;
        else
            multi = false;

        var settings = {
            browse_id : $(this).find('input[name="browse_id"]').val(),
            url : $(this).find('input[name="url"]').val(),
            callback : $(this).find('input[name="callback"]').val(),
            multi : multi,
            files_added : $(this).find('input[name="files_added"]').val(),
            start : $(this).find('input[name="start_button"]').val(),
            progress : $(this).find('input[name="progress"]').val()
        };

        var multipart = $(this).find('input[name="multipart"]').val();
        if (typeof(multipart) !== 'undefined') {
            settings.multipart_params = eval('('+multipart+')');
            //console.log(settings);
        }

        //console.log(settings);

        if (typeof(settings.browse_id) !== 'undefined' && settings.browse_id.length) {
            // create upload initializer
            var uploader = uploadInitializer(settings);
            uploader_stack.push(uploader);
        }
    });
});


// plupload initialization
function uploadInitializer(settings) {
    var plup_set = {
        runtimes : 'html5,gears,flash,browserplus,silverlight,html4',
        browse_button : settings.browse_id,
        url : settings.url,
        flash_swf_url : '/js/plupload/plupload.flash.swf',
        silverlight_xap_url : '/js/plupload/plupload.silverlight.xap',
        multi_selection: settings.multi,
        multi_part: true,
        multipart_params: {}
    };

    var uploader = new plupload.Uploader(plup_set);

    uploader.bind('UploadFile', function(up) {
        $.extend(up.settings.multipart_params, settings.multipart_params)
        //up.settings.multipart_params = settings.multipart;
        console.log(up.settings);
    });

    uploader.bind('Init', function(up, params) {
        //console.log('Initialized! ' + params.runtime);

        var _this = this;
        if (typeof(settings.start) !== 'undefined') {
            $('#' +settings.start).bind('click', function() {
                _this.start();

                return false;
            });
        }
    });

    uploader.init();

    uploader.bind('FilesAdded', function(up, files) {
        console.log('file added!');
        // getting file list
        var list = new Array();
        $.each(files, function(i, file) {
            list.push(file.name);
        });

        if (typeof(settings.files_added) !== 'undefined') {
            var func_name = settings.files_added + '(list)';
            //console.log(func_name);
            eval(func_name);
        }

        // if the is no element for starting upload
        // do it after files are added
        if (typeof(settings.start) === 'undefined') {
            uploader.start();
        }
    });

    uploader.bind('FileUploaded', function(up, file, data) {
        console.log(data);
        if (typeof(data.response) !== 'undefined') {
            var response = jQuery.parseJSON(data.response);
            alertbox(response.msg, response.status);

            if (response.status == 'ok') {
                //del this later

                var func_name = settings.callback + '(response)';
                //console.log(func_name + ' is gona be called now!');
                eval(func_name);
            }

            if (typeof(response.debug_info) !== 'undefined' && $('#debug_content')) {
                $('#debug_content').append('<p class="debug_url">' + settings.url + '</p>');
                for (var i = 0; i < response.debug_info.length; i++) {
                    $('#debug_content').append(response.debug_info[i]);
                }
            }
        }
    });

    uploader.bind('UploadProgress', function(up, file) {
        //console.log(file);
        if (typeof(settings.progress) !== 'undefined') {
            var func_name = settings.progress + '(file.percent, file.id)';
            eval(func_name);
        }
    });

    return uploader;
}

function uploadStarter() {

}