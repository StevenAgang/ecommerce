$(document).ready(function(){
    $(document).on('change','.change_status',function(){
        let form = $(this).closest('form');
        $.post(form.attr('action'),form.serialize(),function(res){
            $('main section').html(res);
        });
        return false;
    });
    $(document).on('input','#search_order input[name=searchbar]',function(){
        if($(this).val() === '')
        {
            $.get('orders/order_category/0',function(){
                $('main section').html(res);
            });
            return false;
        }
        else
        {
            $.post($('#search_order').attr('action'),$('#search_order').serialize(),function(res){
                $('main section').html(res);
            });
            return false;
        }
    });
});