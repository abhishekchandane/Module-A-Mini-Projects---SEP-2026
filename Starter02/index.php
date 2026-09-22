<?php
date_default_timezone_set('UTC');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Starter Task 02</title>
</head>

<body>
    <div id="server-info">
        <p>Server Time: <?php echo date('H:i:s'); ?></p>
        <p>PHP Version: <?php echo phpversion(); ?></p>
    </div>
</body>

</html>