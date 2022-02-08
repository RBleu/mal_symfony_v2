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