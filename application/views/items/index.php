<?php $this->load->view('include/admin/header'); ?>
<div id="main">
    <?php $this->load->view('include/admin/sidebar'); ?>

    <div id="content">
        <div class="page-head">
            <span class="page-heading"><?= $title ?></span>
            <span class="page-heading"><a href="<?= base_url(); ?>items/deleteNumbers">Delete Numbers</a><span>
            <?php $this->load->view('message'); ?>
        </div>
        <div id="form-wrap">
            <div class="row">
                <div class="col-xs-3">Unique Id</div>
                <div class="col-xs-3">Phone</div>
            </div>
            <?php
            if (!empty($items)) {
                foreach ($items as $key => $value) {
                    ?>
                    <div class="row">
                        <div class="col-xs-3"><?= $value['uniqueId'] ?></div>
                        <div class="col-xs-3"><?= $value['phone'] ?></div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<?php $this->load->view('include/footer'); ?> 