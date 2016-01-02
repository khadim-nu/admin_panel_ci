<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('include/variables'); ?>
        <meta charset="utf-8">
        <!--<meta name="viewport" content="width=device-width,initial-scale=1">-->
        <title><?= $title; ?></title>
        <script type="text/javascript" src="<?= JS_URL; ?>jquery-latest.min.js"></script>
        <link rel="stylesheet" href="<?= CSS_URL; ?>bootstrap.min.css" media="all" type="text/css">
        <link rel="stylesheet" href="<?= CSS_URL; ?>all.css" media="all" type="text/css">
        <?php
        $url = $_SERVER['REQUEST_URI'];
        $pattern = '/login';
        if (strlen(strstr($url, $pattern)) > 0 || strlen(strstr($url, "/register")) > 0 || strlen(strstr($url, "/forgot_password")) > 0) {
            ?>
            <link rel="stylesheet" href="<?= CSS_URL ?>login/login.css" media="all" type="text/css">
        <?php } else {
            ?>
            <link rel="stylesheet" href="<?= CSS_URL ?>admin/admin.css" media="all" type="text/css">
            <script type="text/javascript" src="<?= JS_URL; ?>sidebar.js"></script>
        <?php }
        ?>
            
        <link rel="stylesheet" href="<?= CSS_URL; ?>jquery.dataTables.min.css" media="all" type="text/css">
        
        <link rel="stylesheet" href="<?= CSS_URL; ?>jquery-ui.css">
        
        <link rel="stylesheet" href="<?= CSS_URL; ?>sweet-alert.css" media="all" type="text/css"> 
        
        <script type="text/javascript" src="<?= JS_URL; ?>sweet-alert.min.js"></script>
    
    </head>