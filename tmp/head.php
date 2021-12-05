<!DOCTYPE html>
<html dir='rtl'>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="alqnasfox website here were you can stat involed in netowrking world">
<meta name="author" content="ALqnasfox">

<link type="image/x-icon" rel="shortcut icon" href="favicon.ico"/>
<head>
    <title>القناص شات</title>
    <?php
    $css = glob("$theme/css/*.css");
        foreach($css as $value)
        {
            echo "<link rel='stylesheet' href='/$script_path/$value'>\n";
        }
    ?>

</head>

<body class="container">
    
    <section class="header">
        <ul>
        <?php
            echo "<a href='/$script_path/home'><li>الرئيسية</li></a>";
            if(isset($_SESSION["name"]))
            {
                echo "<a href='/$script_path/massanger'><li>الشات</li></a>
                <a href='/$script_path/profile'><li>البروفايل</li></a>
                <a href='/$script_path/logout'><li>تسجيل الخروج</li></a>";
            }
            if(!isset($_SESSION["name"]))
            {
                echo "<a href='/$script_path/signup'><li>التسجيل</li></a>
                <a href='/$script_path/login'><li>تسجيل الدخول</li></a>";
            }
        ?>

        </ul>

    </section>