<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" description="width=device-width, initial-scale=1.0">
        <meta name="description" content="<?=$content?>">
        <title><?=$title?></title>
        <link rel="stylesheet" href="/assets/css/dashboardstyle.css">
        <link rel="stylesheet" href="/assets/css/details.css">
        <link rel="stylesheet" href="/assets/css/cartstyle.css">
        <link rel="stylesheet" href="/assets/css/admin_dashboard_style.css">
        <link rel="stylesheet" href="/assets/css/restriction.css">
        <link rel="icon" type="image/x-icon" href="/assets/img/eco.jpg">
        <script src="/assets/lib/jquery-3.7.1.js"></script>
        <script src="/assets/js/dashboard.js"></script>
        <script src="/assets/js/details.js"></script>
        <script src="/assets/js/cart.js"></script>
        <script src="/assets/js/order.js"></script>
        <script src="/assets/js/admin_product.js"></script>
    </head>
    <body>
    <?php $this->load->view('partials/popup_modal'); ?>
        <aside>
            <img id="logo" src="/assets/img/logo.png" alt="">
            <a title=" Home" href="<?=base_url('')?>"><img src="/assets/img/shop.png" alt=""></a>
<?php   if(isset($admin) && $admin === true)
        {
?>
            <a title="Products" id="admin_product_view" href="<?=base_url('products/switch_product_view/product_view')?>"><img src="/assets/img/clothes.png" alt=""></a>
<?php
        }
?>
        </aside>