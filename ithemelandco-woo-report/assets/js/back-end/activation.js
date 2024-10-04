jQuery(document).ready(function ($) {
    $(document).on('click', '#itwr-activation-activate', function () {
        $('#itwr-activation-type').val('activate');

        if ($('#itwr-activation-email').val() != '') {
            if ($('#itwr-activation-industry').val() != '') {
                setTimeout(function () {
                    $('#itwr-activation-form').first().submit();
                }, 200)
            } else {
                swal({
                    title: "Industry is required !",
                    type: "warning"
                });
            }
        } else {
            swal({
                title: "Email is required !",
                type: "warning"
            });
        }
    });

    $(document).on('click', '#itwr-activation-skip', function () {
        $('#itwr-activation-type').val('skip');

        setTimeout(function () {
            $('#itwr-activation-form').first().submit();
        }, 200)
    });
})