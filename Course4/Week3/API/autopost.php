<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Swadhin Saha</title>
    <script
            src="https://code.jquery.com/jquery-3.5.1.min.js"
            integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
            crossorigin="anonymous"></script>
</head>
<body>
<form id="target">
    <input type="text" name="nam" value="Hello">
    <img src="loading.gif" alt="loading" id="loading" style="display: none">
</form>

<div id="result"></div>

<script type="text/javascript">
    $('#target').change(function (event) {
        event.preventDefault();
        $('#loading').show();
        let form = $('#target');
        let txt = form.find('input[name="nam"]').val();
        $.post('autoecho.php', {
            val: txt
        }, function (data) {
            console.log(data);
            $('#loading').hide();
            $('#result').empty().append(data);
        }).error(function (msg) {
            console.log(msg);
            return false;
        });

    });
</script>
</body>
</html>