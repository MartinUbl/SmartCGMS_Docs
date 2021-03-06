<?php
require "pages.php";
?>

<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="dist/normalize.min.css" />
    <link rel="stylesheet" href="dist/bootstrap.min.css" />
    <link rel="stylesheet" href="dist/bootstrap-icons.css" />
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
                <a <?php echo buildMenuLink("apilibraryinterface") ?> >Library interface</a>
                <a <?php echo buildMenuLink("apiinterface") ?> >Object model and interface</a>
                <a <?php echo buildMenuLink("apideviceevent") ?> >Device event</a>
                <a <?php echo buildMenuLink("apitime") ?> >Time and time segments</a>
                <a <?php echo buildMenuLink("apientities") ?> >Entities</a>
                <a <?php echo buildMenuLink("apifilter") ?> ><i class="bi bi-caret-right"></i>&nbsp;Filter</a>
                <a <?php echo buildMenuLink("apimodel") ?> ><i class="bi bi-caret-right"></i>&nbsp;Model</a>
                <a <?php echo buildMenuLink("apidiscretemodel") ?> >&nbsp;&nbsp;<i class="bi bi-caret-right"></i>&nbsp;Discrete model</a>
                <a <?php echo buildMenuLink("apisignalmodel") ?> >&nbsp;&nbsp;<i class="bi bi-caret-right"></i>&nbsp;Signal model</a>
                <a <?php echo buildMenuLink("apimetric") ?> ><i class="bi bi-caret-right"></i>&nbsp;Metric</a>
                <a <?php echo buildMenuLink("apisolver") ?> ><i class="bi bi-caret-right"></i>&nbsp;Solver</a>
                <a <?php echo buildMenuLink("apiapproximator") ?> ><i class="bi bi-caret-right"></i>&nbsp;Approximator</a>
                <a <?php echo buildMenuLink("apisignal") ?> ><i class="bi bi-caret-right"></i>&nbsp;Signal</a>
                <a <?php echo buildMenuLink("apiinspection") ?> >Inspection interface</a>
                <a <?php echo buildMenuLink("apifeedback") ?> >Feedback</a>
                <a <?php echo buildMenuLink("apisimpleinterface") ?> >"Simple" interface</a>
                <a <?php echo buildMenuLink("apiconfiguration") ?> >Configuration</a>
                <li class="list-group-item list-group-item-primary">Existing entities</li>
                <a <?php echo buildMenuLink("entsignalgenerator") ?> >Signal generator filter</a>
                <a <?php echo buildMenuLink("entdatabasefilters") ?> >Database reader &amp; writer filter</a>
                <a <?php echo buildMenuLink("entdbformat") ?> ><i class="bi bi-caret-right"></i>&nbsp;Database structure</a>
                <a <?php echo buildMenuLink("entcalculatedsignal") ?> >Calculated signal filter</a>
                <a <?php echo buildMenuLink("enterrormetric") ?> >Error metric filter</a>
                <a <?php echo buildMenuLink("entlogfilters") ?> >Log &amp; log replay filter</a>
                <a <?php echo buildMenuLink("entdrawingfilter") ?> >Drawing filter (legacy)</a>
                <a <?php echo buildMenuLink("entfeedbacksender") ?> >Feedback sender filter</a>
                <a <?php echo buildMenuLink("entsignalfilters") ?> >Mapping and masking filters</a>
                <a <?php echo buildMenuLink("entmiscfilters") ?> >Other filters</a>
                <a <?php echo buildMenuLink("entmetrics") ?> >Metrics</a>
                <a <?php echo buildMenuLink("entsolvers") ?> >Solvers</a>
                <a <?php echo buildMenuLink("entapproximators") ?> >Approximators</a>
                <li class="list-group-item list-group-item-primary">C++ SDK</li>
                <a <?php echo buildMenuLink("sdkoverview") ?> >Overview</a>
                <a <?php echo buildMenuLink("sdkstructure") ?> >SDK structure</a>
                <a <?php echo buildMenuLink("sdkhelpers") ?> >Helpers</a>
                <a <?php echo buildMenuLink("sdkconfiguration") ?> ><i class="bi bi-caret-right"></i>&nbsp;Configuration</a>
                <a <?php echo buildMenuLink("sdkexecution") ?> ><i class="bi bi-caret-right"></i>&nbsp;Execution</a>
                <a <?php echo buildMenuLink("sdkoptimizer") ?> ><i class="bi bi-caret-right"></i>&nbsp;Optimizing parameters</a>
                <li class="list-group-item list-group-item-primary">Tools</li>
                <a <?php echo buildMenuLink("frontends") ?> >Frontends</a>
                <a <?php echo buildMenuLink("console") ?> ><i class="bi bi-caret-right"></i>&nbsp;Console</a>
                <a <?php echo buildMenuLink("gui") ?> ><i class="bi bi-caret-right"></i>&nbsp;GUI</a>
            </ul>

        </div>
        <div class="col">
            <?php includeCurrentPage(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <footer>Copyright &copy; since 2018 University of West Bohemia</footer>
        </div>
    </div>
</div>

    <script src="dist/bootstrap.min.js"></script>
</body>
</html>
