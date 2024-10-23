<article id="status">
          <h1>Status</h1>
          <form action="<?=base_url('orders/order_category/0')?>" method="post">
               <p><?=isset($all_order['all']) ? $all_order['all'] : $all_order ?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label for="all_orders">
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="all order" value="">
                    <input type="submit" name="all_orders" value="">
                    All Orders
               </label>
          </form>
          <form action="<?=base_url('orders/order_category/1')?>" method="post">
               <p><?=$pending['pending']?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label for="pending">
                    <input type="hidden" name="id" value="1">
                    <input type="hidden" name="status" value="pending">
                    <input type="submit" name="pending" value="">
                    Pending
               </label>
          </form>
          <form action="<?=base_url('orders/order_category/2')?>" method="post">
               <p><?=$process['process']?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label for="on-process">
                    <input type="hidden" name="id" value="2">
                    <input type="hidden" name="status" value="on-process">
                    <input type="submit" name="on-process" value="">
                    On-Process
               </label>
          </form>
          <form action="<?=base_url('orders/order_category/3')?>" method="post">
               <p><?=$shipped['shipped']?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label for="shipped">
                    <input type="hidden" name="id" value="3">
                    <input type="hidden" name="status" value="shipped">
                    <input type="submit" name="shipped" value="">
                    Shipped
               </label>
          </form>
          <form action="<?=base_url('orders/order_category/4')?>" method="post">
               <p><?=$delivered['delivered']?></p>
               <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
               <label for="delivered">
                    <input type="hidden" name="id" value="4">
                    <input type="hidden" name="status" value="delivered">
                    <input type="submit" name="delivered" value="">
                    Delivered
               </label>
          </form>
        </article>
        <section id="orders">
               <table>
                    <tr id="head">
                         <th>
<?php                    if(isset($category))
                         {
                              switch($category)
                              {
                                   case '1':
?>
                                   <h1>Pending</h1>
<?php
                                   break;
                                   case '2':
?>
                                   <h1>On-Process</h1>
<?php
                                   break;
                                   case '3':
?>
                                   <h1>Shipped</h1>
<?php
                                   break;
                                    case '4':
?>
                                   <h1>Delivered</h1>
<?php
     
                                   break;
                                   default:
?>
                                   <h1>All Order</h1>
<?php
                                   break;
                              }    
                         }
                         else
                         {
?>
                                   <h1> All Order</h1>
<?php       
                         }
                         
?>
                          (<?=isset($all_order['partial']) ? $all_order['partial'] : $all_order ?>)</th>
                         <th>Order ID #</th>
                         <th>Order Date</th>
                         <th>Reciever</th>
                         <th>Total Amount</th>
                         <th>Status</th>
                    </tr>
<?php if(isset($orders) && !empty($orders))
     {
          foreach($orders['items'] as $order)
          {
?>
                    <tr class="items">
                         <td>
<?php                    foreach($images as $image)
                         {
                              if($image['id'] === $order['id'])
                              {
?>
                              <img src="<?=$image['image']->main?>" alt="">
<?php
                              }
                         }
?>
                              <p id="quantity"><?=$order['quantity']?> Items</p>
                         </td>
                         <td><?=$order['id']?></td>
                         <td><?=$order['created_at']?></td>
                         <td>
<?php                         foreach($orders['user'] as $info)
                              {
                                   if($info['id'] === $order['user_id'])
                                   {
?>
                              <h1><?=ucwords($info['firstname']) . ' ' . ucwords($info['lastname'])?></h1>
                              <p><?=ucwords($info['street']) . ' ' . ucwords($info['city']) . ' ' . ucwords($order['zip'])?></p> 
<?php    
                                   }                            
                              }
?>
                         </td>
                         <td>$<?=$order['total_price']?></td>
                         <td>
                              <form class="change_status" action="<?=base_url('orders/change_status')?>" method="post">
                                   <input type="hidden" name="id" value="<?=$order['id']?>">
                                   <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
                                   <select name="status">
<?php                         switch($order['status'])
                              {
                                   case '1':
?>
                                        <option value="pending" selected>Pending</option>
                                        <option value="process">On-Process</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered">Delivered</option>
<?php
                                   break;
                                   case '2':
?>
                                        <option value="pending">Pending</option>
                                        <option value="process" selected>On-Process</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered">Delivered</option>
<?php
                                   break;
                                   
                                   case '3':
?>
                                        <option value="pending">Pending</option>
                                        <option value="process">On-Process</option>
                                        <option value="shipped" selected>Shipped</option>
                                        <option value="delivered">Delivered</option>
<?php
                                   break;
                                   case '4':
?>
                                        <option value="pending">Pending</option>
                                        <option value="process">On-Process</option>
                                        <option value="shipped">Shipped</option>
                                        <option value="delivered" selected>Delivered</option>
<?php
                                   break;
                                 
                              }
?>
                                   </select>
                              </form>
                         </td>
                    </tr>
<?php
          }
     }
?>
               </table>
        </section>