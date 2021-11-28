(function($) {
    "use strict";

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //* Form js
    function verificationForm(){
        //jQuery time
        var current_fs, next_fs, previous_fs; //fieldsets
        var left, opacity, scale; //fieldset properties which we will animate
        var animating; //flag to prevent quick multi-click glitches
        var proceed; //flag to prevent someone from proceeding with the form if the information provided is not in the database
        let data = [];

        $(".next").click(function () {
            if (animating)
                return false;
            animating = true;

            //Make ajax call to verify details on the form
            if(event.target.id === "personal_details"){
                event.preventDefault();
                proceed = false;

                if ($("#id_type").val() === "nrc")
                    data["nrc"] = $("#nrc").val();
                if ($("#id_type").val() === "passport")
                    data["passport"] = $("#passport").val();
                if ($("#id_type").val() === "drivers_license")
                    data["drivers_license"] = $("#drivers_license").val();

                data["last_name"] = $("#last_name").val();
                data["first_name"] = $("#first_name").val();
                data["other_names"] = $("#other_names").val();

                console.log(JSON.stringify(data));

                $.ajax({
                    type:'POST',
                    url: config.routes.verify,
                    data: JSON.stringify(data),
                    success:function(data){
                        // alert(data.success);
                        proceed = true;
                    },
                    error:function (){
                        proceed = false
                    }
                });
            }

            if(proceed === false)
                return false;


            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            //activate next step on progressbar using the index of next_fs
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale current_fs down to 80%
                    scale = 1 - (1 - now) * 0.2;
                    //2. bring next_fs from the right(50%)
                    left = (now * 50) + "%";
                    //3. increase opacity of next_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'position': 'absolute'
                    });
                    next_fs.css({
                        'left': left,
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

        $(".previous").click(function () {
            if (animating) return false;
            animating = true;

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //de-activate current step on progressbar
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now, mx) {
                    //as the opacity of current_fs reduces to 0 - stored in "now"
                    //1. scale previous_fs from 80% to 100%
                    scale = 0.8 + (1 - now) * 0.2;
                    //2. take current_fs to the right(50%) - from 0%
                    left = ((1 - now) * 50) + "%";
                    //3. increase opacity of previous_fs to 1 as it moves in
                    opacity = 1 - now;
                    current_fs.css({
                        'left': left
                    });
                    previous_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'opacity': opacity
                    });
                },
                duration: 800,
                complete: function () {
                    current_fs.hide();
                    animating = false;
                },
                //this comes from the custom easing plugin
                easing: 'easeInOutBack'
            });
        });

        $(".submit").click(function () {
            return false;
        })
    };

    toggleInputIDType();

    $("#id_type").change(function () {
        toggleInputIDType();
    });

    function toggleInputIDType() {
        if ($("#id_type").val() === "nrc") {
            $("#nrc").show();
        } else {
            $("#nrc").hide();
        }
        if ($("#id_type").val() === "passport") {
            $("#passport").show();
        } else {
            $("#passport").hide();
        }
        if ($("#id_type").val() === "drivers_license") {
            $("#drivers_license").show();
        } else {
            $("#drivers_license").hide();
        }
    }

    toggleInputVerificationMethod();

    $("#verification_method").change(function () {
        toggleInputVerificationMethod();
    });

    function toggleInputVerificationMethod() {
        if ($("#verification_method").val() === "phone") {
            $("#verification_method_phone").show();
        } else {
            $("#verification_method_phone").hide();
        }
        if ($("#verification_method").val() === "email") {
            $("#verification_method_email").show();
        } else {
            $("#verification_method_email").hide();
        }
    }

    //* Add Phone no select
    function phoneNoselect(){
        if ( $('#msform').length ){
            $("#phone").intlTelInput();
            $("#phone").intlTelInput("setNumber", "+260");
        };
    };

    //* Select js
    function nice_Select(){
        if ( $('.product_select').length ){
            $('select').niceSelect();
        };
    };

    /*Function Calls*/
    verificationForm ();
    phoneNoselect ();
    nice_Select ();
})(jQuery);
