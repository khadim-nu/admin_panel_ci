<?php $this->load->view('include/head'); ?>
<body>
    <header id="header">
        <div class="top-bar">
            <div class="right-pane">
                <span class="user">
                    <a href="<?= base_url(); ?>admin/login">Login</a>
                </span>
            </div>
            <strong class="logo">
                <a href="<?= base_url(); ?>welcome">Walter</a>
            </strong>
            <?php $this->load->view('message'); ?>
        </div>
    </header>

