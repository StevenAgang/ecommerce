            <section id="container">
                <section id="product_container">
                    <figure id="image_container">
                        <img id="main_image" src="<?=$images->main?>" alt="">
                        <article id="images">
<?php                   foreach($images as $key => $image)
                        {
                            if($key !== 'main' && $image !== '')
                            {
?>
                            <img src="<?=$image?>" alt="">
<?php
                            }
                        }
?>
                        </article>
                    </figure>
                    <figcaption id="details">
                        <h1><?=$details['name']?></h1>
                        <h2><mark>$<?=$details['price']?></mark></h2>
                        <h3>Stocks: <?=$details['stocks']?></h3>
                        <article>
                            <p><?=$details['description']?></p>
                        </article>
                        <form id="add_carts" action="<?=base_url('carts/add_to_cart')?>" method="post">
                            <input type="hidden" name="category_id" value="<?=$category_id?>">
                            <input type="hidden" name="prod_id" value="<?=$prod_id?>">
                            <input type="hidden" name="id" value="<?=$details['id']?>">
                            <input type="number" name="quantity" value="1">
                            <input type="text" name="sum" value="$<?=$details['price']?>" disabled>
<?php                       if($details['stocks'] === '0')
                            {
?>
                                <input type="submit" name='addtocart' value="Add to Cart" disabled>
<?php  
                            }
                            else
                            {
?>
                                <input type="submit" name='addtocart' value="Add to Cart">
<?php
                            }
?>
                        </form> 
                    </figcaption>
                </section>
                <section id="product" class="products">
                    <h1>You Might Like</h1>
                    <ul>
<?php
          foreach($similar as $product)
          {
            if($details['id'] !== $product['id'])
            {
?>
                        <li>
                            <figure>
                                <a href="<?=base_url('products/details/' . $product['category_id'] .'/' . $product['id'])?>">
<?php                           foreach($similar_image as $image)
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
          }
?>
                    </ul> 
                </section>
            </section>