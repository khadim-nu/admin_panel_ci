<footer id="footer">

</footer>
<?php
$url = $_SERVER['REQUEST_URI'];
$pattern = '/login';
if (strlen(strstr($url, $pattern)) > 0 || strlen(strstr($url, "/register")) > 0 || strlen(strstr($url, "/forgot_password")) > 0) {
    ?>
    <link rel="stylesheet" href="<?= CSS_URL ?>login/login.css" media="all" type="text/css">

    <script type="text/javascript" src="<?= JS_URL; ?>highcharts.js"></script>
    <script type="text/javascript" src="<?= JS_URL; ?>exporting.js"></script>

    <script type="text/javascript" src="<?= JS_URL; ?>jquery.dataTables.min.js"></script>

    <script type="text/javascript" src="<?= JS_URL; ?>jquery-ui.js"></script>

    <script type="text/javascript" src="<?= JS_URL; ?>jquery.main.js"></script>
    <script type="text/javascript" src="<?= JS_URL; ?>bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= JS_URL; ?>customize.js"></script>
<?php } ?>
<!-- For parsley  -->
<script type="text/javascript" src="<?= JS_URL; ?>parsley.js"></script>
<script type="text/javascript" src="<?= JS_URL; ?>script.js"></script>


<input type ="hidden" value ="<?= $_SERVER['HTTP_HOST'] ?>" id = "current-enviroment">
<script>
    $(document).ready(function () {

        var default_img = <?= json_encode(base_url() . DEFAULT_SRC); ?>;
        $('img').on('error', function () {
            $(this).attr("src", default_img);
        });
    });
</script>
</body>
</html>