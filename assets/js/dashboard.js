$(document).ready(function(){
    //  WHEN THE DOCUMENT IS DONE LOADING, FETCH ALL THE PRODUCT
    $.get('products/product_api',function(res){
        $('main section').append(res);
   });
    // FUNCTION FOR SUBMITTING FORM, WHEN THE FORM HAS ID IT WILL GO TO ITS RESPECTIVE CASE IN THE SWITCH STATEMENT
    // ELSE IT WILL GO TO THE DEFAULT ONE
   $(document).on('submit','form',function(event){
        switch($(this).attr('id'))
        {
            case 'add_carts':
                $.post($(this).attr('action'),$(this).serialize(),function(res){
                    if(res.status === true)
                    {
                        if(res.success === true)
                        {
                            $('.modal').css('display','block');
                            var html_str = "<h1>Successfully Added to Cart</h1>";
                            $('.modal-content').html(html_str);
                            $('.modal-content h1').css('color','green');
                            
                            $(window).click(function(event){
                                if(event.target === $('.modal')[0])
                                {
                                    $('.modal').css('display','none');
                                }
                            });
                            $('main #searchbar_carts').html(res.view);
                            console.log(res.partial);
                            $.get(res.partial,function(res){
                                $('main section').html(res);
                            });
                        }
                        else
                        {
                            $('.modal').css('display','block');
                            var html_str = "<h1>Not enough stocks for you, sorry!!</h1>";
                            $('.modal-content').html(html_str);
                            $('.modal-content h1').css('color','red');
                            
                            $(window).click(function(event){
                                if(event.target === $('.modal')[0])
                                {
                                    $('.modal').css('display','none');
                                }
                            });
                        }
                       
                    }
                    else
                    {
                        window.location.href = res.view;
                    }
                },'json');
                return false;
            case 'display_cart':
                $.get($(this).attr('action'),function(res){
                    if(res.status === true)
                    {
                        $('main section').html(res.view);
                    }
                    else
                    {
                        window.location.href = res.view;
                    }
                },'json');
                return false;
            case 'carts':
                $.post($(this).attr('action'),$(this).serialize(),function(res){
                    $('main #searchbar_carts').html(res.searchbar);
                    $('main section').html(res.orders);
                },'json'); 
                return false;
            case 'pay':
                $.post($(this).attr('action'),$(this).serialize(),function(res){
                    $('main #searchbar_carts').html(res.searchbar);
                    $('main section').html(res.orders);
                },'json');
                success_order();
                return false;
            case 'shipping':
                $('#shipping input').removeAttr('disabled');
                $.post($(this).attr('action'),$(this).serialize());
                $('#shipping input').each(function(){
                    $(this).attr('disabled','disabled');
                });
                return false;
            case 'bill_info':
                $.post($(this).attr('action'),$(this).serialize());
                return false;
            case 'add_product':
                let formdata = new FormData(this);
                if(validation() === true)
                {
                    $.post($(this).attr('action'),formdata,function(res){
                        $('main section').html(res);
                    });
                    return false;
                }
                return false;
            case 'edit_product':
                let formdatas = new FormData(this);
                $.post($(this).attr('action'),formdatas,function(res){
                    $('main section').html(res);
                });
                return false;
            default:
                $.post($(this).attr('action'),$(this).serialize(), function(res){
                    $('main section').html(res);
                });
                return false;
        }
   });  
    //  GET THE CONTENT OF NEXT OR PREVIOUS PAGE OF THE WEBSITE
   $(document).on('click','#pagination ul .page',function()
   {
        $.get($(this).attr('href'),function(res){
            $('main section').html(res);
        });
        return false;
   });
    // REDIRECT USER TO LOGIN OR REGISTRATION
    $('header button').click(function(){
        if($(this).attr('id') === 'login')
        {
            window.location.href = 'https://ecommerce-m6o2.onrender.com/login';
            // $('.modal').css('display','block');
            // // var html_str = "<h1> Login </h1>";
            // // html_str += "<form action='users/login' method='post'>";
            // // html_str += "<input type='text' name='email' placeholder='Email'>";
            // // html_str += "<input type='password' name='password' placeholder='Password'>";
            // // html_str += "<input type='submit' name='submit' value='Login'>";
            // // html_str += "</form>";
            // // $('.modal-content').html(html_str);
            
            // $(window).click(function(event){
            //     if(event.target === $('.modal')[0])
            //     {
            //         $('.modal').css('display','none');
            //     }
            // });
        }
        else
        {
            window.location.href = 'https://ecommerce-m6o2.onrender.com/register';
            // $('.modal').css('display','block');
            // var html_str = "<h1> Register </h1>";
            // html_str += "<form action='' method='post'>";
            // html_str += "<input type='text' name='firstname' placeholder='First Name'>";
            // html_str += "<input type='text' name='lastname' placeholder='Last Name'>";
            // html_str += "<input type='text' name='mobilenumber' placeholder='First Name'>";
            // html_str += "<input type='text' name='city' placeholder='City'>";
            // html_str += "<input type='text' name='street' placeholder='Street'>";
            // html_str += "<input type='text' name='postal' placeholder='Postal Codes'>";
            // html_str += "<input type='text' name='firstname' placeholder='First Name'>";
            // html_str += "<input type='password' name='password' placeholder='Password'>";
            // html_str += "<input type='text' name='confirm-password' placeholder='Confirm Password'>";
            // html_str += "<input type='submit' name='submit' value='Login'>";
            // html_str += "</form>";
            // $('.modal-content').html(html_str);
            // $(window).click(function(event){
            //     if(event.target === $('.modal')[0])
            //     {
            //         $('.modal').css('display','none');
            //     }
            // });
        }
    });
    // GET THE PRODUCTS WHEN THE USER PRESS ON THE SEARCHBAR
    $('main article #search').on('keypress',function(event){
        if(event.which === 13)
        {
            if($('input[type=search]').val().length === 0)
            {
                $.get('products/product_api',function(res){
                    $('main section').html(res);
               });
            }else
            {
                $.post($(this).attr('action'), $(this).serialize(), function(res){
                    $('main section').html(res);
                });
              return false;
            }
        }
    });
    // CHECK EVERY INPUT OF THE USER IF IT EXIST IN THE DATABASE
    $('main article #search').on('input',function(){
        if($('input[type=search]').val().length === 0)
        {
            $.get('products/product_api',function(res){
                $('main section').html(res);
           });
           return false;
        }
    });
    $(document).on('click','main section ul li a',function(){
        $.get($(this).attr('href'),$(this).serialize(),function(res){
            $('main section').html(res);
        });
        return false;
    });
    // REDIRECT USER TO SPECIFIED LINK
    $(document).on('submit','#logout',function(){
        window.location.href = $(this).attr('action');
    });
    //  GET THE CONTENTS OF CUSTOMER AND ADMIN VIEW
    $(document).on('click','#customer_view',function(){
        var $this = $(this);
        $.get($this.attr('href'), function(res) {
            $('main #searchbar_carts').html(res.searchbar);
            $('main section').html(res.partial);
            $('main #header').html('');
            $this.attr('id', 'admin_view'); 
            $this.attr('href', 'https://ecommerce-pt53.onrender.com/products/admin_view/not_main');
        },'json');
        return false;
    });

    $(document).on('click','#admin_view',function(){
        var $this = $(this);
        $.get($this.attr('href'), function(res) {
            console.log(res);
            $('main #searchbar_carts').html(res.searchbar);
            $('main section').html(res.partial);
            $('main #header').html('Orders');
            $this.attr('id', 'customer_view');
            $this.attr('href', 'https://ecommerce-pt53.onrender.com/products/admin_switch_view'); 
        },'json');
        return false;
    });

    $(document).on('click','#admin_product_view',function(){
        $.get($(this).attr('href'), function(res) {
            $('main #searchbar_carts').html(res.searchbar);
            $('main section').html(res.partial);
            $('main #header').html('Products');
        },'json');
        return false;
    });
});