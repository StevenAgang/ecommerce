<?php $this->load->view('partials/header', $title); ?>

        <main>
            <h1>Forgot Password</h1>
            <form action="" method="post">
                <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
                <input type="email" name="email" placeholder="Email">
                <input type="submit" name="login" value="Reset Password">
            </form>
            <a href="<?=base_url('register')?>">Dont have an account?</a>
            <a href="<?=base_url('')?>">Alread have an account?</a>
        </main>
<?php $this->load->view('partials/footer'); ?>