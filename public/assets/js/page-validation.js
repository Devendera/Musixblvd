var loadFile = function (event) {
    var output = document.getElementById("addPreview");
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src); // free memory
    };
};
var loadPreFile = function (event) {
    var output = document.getElementById("updatePreview");
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = function () {
        URL.revokeObjectURL(output.src); // free memory
    };
};

$(document).ready(function () {
    $("select.country").change(function () {
        var selectedCountry = $(".country option:selected").val();
        var base_path = $(".country").attr("data-url");
        $.ajax({
            url: base_path + "/getState",
            data: { country: selectedCountry },
        }).done(function (data) {
            $("select#response").html(data);
        });
    });
});
$(document).ready(function () {
    //group add limit
    var maxGroup = 12;

    //add more fields group
    $(".addMore").click(function () {
        if ($("body").find(".fieldGroup").length < maxGroup) {
            var fieldHTML =
                '<div class="col-md-12 mt-3 fieldGroup">' +
                $(".fieldGroupCopy").html() +
                "</div>";
            $("body").find(".fieldGroupCopy:first").before(fieldHTML);
        } else {
            alert("Maximum " + maxGroup + " groups are allowed.");
        }
    });

    //remove fields group
    $("body").on("click", ".remove", function (e) {
        e.preventDefault();
        $(this).parents(".fieldGroup").remove();
    });
});

$(document).ready(function () {
    $.validator.addMethod(
        "filesize",
        function (value, element, param) {
            return this.optional(element) || element.files[0].size <= param;
        },
        "File size must be less than {0}"
    );

    $.validator.addMethod(
        "letterswithspace",
        function (value, element) {
            return (
                this.optional(element) ||
                /^[a-zA-Z-,]+(\s{0,1}[a-zA-Z-, ])*$/i.test(value)
            );
        },
        "Enter valid data"
    );

    
    $("#creativeFrm").validate({
        // initialize the plugin
        rules: {
            fileUpload: {
                required: true,
                accept: "image/*",
                extension: "jpg,jpeg,png",
                filesize: 5242880, // <- 5 MB
            },
            firstName: {
                required: true,
                letterswithspace: true,
            },
            lastName: {
                required: true,
                letterswithspace: true,
            },
            website: {
                required: true,
                url: true,
            },
            stage: {
                required: true,
            },
            gender: {
                required: true,
            },
            type: {
                required: true,
            },
            pro: {
                required: true,
            },
            craft: {
                required: true,
            },
            genre: {
                required: true,
            },
            status: {
                required: true,
            },
            influencers: {
                required: true,
            },
        },
    });

    $("#creativeEditFrm").validate({
        // initialize the plugin
        rules: {
            firstName: {
                required: true,
                letterswithspace: true,
            },
            lastName: {
                required: true,
                letterswithspace: true,
            },
            website: {
                required: true,
                url: true,
            },
            stage: {
                required: true,
            },
            gender: {
                required: true,
            },
            type: {
                required: true,
            },
            pro: {
                required: true,
            },
            craft: {
                required: true,
            },
            genre: {
                required: true,
            },
            status: {
                required: true,
            },
            Influencers: {
                required: true,
            },
        },
    });

    if ($("#register_form").length > 0) {
        $("#register_form").validate({
            rules: {
                email: {
                    required: true,
                    maxlength: 50,
                    email: true,
                },

                password: {
                    required: true,
                    maxlength: 10,
                    minlength: 5,
                },

                sub: {
                    required: true,
                    equalTo: "#password",
                },

                type: {
                    required: true,
                },

                country: {
                    required: true,
                },

                state: {
                    required: true,
                },
            },
            messages: {
                password: {
                    required: "Please enter password",
                },
                sub: {
                    required: "Please enter confirm password",
                    equalTo: "Password and confirm password must be same",
                },
                type: {
                    required: "Please enter type",
                },
                email: {
                    required: "Please enter  email",
                    email: "Please enter valid email",
                    maxlength:
                        "The email name should less than or equal to 50 characters",
                },
                country: {
                    required: "Please enter country",
                },
                state: {
                    required: "Please enter state",
                },
            },
        });
    }
});

