// http://www.uploadify.com/documentation
$(function() {
    $("#file_upload").uploadify({
        'buttonText'      : '图片上传',
        'uploader'        : SCOPE.image_upload,
        'swf'             : SCOPE.uploadify_swf,
        'fileTypeDesc'    : 'Image files',
        'fileObjName'     : 'file',
        'fileTypeExts'    : '*.gif; *.jpg; *.png',
        'fileSizeLimit'   : '2MB',
        'onUploadSuccess' : function(file, data, response) {
            console.log(file);
            console.log(data);
            console.log(response);
            if(response) {
                var obj = JSON.parse(data);
                $('#upload_org_code_img').attr('src', obj.data);
                $('#file_upload_image').attr('value', obj.data);
                $('#upload_org_code_img').show();
            }
        },
    });

    $("#file_upload_other").uploadify({
        'buttonText'      : '图片上传',
        'uploader'        : SCOPE.image_upload,
        'swf'             : SCOPE.uploadify_swf,
        'fileTypeDesc'    : 'Image files',
        'fileObjName'     : 'file',
        'fileTypeExts'    : '*.gif; *.jpg; *.png',
        'fileSizeLimit'   : '6MB',
        'onUploadSuccess' : function(file, data, response) {
            console.log(file);
            console.log(data);
            console.log(response);
            if(response) {
                var obj = JSON.parse(data);
                $('#upload_org_code_img_other').attr('src', obj.data);
                $('#file_upload_image_other').attr('value', obj.data);
                $('#upload_org_code_img_other').show();
            }
        },
    });
});