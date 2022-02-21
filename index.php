<?php
require "pages.php";
?>

<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" integrity="sha512-NhSC1YmyruXifcj/KFRWoC561YpHpc5Jtzgvbuzx5VozKpWvQ+4nXhPdFgmx8xqexRcpAglTj9sIBWINXa8x5w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" integrity="sha384-ejwKkLla8gPP8t2u0eQyL0Q/4ItcnyveF505U0NIobD/SMsNyXrLti6CWaD0L52l" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css" />
    <title>SmartCGMS developer zone</title>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1><span class="mainlogo"></span>SmartCGMS developer zone</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-auto col-lg-auto col-sm-12">
            <ul class="list-group">
                <li class="list-group-item list-group-item-primary">SmartCGMS</li>
                <a <?php echo buildMenuLink("") ?> >Home</a>
                <a href="https://diabetes.zcu.cz/" class="list-group-item">Project homepage</a>
                <a <?php echo buildMenuLink("releasenotes") ?> >Release notes</a>
                <a <?php echo buildMenuLink("license") ?> >License</a>
                <a <?php echo buildMenuLink("faq") ?> >FAQ</a>
                <li class="list-group-item list-group-item-primary">API</li>
                <a <?php echo buildMenuLink("apioverview") ?> >Overview</a>
                <a <?php echo buildMenuLink("apideviceevent") ?> >Device event</a>
                <a <?php echo buildMenuLink("apientities") ?> >Entities</a>
                <a <?php echo buildMenuLink("apifilter") ?> ><i class="bi bi-caret-right"></i>&nbsp;Filter</a>
                <a <?php echo buildMenuLink("apidiscretemodel") ?> ><i class="bi bi-caret-right"></i>&nbsp;Discrete model</a>
                <a <?php echo buildMenuLink("apisignalmodel") ?> ><i class="bi bi-caret-right"></i>&nbsp;Signal model</a>
                <a <?php echo buildMenuLink("apimetric") ?> ><i class="bi bi-caret-right"></i>&nbsp;Metric</a>
                <a <?php echo buildMenuLink("apisolver") ?> ><i class="bi bi-caret-right"></i>&nbsp;Solver</a>
                <a <?php echo buildMenuLink("apiapproximator") ?> ><i class="bi bi-caret-right"></i>&nbsp;Approximator</a>
                <a <?php echo buildMenuLink("apisignal") ?> ><i class="bi bi-caret-right"></i>&nbsp;Signal</a>
                <a <?php echo buildMenuLink("apiinspection") ?> >Inspection interface</a>
                <a <?php echo buildMenuLink("apifeedback") ?> >Feedback</a>
                <li class="list-group-item list-group-item-primary">Existing entities</li>
                <a <?php echo buildMenuLink("entsignalgenerator") ?> >Signal generator filter</a>
                <a <?php echo buildMenuLink("entdatabasefilters") ?> >Database reader &amp; writer filter</a>
                <a <?php echo buildMenuLink("entcalculatedsignal") ?> >Calculated signal filter</a>
                <a <?php echo buildMenuLink("enterrormetric") ?> >Error metric filter</a>
                <a <?php echo buildMenuLink("entlogfilters") ?> >Log &amp; log replay filter</a>
                <a <?php echo buildMenuLink("entdrawingfilter") ?> >Drawing filter (legacy)</a>
                <a <?php echo buildMenuLink("entfeedbacksender") ?> >Feedback sender filter</a>
                <li class="list-group-item list-group-item-primary">C++ SDK</li>
                <a <?php echo buildMenuLink("sdkoverview") ?> >Overview</a>
                <a <?php echo buildMenuLink("sdkstructure") ?> >SDK structure</a>
                <a <?php echo buildMenuLink("sdkhelpers") ?> >Helpers</a>
                <a <?php echo buildMenuLink("sdkconfiguration") ?> ><i class="bi bi-caret-right"></i>&nbsp;Configuration</a>
                <a <?php echo buildMenuLink("sdkexecution") ?> ><i class="bi bi-caret-right"></i>&nbsp;Execution</a>
                <a <?php echo buildMenuLink("sdkoptimizer") ?> ><i class="bi bi-caret-right"></i>&nbsp;Optimizing parameters</a>
            </ul>

        </div>
        <div class="col">
            <?php includeCurrentPage(); ?>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>
