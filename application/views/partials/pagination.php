        <section id="pagination">
               <ul>
<?php    
          $count = isset($total_products['count']) ? (int)$total_products['count'] / 10 : $total_products / 10;
          if($count > 0)
          {
               $wholenumber = (int)$count;
               $fraction = $count - $wholenumber;
               if($fraction > 0)
               {
                    $wholenumber += 1;
               }

               for($itr = 1; $itr <= $wholenumber; $itr++)
               {
?>
                    <a class="page" href="<?=base_url('products/next_page/'. $itr)?>">
                         <li><?=$itr?></li>
                    </a>
<?php
               }
          }
?>  
               </ul>
        </section>