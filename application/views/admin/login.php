<?php $this->load->view('include/head'); ?>

<div id="login-wrap">
    <div class="main-holder">
        <div class="heading-area">
            <STRONG class="heading">Walter Tool - Login</STRONG>
        </div>
        <div class="holder">
            <div class="wrapper">
                <div class="form-wrap">
                    <form id = "login" method="post" action="<?= base_url() . $user_role; ?>/login">
                        <fieldset>
                            <div class="input-wrap">
                                <input type="email" name="email" placeholder="Email ID" data-trigger="change" data-parsley-required data-type="email">
                            </div>
                            <div class="input-wrap">
                                <input type="password" name="password" placeholder="Password" data-trigger="change" data-parsley-required data-type="password" data-parsley-minlength="<?= PASSWORD_MIN_LEN ?>" data-parsley-maxlength="<?= PASSWORD_MAX_LEN ?>">
                            </div>
                            <div class="sign-area">
                                <button class="sign-btn">Login in</button>
                                <span class="forgot"><a href="<?= base_url() . $user_role; ?>/forgot_password">forgot password?</a></span>
                            </div>
                        </fieldset>
                    </form>
                    <div class="div-wrap">
                        <?php $this->load->view('message'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('include/footer'); ?>