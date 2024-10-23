$(document).ready(function(){
    // POP UP MODAL FOR ADDING PRODUCTS
    $(document).on('click','#searchbar_carts #add',function(event){
        $('.modal').css('display','block');
        $('.modal-content').css('width','50rem');
        $('.modal-content').css('margin','0.5rem auto');
        $('.modal-content').css('padding','1rem');
        let html_str = '<h1>Add Product</h1>';
        html_str += '<form id="add_product" action="products/add_products" method="post" enctype="multipart/form-data">';
        html_str += '<input type="text" name="product_name" placeholder="Name">';  
        html_str += '<textarea id="description_box" name="description" cols="30" rows="10" placeholder="Description"></textarea>';
        html_str += '<label for="categories">Category:</label>';
        html_str += '<select name="categories">';
        html_str += '<option value="Men">Men</option>';
        html_str += '<option value="Women">Women</option>';
        html_str += '<option value="Kids">Kids</option>';  
        html_str += '</select>';
        html_str += '<input type="text" name="price" placeholder="$Price">';
        html_str += '<input type="text" name="stock" placeholder="Stocks">';
        html_str += '<p>UPLOAD IMAGE (MAX 5)</p>';
        for(let i = 1; i < 6; i++)
        {
            html_str += '<input type="file" name="image'+i+'" accept="image/*">';
        }
        for(let i = 1; i < 6; i++)
        {
            html_str += '<img class="img" id="image'+i+'" src="" alt="Empty">';
        }
        html_str += '<input type="checkbox" name="main1" checked>';
        html_str += '<input type="checkbox" name="main2">';
        html_str += '<input type="checkbox" name="main3">';
        html_str += '<input type="checkbox" name="main4">';
        html_str += '<input type="checkbox" name="main5">';
        for(let i = 1; i < 6; i++)
        {
            html_str += '<label for="main"'+i+'">Mark as Main</label>';
        }
        html_str += '<input type="submit" name="add" value="Add Product">';
        html_str += '</form>';
        $('.modal-content').html(html_str);
        $(window).click(function(event){
            if(event.target === $('.modal')[0])
            {
                $('.modal').css('display','none');
            }
        });
    });
    // FUNCTION WHEN THE FILE INPUT IS CHANGE
    $(document).on('change','#add_product input[type=file]',function(){
        let file = this;
        let reader = new FileReader();
        let name = $(this).attr('name');
        if(file.files && file.files[0]){
            reader.onload = function(event){
                $('#add_product img#'+name).attr('src',event.target.result);
            }
        }
        reader.readAsDataURL(file.files[0]);
    });
    // FUNCTION TO ALLOW ONLY 1 CHECKBOX IS CHECKED
    $(document).on('change','#add_product input[type=checkbox]',function(){
        $('#add_product input[type=checkbox]').not(this).prop('checked', false);
    });
    $(document).on('focus','#add_product input[type=text]',function(){
        if($(this).attr('name') === 'product_name')
        {
            $(this).attr('placeholder','Name');
        }
        else if($(this).attr('name') === 'price')
        {
            $(this).attr('placeholder','$Price');
        }
        else
        {
            $(this).attr('placeholder','Stock');
        }
    });

    //  POP UP MODAL FOR EDIT PRODUCT
    $(document).on('click','#orders #edit input[type=submit]',function(event){
        event.preventDefault();
        let form = $(this).closest('form');
        $('.modal').css('display','block');
        $('.modal-content').css('width','50rem');
        $('.modal-content').css('margin','0.5rem auto');
        $('.modal-content').css('padding','1rem');
        let html_str = '<h1>Edit Product #'+form.find('input[name=id]').val()+'</h1>';
        html_str += '<form id="edit_product" action="products/change_products" method="post" enctype="multipart/form-data">';
        html_str += '<input type="hidden" name="id" value="'+form.find('input[name=id]').val()+'">';
        html_str += '<input type="text" name="product_name" placeholder="Name">';  
        html_str += '<textarea id="description_box" name="description" cols="30" rows="10" placeholder="Description"></textarea>';
        html_str += '<label for="categories">Category:</label>';
        html_str += '<select name="categories">';
        html_str += '<option value="Men">Men</option>';
        html_str += '<option value="Women">Women</option>';
        html_str += '<option value="Kids">Kids</option>';  
        html_str += '</select>';
        html_str += '<input type="text" name="price" placeholder="$Price">';
        html_str += '<input type="text" name="stock" placeholder="Stocks">';
        html_str += '<p>UPLOAD IMAGE (MAX 5)</p>';
        for(let i = 1; i < 6; i++)
        {
            html_str += '<input type="file" name="image'+i+'" accept="image/*">';
        }
        for(let i = 1; i < 6; i++)
        {
            html_str += '<img class="img" id="image'+i+'" src="" alt="Empty">';
        }
        html_str += '<input type="checkbox" name="main1" checked>';
        html_str += '<input type="checkbox" name="main2">';
        html_str += '<input type="checkbox" name="main3">';
        html_str += '<input type="checkbox" name="main4">';
        html_str += '<input type="checkbox" name="main5">';
        for(let i = 1; i < 6; i++)
        {
            html_str += '<label for="main"'+i+'">Mark as Main</label>';
        }
        html_str += '<input type="submit" name="edit" value="Edit Product">';
        html_str += '</form>';
        html_str += "<h5> Note: If you have for example 4 images in your current product and you insert only 1 image, the other's will be deleted";
        $('.modal-content').html(html_str);
        $(window).click(function(event){
            if(event.target === $('.modal')[0])
            {
                $('.modal').css('display','none');
            }
        });
    });
    $(document).on('change','#edit_product input[type=file]',function(){
        let file = this;
        let reader = new FileReader();
        let name = $(this).attr('name');
        if(file.files && file.files[0]){
            reader.onload = function(event){
                $('#edit_product img#'+name).attr('src',event.target.result);
            }
        }
        reader.readAsDataURL(file.files[0]);
    });
    $(document).on('change','#edit_product input[type=checkbox]',function(){
        $('#edit_product input[type=checkbox]').not(this).prop('checked', false);
    });
    $(document).on('focus','#edit_product input[type=text]',function(){
        if($(this).attr('name') === 'product_name')
        {
            $(this).attr('placeholder','Name');
        }
        else if($(this).attr('name') === 'price')
        {
            $(this).attr('placeholder','$Price');
        }
        else
        {
            $(this).attr('placeholder','Stock');
        }
    });
});
// VALIDATION FOR ADD PRODUCT INPUTS
function validation()
{
    let empty_text = 0;
    let number = ['1','2','3','4','5','6','7','8','9','0'];
    let file = 0;
    let price_checked = false;
    let stock_checked = false;
    let is_number = false;
    let is_number_stock = false; 
    $('#add_product input[type=text]').each(function(){
        if($(this).val() === '')
        {
            empty_text++;
            $(this).attr('placeholder',$(this).attr('placeholder')+' is required');
        }
    });
    $('#add_product input[type=file]').each(function(){
        if($(this).val() === '')
        {
            file++;
        }
    });
    if($('#add_product input[name=price]').val() !== '')
    {
        let valid = 0;
        price_checked = true;
        for(let i = 0; i < $('#add_product input[name=price]').val().length; i++)
        {
            for(let itr = 0; itr < number.length; itr++)
            {
                if($('#add_product input[name=price]').val()[i] === number[itr])
                {
                    valid = 1;
                }
            }
            if(valid === 1)
            {
            is_number = true;
            }
        }
    }
    if($('#add_product input[name=stock]').val() !== '')
    {   
        let valid = 0;
        stock_checked = true
        for(let i = 0; i < $('#add_product input[name=stock]').val().length; i++)
        {
            for(let itr = 0; itr < number.length; itr++)
            {
                if($('#add_product input[name=stock]').val()[i] === number[itr])
                {
                    valid = 1;                       
                }
            }
        }
        if(valid === 1)
        {
            is_number_stock = true;
        }
    }
    if(is_number === false && price_checked === true)
    {
        $('#add_product input[name=price]').attr('placeholder','');
        $('#add_product input[name=price]').attr('placeholder',$('#add_product input[name=price]').attr('placeholder')+' cannot contain words');
        $('#add_product input[name=price]').val('');
        return false;
    }
    if(is_number_stock === false && stock_checked === true)
    {
        $('#add_product input[name=stock]').attr('placeholder','')
        $('#add_product input[name=stock]').attr('placeholder',$('#add_product input[name=stock]').attr('placeholder')+' cannot contain words');
        $('#add_product input[name=stock]').val('');
        return false;
    }
    if(file === 5)
    {
        alert('upload image atleast 1');
        return false;
    }
    if(empty_text === 0 && is_number !== false && is_number_stock !== false && file !== 5)
    {
        return true;
    }
}