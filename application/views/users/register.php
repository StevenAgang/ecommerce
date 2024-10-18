<?php $this->load->view('partials/header', $title); ?>
<?php $this->load->view('partials/errorhandler'); ?>
        <main>
            <h1>Register</h1>
            <form id="register" action="<?=base_url('create_account')?>" method="post">
                <input type="hidden" name="<?=$csrf['name']?>" value="<?=$csrf['hash']?>">
                <input type="text" name="firstname" placeholder="First Name">
                <input type="text" name="lastname" placeholder="Last Name">
                <input type="text" name="mobilenumber" placeholder="Mobile Number">
                <input type="text" name="city" placeholder="City">
                <input type="text" name="street" placeholder="Street">
                <input type="text" name="postal" placeholder="Postal Code">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <input type="password" name="confirm-password" placeholder="Confirm Password">
                <input type="submit" name="register" value="Register">
            </form>
            <a href="<?=base_url('login')?>">Already have an account?</a>
        </main>
<?php $this->load->view('partials/footer'); ?>