        <article id="category">
          <h1>Category</h1>
          <form action="<?=base_url('products/admin_categories/allproduct')?>" method="post">
               <p><?=$all_products?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label>
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="all Products" value="">
                    <input type="submit" name="all_product" value="">
                    All Products
               </label>
          </form>
          <form action="<?=base_url('products/admin_categories/men')?>" method="post">
               <p><?=$men['count']?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label>
                    <input type="hidden" name="id" value="1">
                    <input type="hidden" name="category" value="men">
                    <input type="submit" name="men" value="">
                    Men
               </label>
          </form>
          <form action="<?=base_url('products/admin_categories/women')?>" method="post">
               <p><?=$women['count']?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label>
                    <input type="hidden" name="id" value="2">
                    <input type="hidden" name="category" value="women">
                    <input type="submit" name="women" value="">
                    Women
               </label>
          </form>
          <form action="<?=base_url('products/admin_categories/kids')?>" method="post">
               <p><?=$kids['count']?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label>
                    <input type="hidden" name="id" value="3">
                    <input type="hidden" name="category" value="kids">
                    <input type="submit" name="kids" value="">
                    Kids
               </label>
          </form>
        </article>
        <section id="orders">
               <table>
                    <tr id="head">
                        <th><?=isset($category) ? ucwords($category) . '(' . count($products) . ')' : 'All Order' . '(' . $all_products . ')';?></th>
                         <th>Product ID #</th>
                         <th>Price</th>
                         <th>Category</th>
                         <th>Stocks</th>
                         <th>Sold</th>
                         <th>Action</th>
                    </tr>
<?php if(isset($products) && !empty($products))
     {
          foreach($products as $index => $product)
          {
?>
                    <tr class="items">
                         <td>
<?php               foreach($images as $image)
                    {
                         if($image['id'] === $product['id'])
                         {
?>
                              <img src="<?=$image['image']->main?>" alt="">
<?php  
                              break;
                         }
                    }
?>
                              <p class="quantity"><?=$product['name']?></p>
                         </td>
                         <td><?=$product['id']?></td>
                         <td><?=$product['price']?></td>
                         <td><?=$product['category']?></td>
                         <td>$<?=$product['stocks']?></td>
                         <td><?=$sold[$index]['sold']?></td>
                         <td>
                              <form id="edit">
                                   <input type="hidden" name="id" value="<?=$product['id']?>">
                                   <input type="submit" name="edit" value="Edit Product">
                              </form>
                              <form id="delete" action="<?=base_url('products/delete_products')?>" method="post">
                                   <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
                                   <input type="hidden" name="id" value="<?=$product['id']?>">
                                   <input type="submit" name="edit" value="Delete Product">
                              </form>
                        </td>
                    </tr>
<?php
          }
     }
?>
               </table>
        </section>