<?php $this->load->view('partials/header', $title); ?>
<?php $this->load->view('partials/errorhandler'); ?>
        <main>
            <h1>Login</h1>
            <form id="login" action="<?=base_url('login_auth')?>" method="post">
            <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <input type="submit" name="login" value="Login">
            </form>
            <a href="<?=base_url()?>">Continue as Guest</a>
            <a href="<?=base_url('register')?>">Dont have an account?</a>
        </main>
        <article id="account">
            <h5>Use this account to access admin panel</h5>
            <mark>Email: sample@gmail.com</mark>
            <mark>Password: 11111111</mark>
        </article>
<?php $this->load->view('partials/footer'); ?>