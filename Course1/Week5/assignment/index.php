<!DOCTYPE html>
<html lang="en">
<?php
$text = "Swadhin Saha PHP"
?>
<head>
    <title><?php echo $text ?></title>
</head>
<body>
<h1><?php echo $text ?></h1>
<p>The SHA256 hash of "Swadhin Saha" is <?php print hash('sha256', 'Swadhin Saha'); ?></p>
<pre>
ASCII ART:
    **********
    *
    *
    **********
             *
             *
    **********
</pre>

</body>
</html>