$("#studioUploadForm").validate({
    // initialize the plugin
    rules: {
        studioname: {
            required: true,
            letterswithspace: true,
        },
        address: {
            required: true,
        },
        bookingemail: {
            required: true,
            maxlength: 50,
            email: true,
        },
        hourlyrate: {
            required: true,
            number: true,
        },
        pro: {
            required: true,
        },
        radio: {
            required: true,
        },
        "files[]": {
            required: true,
            extension: "png,jpg,jpeg",
            filesize: 300000, //500kb
        },
    },
});

$("#studioUploadUpdateForm").validate({
    // initialize the plugin
    rules: {
        studioname: {
            required: true,
            letterswithspace: true,
        },
        address: {
            required: true,
           
        },
        bookingemail: {
            required: true,
            maxlength: 50,
            email: true,
        },
        hourlyrate: {
            required: true,
            number: true,
        },
        pro: {
            required: true,
        },
        radio: {
            required: true,
        },
    },
});

$(document).ready(function () {
    $(".add-connection-btn").click(function () {
        $("#ajaxdiv").css("display", "none");
        $("#ajaxResponse").text("");
        var connectionId = $(this).attr("data-id");
        var base_path = $(this).attr("data-url");
        var data_type = $(this).attr("data-type");

        var url = base_path + "/create-connections";

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: url,
            data: { user_id: connectionId },
        }).done(function (data) {
            var result = data.status;
            var message = data.message;
            if (message == "Unauthenticate") {
                window.location.href = base_path + "/login";
            } else {
                if (result) {
                    location.reload();
                } else {
                    $("#ajaxdiv").css("display", "block");
                    $("#ajaxResponse").text("");
                    $("#ajaxResponse").text(message);
                    setTimeout(function () {
                        $("#ajaxdiv").css("display", "none");
                    }, 5000);
                }
            }
        });
    });
});

$(document).ready(function () {
    $(".add-request-btn").click(function () {
        $("#ajaxdiv").css("display", "none");
        $("#ajaxResponse").text("");
        var connectionId = $(this).attr("data-id");
        var base_path = $(this).attr("data-url");
        var data_type = $(this).attr("data-type");

        var url = base_path + "/create-management";

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: url,
            data: { user_id: connectionId },
        }).done(function (data) {
            var result = data.status;
            var message = data.message;
            if (message == "Unauthenticate") {
                window.location.href = base_path + "/login";
            } else {
                if (result) {
                    location.reload();
                } else {
                    $("#ajaxdiv").css("display", "block");
                    $("#ajaxResponse").text("");
                    $("#ajaxResponse").text(message);
                    setTimeout(function () {
                        $("#ajaxdiv").css("display", "none");
                    }, 5000);
                }
            }
        });
    });
});

$("#geoaddress")
    .geocomplete()
    .bind("geocode:result", function (event, result) {
        $("#latitude").val("");
        $("#longitude").val("");
        if (result) {
            var lat = result.geometry.location.lat();
            var lng = result.geometry.location.lng();
            $("#latitude").val(lng);
            $("#longitude").val(lat);
        }
    });

