let checked = true;
$(document).ready(function(){
    let cart_data = 0;
    $(document).on('click','#carts input[name=remove]',function(event){
        event.preventDefault();
        let forms = $(this).closest('form');
        $('.modal').css('display','block');
        var html_str = '<h1>Are you sure you want to delete this item?';
        html_str += '<section id="button">';
        html_str += '<button id="yes">Yes</button>';
        html_str += '<button id="no">No</button>';
        html_str += '</section>';
        $('.modal-content').html(html_str);
        cart_data = forms.find('input[name=cart_id]').val();
    });

    $(document).on('click','#yes',function(){
        $('#carts').attr('action','carts/remove');
        $('#carts').find('input[name=cart_id]').val(cart_data);
        $('#carts').submit();
        $('.modal').css('display','none');
     });

     $(document).on('click','#no',function(){
         $('.modal').css('display','none');
     });

     $(document).on('change','#bill input[type=checkbox]',function(){
        if($(this).prop('checked'))
        {
            checked = true;
            $('#shipping input').each(function(){
                $(this).attr('disabled','disabled');
            });
            
            $('#shipping input').each(function(){
                $(this).val($('#bill_info input[name="'+ $(this).attr('name') +'"]').val());
           });
        }
        else
        {
           checked = false;
           get_address();
        }
     });

     $(document).on('input','#bill_info input',function(){
        if(checked === true)
        {
            $('#shipping input[name="'+ $(this).attr('name') +'"]').val($(this).val());
        }
     });

     $(document).on('click','#checkout',function(event){
        event.preventDefault();
        let valid = true;
        var html_str = "";
        $('#shipping input').each(function(){
            if($(this).val() === '')
            {
                html_str += '<p class="errors">' + $(this).attr('placeholder') + ' is required';
                valid = false;
            }
            if($('input[name=zip]').val().length > 10)
            {
                html_str += '<p class="errors">Zip Code should not exceed 10 characters</p>';
                valid = false;
            } 
        });
        $('#bill_info input').each(function(){
            if($(this).val() === '')
            {
                html_str += '<p class="errors">' + $(this).attr('placeholder') + ' is required';
                valid = false;
            }
            if($('input[name=zip]').val() !== '')
            {
                if($('input[name=zip]').val().length > 10)
                {
                    html_str += '<p class="errors">Zip Code should not exceed 10 characters</p>';
                    valid = false;
                } 
            }
        });
        if(valid === false)
        {
            console.log($('.errors').html());
            $('.modal').css('display','block');
            $('p.errors').css('color', 'red');
            $('.modal-content').html(html_str);
        }
        else
        {
            $('#shipping').submit();
            $('#bill_info').submit();
            $('.modal').css('display','block');
            let html_str = '<h1> Checkout </h1>';
            html_str += '<form id="pay" action="carts/checkout" method="post">';
            html_str += '<select name="credit_card">';
            html_str += '<option value="Visa">Visa</option>';
            html_str += '<option value="Master Card">Master Card</option>';
            html_str += '</select>';
            html_str += '<input type="text" name="card_number" placeholder="Card Number">';
            html_str += '<input type="text" name="expiration" placeholder="Expiration Date">';
            html_str += '<input type="text" name="cvc" placeholder="CVC">';
            html_str += '<input type="submit" name="submit" value="Pay">';
            html_str += '</form>';
            $('.modal-content').html(html_str);        
        }
        $(window).click(function(event){
            if(event.target === $('.modal')[0])
            {
                $('.modal').css('display','none');
            }
        });
     });
    
    $(document).on('click','#pay input[name=submit]',function(event){
        event.preventDefault();
        if(credit_card() === true)
        {
            $('#pay').submit();
        }
    });

    $(document).on('change','#carts input[name=quantity]',function(event)
    {
        let forms = $(this).closest('form');
        $convert = parseInt($(this).val());
        if($convert < 1) 
        {
            $(this).val('1');
        }
        else
        {
            $('#carts').attr('action','carts/change_quantity');
            let formData = forms.find('input[name=quantity],input[name=cart_id],input[name=csrf_token]'); 
            $.post($('#carts').attr('action'),formData.serialize(),function(res){
                $('main section').html(res.view);
            },'json');   
        }
    });
});
function get_address()
{
    $('#shipping input').each(function()
    {
        $(this).attr('disabled','disabled');
    });
    $.get('carts/shipping_info',function(res){
        $('#shipping input[name=fname]').val(res.address['firstname']);
        $('#shipping input[name=lname]').val(res.address['lastname']);
        $('#shipping input[name=address]').val(res.address['street'] + ' ' + res.address['city']);
        $('#shipping input[name=city]').val(res.address['city']);
        $('#shipping input[name=zip]').val(res.address['postal_code']);
    },'json');
    return false;
}
function credit_card()
{
    let valid_credit = true;
    let number = ['1','2','3','4','5','6','7','8','9','0'];
    $('.credit_errors').text('');
    $('#pay input[type=text]').each(function(){
        if($(this).val() === '')
        {
            $(this).after('<p class="credit_errors">' + $(this).attr('placeholder') + ' is required');
            valid_credit = false;
        }
    });
    if($('input[name=card_number]').val() !== '')
    {
        if($('input[name=card_number]').val().length !== 16)
        {
            $('input[name=card_number]').after('<p class="credit_errors">' + $('input[name=card_number]').attr('placeholder') + ' is invalid');
            valid_credit = false;
        }
    } 
    if($('input[name=expiration]').val() !== '')
    {
        let is_number = false;
        for(let i = 0; i < $('input[name=expiration]').val().length; i++)
        {
            for(let itr = 0; itr < number.length; itr++)
            {
                if($('input[name=expiration]').val()[i] === number[itr])
                {
                    is_number = true;
                    break;
                }
            }
        }
        if(is_number === true)
        {
            let data = parseInt($('input[name=expiration]').val());
            if(data < 2023)
            {
                $('input[name=expiration]').after('<p class="credit_errors">' + $('input[name=expiration]').attr('placeholder') + ' is invalid');
                valid_credit = false;
            }
        }
        else
        {
            $(this).after('<p class="credit_errors">' + $(this).attr('placeholder') + ' is invalid');
        }
        
    }
    if($('input[name=cvc]').val() !== '')
    {
        if($('input[name=cvc]').val().length > 0 && $('input[name=cvc').val().length < 4)
        {
            let is_number = false;
            for(let i = 0; i < $('input[name=cvc]').val().length; i++)
            {
                for(let itr = 0; itr < number.length; itr++)
                {
                    if($('input[name=cvc]').val()[i] === number[itr])
                    {
                        is_number = true;
                        break;
                    }
                }
            }
            if(is_number === false)
            {
                $('input[name=cvc]').after('<p class="credit_errors">' + $('input[name=cvc]').attr('placeholder') + ' is invalid');
            }
        }
        else
        {
            $('input[name=cvc]').after('<p class="credit_errors">' + $('input[name=cvc]').attr('placeholder') + ' is invalid');
        }
    }
    return valid_credit;
}

function success_order()
{
    let html_str = "<h1> Orders Complete Thankyou!";
    $('.modal-content').html(html_str);
   $('.modal-content h1').css('color','green'); 
}