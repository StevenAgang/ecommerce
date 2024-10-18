$(document).ready(function(){
    
    $(document).on('input','#add_carts input[type=number]',function()
    {
        if(parseFloat($(this).val()) < 1)
        {   
            $(this).val('1');
        }
        else
        {
            let price = $('h2 mark').html().replace('$','');
            price = price * $(this).val();
            $('#add_carts input[name=sum]').val('$' + price);
        }
    });

    $(document).on('submit','#cart',function(){
        $.post($(this).attr('action'),$(this).serialize(),function(){
            
        });
    });

    $(document).on('click','#images img',function(){
        let main_pic = $('#main_image').attr('src');
        $('#main_image').attr('src', $(this).attr('src'));
        $(this).attr('src',main_pic);
    });
});