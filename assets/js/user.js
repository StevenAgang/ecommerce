$(document).ready(function(){
    const elem = $('form');
    $('#register').submit(function()
    {;
        var is_valid = true;
        $('form p').text('');
        $('input[type=text], input[type=email], input[type=password]').each(function(){
            if($(this).val() === "")
            {
                $(this).after("<p>" + $(this).attr('name') + " Field is required </p>");
                $('p').css('color', 'red');
                is_valid = false;
            }
        });
        if($('input[name=mobilenumber]').val() !== '')
        {
            if($('input[name=mobilenumber]').val()[0] !== 0 && $('input[name=mobilenumber]').val()[1] !== 9 && $('input[name=mobilenumber]').val().length !== 11)
            {
                $('input[name=mobilenumber]').after("<p>Please enter a valid Mobile Number</p>");
                $('p').css('color', 'red');
                is_valid = false;
            }
        }
        if($('input[name=email]').val() !== '')
        {
            if(!$('input[name=email]').val().includes('@'))
            {
                $('input[name=email]').after("<p>Please enter a valid email</p>");
                $('p').css('color', 'red');
                is_valid = false;
            } 
        }
        if($('input[name=postal]').val() !== '')
        {
            if($('input[name=postal]').val().length > 10)
            {
                $('input[name=postal]').after("<p>Postal Code should not exceed 10 characters</p>");
                $('p').css('color', 'red');
                is_valid = false;
            } 
        }
        if($('input[name=city]').val() !== '')
        {
            if(!$('input[name=city]').val().includes('City'))
            {
                $('input[name=city]').after("<p>Please enter a valid City</p>");
                $('p').css('color', 'red');
                is_valid = false;
            } 
        }
        if($('input[type=password]').val() !== '')
        {
            if($('input[name=password]').val() !== $('input[name=confirm-password]').val())
            {
                $('input[name=confirm-password').after("<p>Password not matched</p>");
                $('p').css('color', 'red');
                is_valid = false;
            }
            else
            {
                if($('input[name=password]').val().length < 8)
                {
                    $('input[name=password').after("<p>Password need atleast 8 characters long</p>");
                    $('p').css('color', 'red');
                    is_valid = false;
                }
            }
        }
        if(is_valid === false)
        {
           return false;
        }
    });
    $('#login').submit(function()
    {
        var is_valid = true;
        $('form p').text('');
        $('input[type=text], input[type=email], input[type=password]').each(function(){
            if($(this).val() === "")
            {
                $(this).after("<p>" + $(this).attr('name') + " Field is required </p>");
                $('p').css('color', 'red');
                is_valid = false;
            }
        });
        if($('input[name=email]').val() !== '')
        {
            if(!$('input[name=email]').val().includes('@'))
            {
                $('input[name=email]').after("<p>Please enter a valid email</p>");
                $('p').css('color', 'red');
                is_valid = false;
            } 
        }
        if(is_valid === false)
        {
           return false;
        }
    });
});