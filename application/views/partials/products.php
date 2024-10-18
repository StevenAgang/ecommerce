        <article id="category">
          <h1>Category</h1>
          <form action="<?=base_url('products/categories/allproduct')?>" method="post">
               <p><?=$all_products?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label for="all_product">
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="all Products" value="">
                    <input type="submit" name="all_product" value="">
                    All Products
               </label>
          </form>
          <form action="<?=base_url('products/categories/men')?>" method="post">
               <p><?=$men['count']?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label for="men">
                    <input type="hidden" name="id" value="1">
                    <input type="hidden" name="category" value="men">
                    <input type="submit" name="men" value="">
                    Men
               </label>
          </form>
          <form action="<?=base_url('products/categories/women')?>" method="post">
               <p><?=$women['count']?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label for="women">
                    <input type="hidden" name="id" value="2">
                    <input type="hidden" name="category" value="women">
                    <input type="submit" name="women" value="">
                    Women
               </label>
          </form>
          <form action="<?=base_url('products/categories/kids')?>" method="post">
               <p><?=$kids['count']?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label for="kids">
                    <input type="hidden" name="id" value="3">
                    <input type="hidden" name="category" value="kids">
                    <input type="submit" name="kids" value="">
                    Kids
               </label>
          </form>
        </article>
        <section id="product">
          <h1><?=isset($category) ? ucwords($category) : 'All Products';?>(<?=isset($total_products['count']) ? $total_products['count'] : $total_products ?>)</h1>
            <ul>
<?php     $count = 0;
          foreach($products as $product)
          {
               if($count === 10)
                    break;
               $count++;
?>
               <li>
                    <figure>
                         <a href="<?=base_url('products/details/' . $product['category_id'] .'/' . $product['id'])?>">
<?php                    foreach($images as $image)
                         {
                              if($image['id'] === $product['id'])
                              {
?>                  
                                   <img src="<?=$image['image']->main?>" alt="">
<?php     
                              }      
                         }
?>
                         </a>
                    </figure>
                   <figcaption>
                         <a href="<?=base_url('products/details/' . $product['category_id'] .'/' . $product['id'])?>">
                              <p id="description"><?=$product['name']?></p>
                              <p id="price">$<?=$product['price']?></p>
                         </a>
                   </figcaption>
               </li>
<?php             
          }
?>
          </ul>
<?php     if(empty($products))
          {
?>
               <h1 class="message">Products does not exist</h1>
<?php
          }
?>
<?php $this->load->view('partials/pagination'); ?>
        </section>