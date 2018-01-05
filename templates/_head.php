<?php
$current = mpc::current();
if (empty($current)) {
    $current = 'RadioPi not playing';
}
?>
<!doctype html>
<html lang="en">
<head>
    <title>RadioPi</title>
    <!-- Required meta tags -->
    <meta content="#3E3F3A" name="theme-color">
    <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon">
    <link rel="icon" type="image/png" href="/assets/radiopi_icon.png"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/bootstrap.min.css">
    <link rel="stylesheet" href="/css/font-awesome.css">
    <link rel="stylesheet" href="/assets/sandstone.css">
    <link rel="stylesheet" href="/assets/overrides.css">

    <!-- Optional JavaScript -->
    <script src="/assets/jquery3.2.1.js"></script>
    <script src="/assets/popper.min.js"></script>
    <script src="/assets/bootstrap.min.js"></script>
</head>
<body>
<div style="width: 100%; position: relative; top:0; left:0;">
    <div class="navbar navbar-dark bg-primary" style="width: 100%; position: absolute; top: 0; left: 0; height: 40px; ">
        <div class="text-white navbar-brand"
             style="height: 35px; display: inline-block; width: 80vw; position: absolute; top: 5px;">
            <a href="/"><img src="assets/radiopi_sm.png"> <span>RadioPi</span></a>
        </div>
        <div style="position: absolute; top: 8px; right: 5px;">
            <small><a href="/?search"><span class="btn btn-outline-success">Search</span></a></small>
        </div>
    </div>
    <div class="" style="width: 100%; position: absolute; top: 40px; left: 0; overflow: auto;">
        <div class=" bg-secondary text-white" style="height: 1px;">
            <?php if ($current != 'RadioPi not playing') { ?>
                <span class="ajax btn btn-danger btn-circle btn-xl " data-command="stop" style="margin: 0 5px;"><span
                            class="fa fa-stop"></span></span>
            <?php } ?>
        </div>
        <div class=" bg-secondary text-white" style="min-height: 50px; padding-left: 85px; padding-top: 15px;">
            <span style="font-size: 1.2em"><?php echo $current; ?></span> <br>Current cold bootup time 1:09
        </div>
        <div style="margin-top: 25px">
