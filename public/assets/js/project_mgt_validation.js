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
                required: "Please Upload Audio",
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
