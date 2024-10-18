<?php $this->load->view('partials/dashboard_header'); ?> 
        <main>
            <header>
                <img src="/assets/img/cloth.png" alt="">
                <h1>Elegant Fashion!</h1>
<?php   if(isset($user_data))
        {       
                if(isset($admin) && $admin === true)
                {
?>
                <a id="customer_view" href="<?=base_url('products/admin_switch_view')?>">Switch View</a>
<?php
                }
?>
                <span>
                        <h1 id="user">Hello! <?=ucwords($user_data['firstname']) .' '. ucwords($user_data['lastname'])?></h1>
                        <form id="logout" action="<?=base_url('logout')?>" method="get"> 
                                <input type='submit' name='logout' value='Logout'>
                        </form>
                </span>
                
<?php
        }
        else
        {
?>
                <span>
                        <button id="signup">Signup</button>
                        <button id="login">Login</button>
                </span>
              
<?php          
        }
?>
            </header>
<?php   if(isset($admin) && $admin === true)
        {
?>
                <h1 id="header">Orders</h1>
<?php
        }
?>
            <article id="searchbar_carts">
                <?php $this->load->view('partials/search'); ?>
            </article>
            <section>

            </section>
        </main>
<?php $this->load->view('partials/dashboard_footer'); ?> 