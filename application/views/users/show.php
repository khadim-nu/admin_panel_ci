<?php $this->load->view('include/admin/header'); ?>
<div id="main">
    <?php $this->load->view('include/admin/sidebar'); ?>
    <div id="content">
        <div class="page-head">
            <span class="page-heading">Social Users</span>
            <?php $this->load->view('message'); ?>
        </div>
        <div class="table-wrapper">
            <div class="holder">
                <div class="block wide-view">
                    <div class="row-fluid">
                        <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="sorting">Name</th>
                                    <th class="hidden-480">Email</th>
                                    <th class="hidden-480">SocialiD</th>
                                    <th class="hidden-480">From</th>
                                    <th class="hidden-480">created_at</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                if ($users) {
                                    foreach ($users as $row) {
                                        ?>
                                        <tr>
                                            <td><?= $row['name']; ?></td>
                                            <td class="hidden-480"><?= $row['email']; ?></td>
                                            <td class="hidden-480"><?= $row['socialiD']; ?></td>
                                            <td class="hidden-480"><?= $row['type']; ?></td>
                                            <td class="hidden-phone"><?= ($row['createdAt']); ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>

        </div>
    </div>
</div>
<?php $this->load->view('include/footer'); ?> 