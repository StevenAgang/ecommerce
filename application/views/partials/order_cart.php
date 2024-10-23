        <section id='order_cart'>
<?php   if(isset($order) && !empty($order))
        {
            foreach($order as $orders)
            {
?>
                <article>
                    <figure>
<?php               foreach($images as $image)
                    {
                        if($image['id'] === $orders['id'])
                        {
?>
                        <img src="<?=$image['image']->main?>" alt="">
<?php
                        }
                    }
?>
                    </figure>
                    <figcaption>
                        <h1><?=$orders['name']?></h1>
                        <h2><mark><?=$orders['price']?></mark></h2>
                        <form id="carts" action="" method="post">
                            <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">  
                            <input type="hidden" name="cart_id" value="<?=$orders['cart_id']?>">
                            <label for="quantity">Quantity:</label>
                            <input type="number" name="quantity" value="<?=$orders['quantity']?>">
                            <label for="sum">Total Price:</label>
                            <input type="text" name="sum" value="$<?=$orders['total_price']?>" disabled>
                            <input type="submit" name='remove' value="X">
                        </form>
                    </figcaption>
                </article>
<?php   
            }
        }
?>
        </section>
        <section id='billing'>
            <section id="summary">
                <h1>Order Summary</h1>
                <p>Items: <span>$<?=$item_price?></span></p>
                <p>Shipping Fee: <span>$<?=$shipping?></span></p>
                <p id="amount">Total Amount: <span>$<?=$total_amount?></span></p>
            </section>
            <form id="checkout" action="">
                    <input type="submit" name="checkout" value="Proceed to Checkout">
            </form>
        </section>