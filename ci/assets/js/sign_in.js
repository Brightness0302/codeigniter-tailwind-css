const site_url = $("[name=site_url]").val();

(function ($) {
    'use strict';
    $(function() {

        window.Parsley.addValidator('emailRegex', {
            validateString: function(value) {
                return /[a-zA-Z0-9_]+@[a-zA-Z]+\.(com|net|org)$/.test(value)
            },
            messages:
            {
                en: 'Invalid Email Format ! e.g johndoe@gmail.com'
            }
        });

        $("#form-sign-in").parsley({
            classHandler: function (el) {
                return el.$element.closest('.form-group');
            },
            errorsWrapper: '<div class="border form-field invisible opacity-0 mt-2 text-sm font-bold border-red-400 rounded bg-red-100 px-4 py-3 text-red-700"></div>',
            errorTemplate: '<p></p>'
        }).on('field:success', function() {
            let el = this.$element[0].id
            $('#'+el).addClass("border-green-500");
            $('#'+el).parent().find(".form-field").hide(250);
            $('#'+el).parent().find(".form-field").addClass("opacity-0");
            $('#'+el).parent().find(".form-field").addClass("invisible");
        }).on('field:error', function() {
            let el = this.$element[0].id
            $('#'+el).parent().find(".form-field").removeClass("invisible");
            $('#'+el).parent().find(".form-field").addClass("visible");
            $('#'+el).parent().find(".form-field").show(250);
            $('#'+el).parent().find(".form-field").animate({opacity: 1.0}, 250);
            $('#'+el).removeClass("border-green-500");
        });

        $("#email-sign-in").parsley({
            classHandler: function (el) {
                return el.$element.closest('.form-group');
            },
            errorsWrapper: '<div class="border form-field invisible opacity-0 mt-2 text-sm font-bold border-red-400 rounded bg-red-100 px-4 py-3 text-red-700"></div>',
            errorTemplate: '<p></p>'
        }).on('field:success', function() {
            let el = this.$element[0].id
            $('#'+el).addClass("border-green-500");
            $('#'+el).parent().find(".form-field").hide(250);
            $('#'+el).parent().find(".form-field").addClass("opacity-0");
            $('#'+el).parent().find(".form-field").addClass("invisible");
        }).on('field:error', function() {
            let el = this.$element[0].id
            $('#'+el).parent().find(".form-field").removeClass("invisible");
            $('#'+el).parent().find(".form-field").addClass("visible");
            $('#'+el).parent().find(".form-field").show(250);
            $('#'+el).parent().find(".form-field").animate({opacity: 1.0}, 250);
            $('#'+el).removeClass("border-green-500");

            $('#parsley-id-5').find('.parsley-type').hide().show(250);
            $('#parsley-id-5').find('.parsley-required').hide().show(250);
            $('.parsley-emailRegex').hide().show(250);
        });


        $("#form-sign-in").submit(function(e) {
            e.preventDefault();

            if($(this).parsley().isValid())
            {

                NProgress.start();

                $("#form-submit-sign-in").text('');
                $("#form-submit-sign-in").append('<img src="'+site_url+'assets/loader/loader.gif" style="width: 25px; display: block; margin: 0 auto;">');
                $("#form-submit-sign-in").addClass("cursor-not-allowed");
                $("#form-submit-sign-in").addClass("opacity-50");
                $("#form-submit-sign-in").removeClass("hover:bg-blue-700");
                $("#form-submit-sign-in").prop("disabled", true);

                $.post(site_url + 'sign-in', { email: $('#email-sign-in').val(), password: $('#password-sign-in').val()  }, function(data)
                {
                    if(data.logged_in)
                    {
                        Swal.fire(
                            data.title,
                            data.desc,
                            data.type
                        )
                        NProgress.done();
                        $("#form-submit-sign-in").text('Sign In');
                        $("#form-submit-sign-in").removeClass("cursor-not-allowed");
                        $("#form-submit-sign-in").removeClass("opacity-50");
                        $("#form-submit-sign-in").addClass("hover:bg-blue-700");
                        $("#form-submit-sign-in").prop("disabled", false);
                    }
                    else
                    {
                        Swal.fire(
                            data.title,
                            data.desc,
                            data.type
                        )
                        NProgress.done();
                        $("#form-submit-sign-in").text('Sign In');
                        $("#form-submit-sign-in").removeClass("cursor-not-allowed");
                        $("#form-submit-sign-in").removeClass("opacity-50");
                        $("#form-submit-sign-in").addClass("hover:bg-blue-700");
                        $("#form-submit-sign-in").prop("disabled", false);
                    }
                });
            }
        });

    });
})(jQuery);