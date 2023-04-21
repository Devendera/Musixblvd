
    $.validator.addMethod(
        "filesize",
        function (value, element, param) {
            return this.optional(element) || element.files[0].size <= param;
        },
        "File size must be less than {0}"
    );


if ($("#project_mgt_form").length > 0) {
    $("#project_mgt_form").validate({
        // initialize the plugin
        rules: {
            projectImage: {
                required: true,
            },
            projectTitle: {
                required: true,
            },
            releaseDate: {
                required: true,
            },
            privacy: {
                required: true,
            },
            projectAudio: {
                required: true,
                accept: "audio/*",
                filesize: 1242880, // <- 5 MB
            },
            "projectData[0][platform]": {
                required: true,
            },
            "projectData[0][url]": {
                required: true,
                url: true,
            },
            "contributors[]": {
                required: true,
            },
            "roles[]": {
                required: true,
            },
        },
        messages: {
            projectImage: {
                required: "Please Upload an Image",
            },
            projectTitle: {
                required: "Please Enter Title",
            },
            releaseDate: {
                required: "Please Enter Date",
            },
            privacy: {
                required: "Please Select Type",
            },
            projectAudio: {
                filesize: "File size must be less than 600kb.",
            },
            "platforms[]": {
                required: "Please Select Value",
            },
            "url[]": {
                required: "Please Enter Value",
            },
            "contributors[]": {
                required: "Please Enter Contributor",
            },
            "roles[]": {
                required: "Please Enter Role",
            },
        },
    });
}
 
$(document).ready(function() {
    $('#btn-example-file-reset').on('click', function() {     
       $('#fileUpload1').val('');
       $('#filename').text('Audio File Name Here');     
    });
 });