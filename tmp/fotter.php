<section class='fotter'>
    <p><?php echo "Aalqnasfox Projects &copy".date("Y"); ?></p>
</section>
<?php
    $js = glob("style/js/*.js");
    foreach($js as $value)
    {
        echo "<script src='/$script_path/$value'></script>\n";
    }
?>
</body>
</html>