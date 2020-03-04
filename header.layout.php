<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Copy House</title>
    <link rel="shortcut icon" type="image/jpg" href="./IMG/favi.jpg"/>
    <link rel="stylesheet" href="./CSS/main.css" />
    <link rel="stylesheet" href="./CSS/bootstrap.min.css" />
    <link rel="stylesheet" href="./CSS/all.css" />
    


</head>
<body>
<?php require_once './user-only.inc.php'; ?>
<?php include './navbar.inc.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 mt-3">
            <?php include './sidebar.inc.php'; ?>
        </div>
        <div class="col-md-10 mt-3">
        <hr>
        <?php require_once './Helper.class.php'; ?>

            <?php if(Helper::ifError()) { ?>
                <div class="alert alert-danger">
                <strong>Greska!</strong> <?php echo Helper::getError(); ?>
            </div>
            <?php } ?>

            <?php if(Helper::ifMessage()) { ?>
                <div class="alert alert-dark">
                    <strong>Uspesno!</strong> <?php echo Helper::getMessage(); ?>
                </div>
            <?php } ?>


