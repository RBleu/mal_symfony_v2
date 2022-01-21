$(function(){
    $('.show-pass').on('change', (e) => {
        let password = '';

        if($(e.target).attr('name') == 'show-pass')
        {
            password = $('.plainPassword');
        }
        else
        {
            password = $('.plainPasswordConfirm');
        }


        if($(e.target).is(':checked'))
        {
            password.attr('type', 'text');
        }
        else
        {
            password.attr('type', 'password');
        }
    });

    $('.btn').on('click', () => {
        let username = $('.username').val();
        let password = $('.plainPassword').val();
        let confirmPassword = $('.plainPasswordConfirm').val();

        if(username.match(/^[\w]{4,20}$/g) != null && password.match(/^.{8,50}$/g) != null)
        {
            if(confirmPassword === undefined)
            {
                $('.log').trigger('submit');
            }
            else
            {
                if(password == confirmPassword)
                {
                    $('.log').trigger('submit');
                }
            }
        }
    });

    $('.field').on('keyup', (e) => {
        if(e.keyCode == 13)
        {
            $('.btn').trigger('click');
        }
    });

    $('.plainPasswordConfirm').on('keyup', (e) => {
        let password = $('.plainPassword').val();
        let confirmPassword = $(e.target).val();

        if(password == confirmPassword)
        {
            $(e.target).removeClass('invalid');
        }   
        else
        {
            $(e.target).addClass('invalid');
        }
    });
});