$(function () {
    var base_path = $("#search-country").attr("data-url");
    var url = base_path + "/country/" + $("#search-country").val() + "/states/";
    $.get(url, function (data) {
        var select = $("select[name= state]");
        select.empty();
        urlState = "";
        if (getUrlVars()["state"]) {
            var urlState = getUrlVars()["state"];
        }
        select.append('<option value="">Select State</option>');
        $.each(data, function (key, value) {
            urlState = urlState.replace(/\+/g, "%20");
            urlState = decodeURIComponent(urlState);
            if (urlState != "" && value.name === urlState) {
                select.append(
                    $("<option />")
                        .val(value.name)
                        .text(value.name)
                        .attr("selected", "selected")
                );
            } else {
                select.append($("<option />").val(value.name).text(value.name));
            }
        });
    });
    $("#search-country").change(function () {
        var base_path = $(this).attr("data-url");
        var url = base_path + "/country/" + $(this).val() + "/states/";
        $.get(url, function (data) {
            var select = $("select[name= state]");
            select.empty();
            select.append('<option value="">Select State</option>');
            $.each(data, function (key, value) {
                select.append($("<option />").val(value.name).text(value.name));
            });
        });
    });
    $(".filter-clear").click(function () {
        $("#filterFrm")[0].reset();
        var select = $("select[name= state]");
        select.empty();
        select.append('<option value="">Select State</option>');
        var base_path = $("#search-country").attr("data-url");
        window.location.href = base_path + "/explore-filter";
    });
});

$("#forgetPassword").validate({
    // initialize the plugin
    rules: {
        email: {
            required: true,
            maxlength: 50,
            email: true,
        },
    },
});

$("#loginForm").validate({
    // initialize the plugin
    rules: {
        email: {
            required: true,
            maxlength: 50,
            email: true,
        },
        password: {
            required: true,
        },
    },
});
$("#manager_edit_form").validate({
    rules: {
        firstname: {
            required: true,
            maxlength: 50,
            nowhitespace: true,
        },

        lastname: {
            required: true,
            maxlength: 50,
            nowhitespace: true,
        },

        company: {
            required: true,
            maxlength: 50,
        },

        profile: {
            accept: "image/*",
            extension: "jpg,jpeg,png",
            filesize: 5242880, // <- 5 MB
        },
        facebook: {
            url: true,
        },
        instagram: {
            url: true,
        },
        twitter: {
            url: true,
        },
    },
    messages: {
        firstname: {
            required: "Please enter first name",
        },
        lastname: {
            required: "Please enter last name",
        },
        company: {
            required: "Please enter company name",
        },
    },
});

$("#manager_form").validate({
    rules: {
        firstname: {
            required: true,
            maxlength: 50,
            nowhitespace: true,
        },

        lastname: {
            required: true,
            maxlength: 50,
            nowhitespace: true,
        },

        company: {
            required: true,
            maxlength: 50,
        },

        profile: {
            required: true,
            accept: "image/*",
            extension: "jpg,jpeg,png",
            filesize: 5242880, // <- 5 MB
        },
        facebook: {
            url: true,
        },
        instagram: {
            url: true,
        },
        twitter: {
            url: true,
        },
    },
    messages: {
        firstname: {
            required: "Please enter first name",
        },
        lastname: {
            required: "Please enter last name",
        },
        company: {
            required: "Please enter company name",
        },
    },
});

function getUrlVars() {
    var vars = [],
        hash;
    var hashes = window.location.href
        .slice(window.location.href.indexOf("?") + 1)
        .split("&");
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split("=");
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

$(function () {
    $("#togglefrm").hide();
    $("#filter").click(function () {
        $("#togglefrm").toggle();
    });
});

$("#contactForm").validate({
    rules: {
        name: {
            required: true,
            maxlength: 50,
            nowhitespace: true,
        },

        email: {
            required: true,
            maxlength: 50,
            email: true,
        },

        phone: {
            required: true,
		  number: true,
		  minlength: 10,
		  maxlength: 10
        },

        message: {
            required: true,
            maxlength: 50,
        },
       
    },
});
$("#youtubeForm").validate({
    // initialize the plugin
    rules: {
    channel:{
            required: true,
            url: true,
        },
    },
});

$("#spotifyForm").validate({
    // initialize the plugin
    rules: {
    spotify:{
            required: true,
            nowhitespace: true,
        },
    },
});

$.validator.addMethod(
    "checklocation",
    function (value, element) {
      lat =  $('#latitude'). val(); 
      console.log(lat);
      if(lat != ""){
        return (
          true
        );
      }
    },
    "Enter valid address"
);


