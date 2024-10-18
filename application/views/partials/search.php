<?php   if(isset($admin) && $admin === true)
        {
                if(isset($selected_product))
                {
?>
          <form id="search" action="<?=base_url('products/admin_product_search')?>" method='post'>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">          
               <input type="search" name="searchbar" placeholder="Search Product">
               <input type="submit" name="searchsubmit" value="">
           </form>
           <p>|</p>
<?php
                }
                else
                {
?>
          <form id="search_order" action="<?=base_url('orders/search_order')?>" method='post'>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">          
               <input type="search" name="searchbar" placeholder="Search Order">
               <input type="submit" name="searchsubmit" value="">
           </form>
           <p>|</p>
<?php      
                }
        }
        else
        {
?>
          <form id="search" action="<?=base_url('products/search')?>" method='post'>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">          
               <input type="search" name="searchbar" placeholder="Search your favorite clothes here">
               <input type="submit" name="searchsubmit" value="">
           </form>
           <p>|</p>
<?php
        }
?>
<?php   if(isset($admin) && $admin === true)
        {
                if(isset($selected_product) && $selected_product === true)
                {
?>
            <form id="add" action="" method="get">
                <input type="submit" name="add_product" value="Add Product">
           </form>
<?php
                }
        }
        else
        {
?>
            <form id="display_cart" action="<?=base_url('carts/order_carts')?>" method="get">
                <input type="submit" name="cart" value="Cart <?=isset($cart_count['count']) && !empty($cart_count['count'])? '('.$cart_count['count'].')': "";?>">
           </form>
<?php
        }
?>