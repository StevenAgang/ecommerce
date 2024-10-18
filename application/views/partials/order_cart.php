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
            <section>
                <header>
                    <h1>Shipping Information</h1>
                    <form id="bill" action="">
                        <label for="same_with_billing">
                            <input type="checkbox" name="same_with-billing" value="Same in Billing" checked>
                            Same in Billing
                        </label>
                    </form>
                    <form id="shipping" action="<?=base_url('carts/set_shipping')?>" method="post">
                        <input type="text" name="fname" placeholder="First name" disabled>
                        <input type="text" name="lname" placeholder="Last name" disabled>
                        <input type="text" name="address" placeholder="Address" disabled>
                        <input type="text" name="city" placeholder="City" disabled>
                        <input type="text" name="zip" placeholder="Zip" disabled>
                    </form>
                </header>
            </section>
            <section>
                <header>
                    <h1>Billing Information</h1>
                    <form id="bill_info" action="<?=base_url('carts/set_billing')?>" method="post">
                        <input type="text" name="fname" placeholder="First name">
                        <input type="text" name="lname" placeholder="Last name">
                        <input type="text" name="address" placeholder="Address">
                        <input type="text" name="city" placeholder="City">
                        <input type="text" name="zip" placeholder="Zip">
                    </form>
                </header>
            </section>
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