<?php
require __DIR__.'/../bootstrap/autoload.php';
$config = require_once '../config/app.php';
use App\Http\Controllers\BillingInstaller\BillingDependencyController;

$passwordMatched = false;
$showError = false;
$env = '../.env';
$envFound = is_file($env);
if ($envFound) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/..');
    $dotenv->load();
}
if (isset($_POST['submit'])) {

    $probePhrase = env('PROBE_PASS_PHRASE', '   ');
    //Unique password incase support team requires access to probe.php
    $password = '599fe9896c015afebff1789ea0078f61';

    $input = $_POST['passPhrase'];
    if (! in_array($input, [$probePhrase, $password])) {
        $showError = true;
    } else {
        $passwordMatched = true;
    }
}
?>
<!Doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Agora Invoicing Installer</title>

    <link rel="shortcut icon" href="./images/faveo.png" type="image/x-icon" />

<!--    <link rel="apple-touch-icon" href="./agora.png">-->

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">
    <?php
    // Array of CSS files with optional IDs for each link tag
    $css_files = [
        ['file' => './admin/css-1/all.min.css'],
        ['file' => './admin/css-1/adminlte.min.css', 'id' => 'default-styles'],
//        ['file' => './admin/css-1/adminlte-rtl.css', 'id' => 'rtl-styles'],
        ['file' => './admin/css-1/bs-stepper-rtl.css', 'id' => 'rtl-styles-2'],
        ['file' => './admin/css-1/bs-stepper.css', 'id' => 'default-styles-2'],
        ['file' => './admin/css-1/flag-icons.min.css'],
        ['file' => './admin/css-1/probe-rtl.css', 'id' => 'rtl-styles-1'],
        ['file' => './admin/css-1/probe.css', 'id' => 'default-styles-1'],
    ];

    // Loop through the array to generate <link> tags
    foreach ($css_files as $css) {
        $id = isset($css['id']) ? ' id="' . $css['id'] . '"' : ''; // Check if 'id' is set
        echo '<link rel="stylesheet" href="' . $css['file'] . '"' . $id . '>' . PHP_EOL;
    }
    ?>

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <style>
        .cursor-default { cursor: default !important; }
    </style>
</head>

<body class="layout-top-nav text-sm layout-navbar-fixed" dir="ltr">

<div class="wrapper">

    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">

        <div class="container">

            <a href="javascript:;" class="navbar-brand" style="">

                <img src="./images/agora-invoicing.png" alt="Agora Logo" class="brand-image install-img">
            </a>

            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-3"></div>

            <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">

                <li class="nav-item dropdown">

                    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">

                        <i id="flagIcon" class="flag-icon flag-icon-us"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right p-0" style="left: inherit; right: 0px;">

                        <a href="javascript:;" class="dropdown-item" id="englishOption">

                            <i class="flag-icon flag-icon-us mr-2"></i> English
                        </a>

                        <a href="javascript:;" class="dropdown-item" id="arabicOption">

                            <i class="flag-icon flag-icon-ar mr-2"></i> Arabic
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <?php
    if ($envFound && ! $passwordMatched){
        ?>
        <div class="content-wrapper" style="margin-top: 80px;">
            <div class="content">
                <div class="container pt-3 pb-3">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <form action="probe.php" method="post">
                            <div class="card-body">
                                <p class="text-center lead text-bold">Billing Probes</p>
                                <div class="form-group row">

                                    <label for="inputEmail1" class="col-sm-2 col-form-label">What's the magic phrase <span style="color: red;">*</span></label>

                                    <div class="col-sm-10">

                                        <input type="text" class="form-control" id="phrase" name="passPhrase" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">

                                <button class="btn btn-primary float-right" name="submit">
                                    Continue&nbsp;
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
    <div class="content-wrapper" style="margin-top: 80px;">

        <div class="content">

            <div class="container pt-3 pb-3">

                <div class="accordion" id="accordionExample">

                    <div class="steps">

                        <progress id="progress" value=0 max=100></progress>

                        <div class="step-item">

                            <button id="btn-server" class="step-button text-center cursor-default" type="button" aria-expanded="true" aria-controls="server">
                                1
                            </button>

                            <div class="step-title mt-2 text-bold">Server Requirements</div>
                        </div>

                        <div class="step-item">

                            <button id="btn-database" class="step-button text-center collapsed cursor-default" type="button" aria-expanded="false" aria-controls="database">
                                2
                            </button>

                            <div class="step-title mt-2">Database Setup</div>
                        </div>

                        <div class="step-item">

                            <button id="btn-start" class="step-button text-center collapsed cursor-default" type="button" aria-expanded="false" aria-controls="start">
                                3
                            </button>

                            <div class="step-title mt-2">Getting Started</div>
                        </div>

                        <div class="step-item">

                            <button id="btn-final" class="step-button text-center collapsed cursor-default" type="button" aria-expanded="false" aria-controls="final">
                                4
                            </button>

                            <div class="step-title mt-2">Final</div>
                        </div>
                    </div>

                    <div id="server" class="collapse show" role="tabpanel" data-bs-parent="#accordionExample">

                        <div class="card">

                            <div class="card-body">

                                <p class="text-center lead text-bold">Server Requirements</p>


                                <div class="row">

                                    <table class="table table-bordered">

                                        <thead style="background: #f5f5f5;">

                                        <tr><th style="width:50%;">Directory</th><th>Permissions</th></tr>
                                        </thead>

                                        <tbody>
                                        <?php
                                        $errorCount = 0;
                                        $basePath = substr(__DIR__, 0, -6);
                                        $billingController = new BillingDependencyController('probe');
                                        $details = $billingController->validateDirectory($basePath, $errorCount);

                                        foreach ($details as $item): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($item['extensionName'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="text-<?= htmlspecialchars($item['color'], ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?= htmlspecialchars($item['message'], ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">

                                    <table class="table table-bordered">

                                        <thead style="background: #f5f5f5;">

                                        <tr><th style="width:50%;">Requisites</th><th>Status</th></tr>
                                        </thead>

                                        <tbody>
                                        <?php
                                        $details = (new BillingDependencyController('probe'))->validateRequisites($errorCount);
                                        foreach ($details as $detail): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($detail['extensionName'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td style="color: <?= htmlspecialchars($detail['color'], ENT_QUOTES, 'UTF-8'); ?>;">
                                                    <?= $detail['connection']; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row">

                                    <table class="table table-bordered">

                                        <thead style="background: #f5f5f5;">

                                        <tr><th style="width:50%;">PHP Extensions</th><th>Status</th></tr>
                                        </thead>

                                        <tbody>
                                        <?php
                                        $details = (new BillingDependencyController('probe'))->validatePHPExtensions($errorCount);
                                        $extString = "Not Enabled<p>To enable this, please install the extension on your server and  update '".php_ini_loaded_file()."' to enable ".$detail['extensionName'].'</p>'
                                            .'<a href="https://support.faveohelpdesk.com/show/how-to-enable-required-php-extension-on-different-servers-for-faveo-installation">How to install PHP extensions on my server?</a>';

                                        foreach ($details as $item): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($item['extensionName'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td class="<?= $item['key'] === 'no-error' ? 'text-success' : 'text-warning'; ?>">
                                                    <?= $item['key'] === 'no-error' ? 'Enabled' : $extString; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">

                                    <table class="table table-bordered">

                                        <thead style="background: #f5f5f5;">

                                        <tr><th style="width:50%;">Mod Rewrite</th><th>Status</th></tr>
                                        </thead>
                                        <?php
                                        function getLicenseUrl()
                                        {
                                            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                                                $url = 'https://';
                                            } else {
                                                $url = 'http://';
                                            }
                                            // Append the host(domain name, ip) to the URL.
                                            $url .= $_SERVER['HTTP_HOST'];

                                            // Append the requested resource location to the URL
                                            $url .= $_SERVER['REQUEST_URI'];

                                            return str_replace('probe.php', 'db-setup', $url);
                                        }
                                        function checkUserFriendlyUrl()
                                        {
                                            if (function_exists('curl_init') === true) {
                                                try {
                                                    $ch = curl_init(getLicenseUrl());
                                                    curl_setopt($ch, CURLOPT_HEADER, true);
                                                    curl_setopt($ch, CURLOPT_NOBODY, true);
                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                                                    curl_exec($ch);
                                                    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                                    curl_close($ch);

                                                    return $httpcode != 404;
                                                } catch (Exception $e) {
                                                    return null;
                                                }
                                            }

                                            return null;
                                        }

                                        // Rewrite Engine Check
                                        $redirect = function_exists('apache_get_modules') ? (int) in_array('mod_rewrite', apache_get_modules()) : 2;
                                        $rewriteStatusColor = 'green';
                                        $rewriteStatusString = 'ON';
                                        if ($redirect == 2) {
                                            $rewriteStatusColor = '#F89C0D';
                                            $rewriteStatusString = 'Unable to detect';
                                        } elseif (!$redirect) {
                                            $errorCount++;
                                            $rewriteStatusColor = 'red';
                                            $rewriteStatusString = 'OFF';
                                        }

                                        // User Friendly URL Check
                                        $userFriendlyUrl = checkUserFriendlyUrl();
                                        $userFriendlyUrlStatusColor = 'green';
                                        $userFriendlyUrlStatusString = 'ON';

                                        if ($userFriendlyUrl === false) {
                                            $errorCount++;
                                            $userFriendlyUrlStatusColor = 'red';
                                            $userFriendlyUrlStatusString = 'OFF (If you are using apache, make sure <var><strong>AllowOverride</strong></var> is set to <var><strong>All</strong></var> in apache configuration)';
                                        } elseif ($userFriendlyUrl !== true) {
                                            $userFriendlyUrlStatusColor = '#F89C0D';
                                            $userFriendlyUrlStatusString = 'Unable to detect';
                                        }
                                        ?>
                                        <tbody>
                                        <tr>
                                            <td>Rewrite Engine</td>
                                            <td style='color: <?= htmlspecialchars($rewriteStatusColor, ENT_QUOTES, 'UTF-8'); ?>'>
                                                <?= htmlspecialchars($rewriteStatusString, ENT_QUOTES, 'UTF-8'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>User friendly URL</td>
                                            <td style='color: <?= htmlspecialchars($userFriendlyUrlStatusColor, ENT_QUOTES, 'UTF-8'); ?>'>
                                                <?= htmlspecialchars($userFriendlyUrlStatusString, ENT_QUOTES, 'UTF-8'); ?>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer">

                                <button class="btn btn-primary float-right"
                                    <?php if ($errorCount === 0): ?>
                                        onclick="gotoStep('database')"
                                    <?php else: ?>
                                        disabled
                                    <?php endif; ?>>
                                    Continue&nbsp;
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="database" class="collapse" role="tabpanel" aria-labelledby="database-trigger">

                        <?php
                        function getBaseUrl() {
                            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
                            $host = $_SERVER['HTTP_HOST'];
                            $path = dirname($_SERVER['SCRIPT_NAME']);
                            return $protocol . '://' . $host . $path;
                        }
                        ?>

                        <div class="card">

                            <div class="card-body">

                                <p class="text-center lead text-bold">Database Setup</p>

                                <div id="db_fields">

                                    <div class="form-group row">

                                        <label for="inputEmail6" class="col-sm-2 col-form-label">Host <span style="color: red;">*</span></label>

                                        <div class="col-sm-10">

                                            <input type="text" class="form-control" id="host" placeholder="Host" value="localhost">
                                        </div>
                                    </div>

                                    <div class="form-group row">

                                        <label for="inputEmail7" class="col-sm-2 col-form-label">Database name <span style="color: red;">*</span></label>

                                        <div class="col-sm-10">

                                            <input type="text" class="form-control" id="database_name" placeholder="Database">
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <label for="inputEmail4" class="col-sm-2 col-form-label">MySQL port number</label>

                                        <div class="col-sm-10">

                                            <input type="text" class="form-control" id="mysql_port" placeholder="Port Number">
                                        </div>
                                    </div>

                                    <div class="form-group row">

                                        <label for="inputEmail3" class="col-sm-2 col-form-label">Username <span style="color: red;">*</span></label>

                                        <div class="col-sm-10">

                                            <input type="email" class="form-control" id="username" placeholder="User name">
                                        </div>
                                    </div>

                                    <div class="form-group row">

                                        <label for="inputPassword3" class="col-sm-2 col-form-label">Password </label>

                                        <div class="col-sm-10">

                                            <input type="password" class="form-control" id="password" placeholder="Password">
                                        </div>
                                    </div>
                                </div>

                                <div id="db_config">
                                    <h6 class="mt-1 mb-3">This test will check prerequisites required to install Billing</h6>

                                    <div class="timeline timeline-inverse "  id="timeline-container">

                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">

                                <button class="btn btn-primary" onclick="previous()"><i class="fas fa-arrow-left"></i>&nbsp; Previous</button>

                                <button class="btn btn-primary float-right" type="submit" id="validate">Continue &nbsp;
                                    <i class="fas fa-arrow-right"></i>
                                </button>

                                <button class="btn btn-primary float-right" onclick="gotoStep('start')" id="continue">Continue &nbsp;
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="start" class="collapse" role="tabpanel" aria-labelledby="start-trigger">

                        <div class="card">

                            <div class="card-body">

                                <p class="text-center lead text-bold">Getting Started</p>

                                <div class="card card-light">

                                    <div class="card-header">

                                        <h3 class="card-title">Sign up as Admin</h3>
                                    </div>

                                    <div class="card-body">

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">First Name <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">

                                                <input type="text" id="admin_first_name" class="form-control"  placeholder="First Name">
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">Last Name <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">

                                                <input type="text" id="admin_last_name" class="form-control" placeholder="Last Name">
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">Username <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">

                                                <input type="email" id="admin_username" class="form-control" placeholder="User name">
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">Email <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">

                                                <input type="email" id="email" class="form-control" placeholder="User email">
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">Password <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">

                                                <input type="password" id="admin_password" class="form-control" placeholder="Password">
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">Confirm Password <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">

                                                <input type="password" id="admin_confirm_password" class="form-control" placeholder="Confirm Password">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-light">

                                    <div class="card-header">

                                        <h3 class="card-title">System Information</h3>
                                    </div>
                                    <div class="card-body">

<!--                                        This will add when general timezone is build-->

<!--                                        <div class="form-group row">-->
<!---->
<!--                                            <label class="col-sm-2 col-form-label">Timezone</label>-->
<!---->
<!--                                            <div class="col-sm-10">-->
<!--                                                <select id="timezone" name="timezone" class="form-control select2">-->
<!--                                                </select>-->
<!--                                            </div>-->
<!--                                        </div>-->

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">Environment <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">

                                                <select id="environment" name="environment" class="form-control select2">
                                                    <option value="production" selected>Production</option>
                                                    <option value="development" >Development</option>
                                                    <option value="testing" >Testing</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">Cache Driver <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">

                                                <select id="driver" name="driver" class="form-control select2">

                                                    <option value="file" selected>File</option>
                                                    <option value="redis">Redis</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card card-light" id="redis-setup">

                                    <div class="card-header">

                                        <h3 class="card-title">Redis Setup</h3>
                                    </div>
                                    <div class="card-body">

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">Redis Host <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="redis_host" placeholder="Redis Host">
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">Redis Port <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="redis_port" placeholder="Redis Port">
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <label class="col-sm-2 col-form-label">Redis Password <span style="color: red;">*</span></label>

                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="redis_password" placeholder="Redis Password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">

                                <button class="btn btn-primary float-right" onclick="submitForm()">Continue &nbsp;
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="final" class="collapse" role="tabpanel" aria-labelledby="final-trigger">

                        <div class="card">

                            <div class="card-body">

                                <p class="text-center lead text-bold">Your Billing Application is Ready!</p>

                                <div class="alert" style="background:#f5f5f5;">
                                    All right, sparky! You’ve made it through the installation.
                                </div>

                                <div class="row">

                                    <div class="col-6">

                                        <p class="lead">Learn More</p>

                                        <p><i class="fas fa-newspaper"></i>&nbsp;&nbsp;<a href="https://support.faveohelpdesk.com/knowledgebase">Knowledge base</a></p>
                                        <p><i class="fas fa-envelope"></i>&nbsp;&nbsp;<a href="mailto:support@ladybirdweb.com">Email Support</a></p>
                                    </div>

                                    <div class="col-6">

                                        <p class="lead">Next Step</p>
                                        <a href="<?php echo getBaseUrl() ?>/login">
                                            <div class="btn btn-primary">Login to Billing &nbsp;<i class="fas fa-arrow-right"></i></div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <footer class="main-footer">

        <div class="float-right d-none d-sm-inline">Agora Invoicing <?php echo $config['version']; ?></div>

        <strong>Copyright © 2014-2021 <a href="javascript:;">Ladybird Web Solution Pvt Ltd.</a>.</strong> All rights reserved.
    </footer>
</div>

<script src="./admin/js/jquery.min.js"></script>
<script src="./admin/js/bootstrap.bundle.min.js"></script>
<script src="./admin/js/adminlte.min.js"></script>
<script src="./admin/js/bs-stepper.min.js"></script>

<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>-->

<script>
    document.getElementById('validate').addEventListener('click', function(event) {
        event.preventDefault();
        dbFormSubmit();
    });
    document.addEventListener('DOMContentLoaded', function() {
        // Get the driver select element and Redis setup section
        const driverSelect = document.getElementById('driver');
        const redisSetup = document.getElementById('redis-setup');

        // Function to toggle Redis setup visibility
        function toggleRedisSetup() {
            if (driverSelect.value === 'redis') {
                redisSetup.style.display = 'block';
            } else {
                redisSetup.style.display = 'none';
            }
        }

        // Initial check when the page loads
        toggleRedisSetup();

        // Add event listener for driver select change
        driverSelect.addEventListener('change', toggleRedisSetup);
    });
    document.addEventListener("DOMContentLoaded", function() {
        const timezoneSelect = document.getElementById("timezone");
        const baseUrl = `<?php echo getBaseUrl() ?>`;
        const endpoint = baseUrl + '/getTimeZone';

        // Function to call the API and populate the timezone select
        function fetchTimezones() {
            fetch(endpoint)
                .then(response => response.json())
                .then(data => {
                    // Clear previous options
                    timezoneSelect.innerHTML = '';

                    // Assuming data is an array of timezones
                    data.forEach(timezone => {
                        const option = document.createElement("option");
                        option.value = timezone.id; // Set the value
                        option.textContent = timezone.name; // Set the display text
                        timezoneSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching timezone values:', error));
        }

        // Use MutationObserver to detect when the 'show' class is added
        const targetNode = document.getElementById('start');
        const observerConfig = { attributes: true, attributeFilter: ['class'] };

        const observer = new MutationObserver((mutationsList) => {
            for (let mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.target.classList.contains('show')) {
                    fetchTimezones();  // Call the API when 'show' class is added
                    observer.disconnect();  // Optional: stop observing after the first trigger
                    break;
                }
            }
        });

        // Start observing the target element
        observer.observe(targetNode, observerConfig);
    });
    function previous() {
        const dbConfigStyle = document.getElementById("db_config").style.display;

        if (dbConfigStyle === 'none') {
            gotoStep('server');
        } else {
            validateData();
        }
    }
    function submitForm() {
        // Cache input elements
        const fields = {
            firstName: $('#admin_first_name'),
            lastName: $('#admin_last_name'),
            username: $('#admin_username'),
            email: $('#email'),
            password: $('#admin_password'),
            confirmPassword: $('#admin_confirm_password'),
            environment: $('#environment'),
            driver: $('#driver'),
            redisHost: $('#redis_host'),
            redisPort: $('#redis_port'),
            redisPassword: $('#redis_password')
        };

        // Clear previous errors
        $('input').removeClass('is-invalid');
        $('.error').remove();

        // Helper function to add error messages
        const showError = (field, message) => {
            field.addClass('is-invalid');
            field.after(`<span class="error invalid-feedback">${message}</span>`);
        };

        // Validate fields
        let isValid = true;
        const requiredFields = {
            firstName: 'Firstname',
            lastName: 'Lastname',
            username: 'Username',
            email: 'Email',
            password: 'Password',
            confirmPassword: 'Confirm Password'
        };

        Object.keys(requiredFields).forEach(field => {
            if (!fields[field].val()) {
                showError(fields[field], `${requiredFields[field]} is required`);
                isValid = false;
            }
        });

        // Validate passwords match
        if (fields.password.val() !== fields.confirmPassword.val()) {
            showError(fields.confirmPassword, 'Passwords do not match');
            isValid = false;
        }

        // Validate Redis fields if driver is set to 'redis'
        if (fields.driver.val() === 'redis') {
            const redisFields = {
                redisHost: 'Redis Host',
                redisPort: 'Redis Port',
                redisPassword: 'Redis Password'
            };

            Object.keys(redisFields).forEach(field => {
                if (!fields[field].val()) {
                    showError(fields[field], `${redisFields[field]} is required`);
                    isValid = false;
                }
            });
        }

        if (!isValid) return; // Stop if validation fails

        // Collect data
        const data = {
            first_name: fields.firstName.val(),
            last_name: fields.lastName.val(),
            user_name: fields.username.val(),
            email: fields.email.val(),
            password: fields.password.val(),
            environment: fields.environment.val(),
            cache_driver: fields.driver.val()
        };

        if (fields.driver.val() === 'redis') {
            data.redis_host = fields.redisHost.val();
            data.redis_port = fields.redisPort.val();
            data.redis_password = fields.redisPassword.val();
        }

        // Send AJAX request
        const baseUrl = `<?php echo getBaseUrl() ?>`;
        $.ajax({
            url: `${baseUrl}/accountcheck`,
            type: 'POST',
            data: data,
            success: function(response) {
                console.log('Form submitted successfully');
                alert('Form submitted successfully!');
                gotoStep('final');
            },
            error: function(error) {
                console.error('Error submitting form', error);
                alert('Error submitting form.');
            }
        });
    }
    validateData();
    function dbFormSubmit() {
        // Collect data from form inputs
        const fields = {
            host: document.getElementById('host'),
            databaseName: document.getElementById('database_name'),
            username: document.getElementById('username'),
            password: document.getElementById('password')
        };

        // Clear previous error messages
        Object.values(fields).forEach(field => {
            field.classList.remove('is-invalid');
            const errorMessage = field.nextElementSibling;
            if (errorMessage && errorMessage.classList.contains('error')) {
                errorMessage.remove();
            }
        });

        // Helper function to add error messages
        const showError = (field, message) => {
            field.classList.add('is-invalid');
            const errorSpan = document.createElement('span');
            errorSpan.className = 'error invalid-feedback';
            errorSpan.innerText = message;
            field.after(errorSpan);
        };

        // Validate required fields
        let isValid = true;
        const requiredFields = {
            host: 'Host',
            databaseName: 'Database Name',
            username: 'Username',
        };

        Object.keys(requiredFields).forEach(field => {
            if (!fields[field].value.trim()) {
                showError(fields[field], `${requiredFields[field]} is required`);
                isValid = false;
            }
        });

        // Stop form submission if validation fails
        if (!isValid) return;

        // Collect data if validation is successful
        const data = {
            host: fields.host.value,
            databasename: fields.databaseName.value,
            username: fields.username.value,
            password: fields.password.value
        };

        const url = `<?php echo getBaseUrl() ?>/posting`;
        document.getElementById('timeline-container').innerHTML = '';
        postData(url, data);
        validateData();
    }
    function toggleContinueButton(hasError) {
        const continueButton = document.getElementById('continue');
        continueButton.disabled = hasError;
    }

    async function postData(url, data) {
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json;charset=UTF-8' },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error(`Error with request: ${response.statusText}`);
            }
            const result = await response.json();
            populateTimeline(result);
            const hasError = result.results?.some(item => item.status === 'Error');

            if (!hasError) {
                await handleApiRequests();
            }
            toggleContinueButton(hasError);
        } catch (error) {
            console.error('Error during postData:', error.message);
        }
    }

    async function handleApiRequests() {
        const baseUrl = `<?php echo getBaseUrl() ?>`;
        const endpoints = [
            `${baseUrl}/create/env`,
            `${baseUrl}/preinstall/check`,
            `${baseUrl}/migrate`
        ];

        for (let url of endpoints) {
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json;charset=UTF-8' }
                });

                if (!response.ok) {
                    throw new Error(`Error with request to ${url}: ${response.statusText}`);
                }

                const result = await response.json();
                populateTimeline(result);
            } catch (error) {
                console.error('Error during API request:', error.message);
                break; // Stop the loop on the first error
            }
        }
    }

    function populateTimeline(response) {
        const container = document.getElementById('timeline-container');
        let results = [];

        // Check if the response is in the first format
        if (response.result && response.result.success) {
            results.push({ message: response.result.success, status: 'Ok' });

            if (response.result.next) {
                results.push({ message: response.result.next, status: 'Info' });
            }
        }

        // Check if the response is in the second format
        if (response.results && Array.isArray(response.results)) {
            results = results.concat(response.results);
        }

        results.forEach(result => {
            const { iconClass, bgClass } = getIconAndBgClass(result.status);

            const existingLoader = container.querySelector('.fa-spinner');

            if(existingLoader){
                existingLoader.parentElement.remove();
            }

            const timelineItem = `
            <div>
                <i class="${iconClass} ${bgClass}" data-toggle="tooltip" title="${result.status}"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header border-0">${result.message}</h3>
                </div>
            </div>
        `;

            container.innerHTML += timelineItem;
        });
    }

    function getIconAndBgClass(status) {
        switch (status) {
            case 'Ok':
                return { iconClass: 'fas fa-check', bgClass: 'bg-success' };
            case 'Error':
                return { iconClass: 'fas fa-times', bgClass: 'bg-danger' };
            default:
                return { iconClass: 'fas fa-spinner fa-spin', bgClass: 'bg-info' };
        }
    }


    function validateData() {

        var k = document.getElementById("validate");

        var m = document.getElementById("continue");

        var n = document.getElementById("db_fields");

        var p = document.getElementById("db_config");

        if (k.style.display == "block") {

            k.style.display = "none";

            m.style.display = "block";

            n.style.display = "none";

            p.style.display = "block";

        } else {

            k.style.display = "block";

            m.style.display = "none";

            n.style.display = "block";

            p.style.display = "none";
        }
    }

    function gotoStep(value) {

        const progress = document.querySelector('#progress');

        if(value === 'server') {
            progress.setAttribute('value', 0);
            document.getElementById('server').classList.add('show');
            document.getElementById('btn-server').setAttribute('aria-expanded', true);
            document.getElementById('database').classList.remove('show');
            document.getElementById('btn-database').setAttribute('aria-expanded', false);
            document.getElementById('start').classList.remove('show');
            document.getElementById('btn-start').setAttribute('aria-expanded', false);
            document.getElementById('final').classList.remove('show');
            document.getElementById('btn-final').setAttribute('aria-expanded', false);

            document.getElementById('btn-server').nextElementSibling.classList.add('text-bold');
            document.getElementById('btn-license').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-database').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-start').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-code').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-final').nextElementSibling.classList.remove('text-bold');
        }

        if(value === 'database') {
            progress.setAttribute('value', 33.33);
            document.getElementById('server').classList.remove('show');
            document.getElementById('btn-server').setAttribute('aria-expanded', false);
            document.getElementById('database').classList.add('show');
            document.getElementById('btn-database').setAttribute('aria-expanded', true);
            document.getElementById('start').classList.remove('show');
            document.getElementById('btn-start').setAttribute('aria-expanded', false);
            document.getElementById('final').classList.remove('show');
            document.getElementById('btn-final').setAttribute('aria-expanded', false);

            document.getElementById('btn-server').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-database').nextElementSibling.classList.add('text-bold');
            document.getElementById('btn-start').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-final').nextElementSibling.classList.remove('text-bold');
        }

        if(value === 'start') {
            progress.setAttribute('value', 66.66);
            document.getElementById('server').classList.remove('show');
            document.getElementById('btn-server').setAttribute('aria-expanded', false);
            document.getElementById('database').classList.remove('show');
            document.getElementById('btn-database').setAttribute('aria-expanded', false);
            document.getElementById('start').classList.add('show');
            document.getElementById('btn-start').setAttribute('aria-expanded', true);
            document.getElementById('final').classList.remove('show');
            document.getElementById('btn-final').setAttribute('aria-expanded', false);

            document.getElementById('btn-server').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-database').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-start').nextElementSibling.classList.add('text-bold');
            document.getElementById('btn-final').nextElementSibling.classList.remove('text-bold');
        }

        if(value === 'final') {
            progress.setAttribute('value', 100);
            document.getElementById('server').classList.remove('show');
            document.getElementById('btn-server').setAttribute('aria-expanded', false);
            document.getElementById('database').classList.remove('show');
            document.getElementById('btn-database').setAttribute('aria-expanded', false);
            document.getElementById('start').classList.remove('show');
            document.getElementById('btn-start').setAttribute('aria-expanded', false);
            document.getElementById('final').classList.add('show');
            document.getElementById('btn-final').setAttribute('aria-expanded', true);

            document.getElementById('btn-server').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-database').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-start').nextElementSibling.classList.remove('text-bold');
            document.getElementById('btn-final').nextElementSibling.classList.add('text-bold');
        }
    }

    // const stepButtons = document.querySelectorAll('.step-button');
    //
    // const progress = document.querySelector('#progress');
    //
    // Array.from(stepButtons).forEach((button,index) => {
    //
    //     button.addEventListener('click', () => {
    //
    //         progress.setAttribute('value', index * 100 /(stepButtons.length - 1) );//there are 3 buttons. 2 spaces.
    //
    //         stepButtons.forEach((item, secindex)=>{
    //
    //             if(index === secindex) {
    //
    //                 item.setAttribute('aria-expanded', true);
    //
    //                 item.classList.remove('done');
    //
    //                 document.getElementById(item.getAttribute('aria-controls')).classList.add('show')
    //             } else {
    //
    //                 item.setAttribute('aria-expanded', false);
    //
    //                 document.getElementById(item.getAttribute('aria-controls')).classList.remove('show')
    //             }
    //             if(index > secindex){
    //
    //                 item.classList.add('done');
    //             }
    //
    //             if(index < secindex){
    //
    //                 item.classList.remove('done');
    //             }
    //         })
    //     })
    // })
</script>

<script>

    var body = document.body;
    var currentDir = body.getAttribute('dir');

    var defaultStyles = document.querySelectorAll('[id^="default-styles"]');
    var rtlStyles = document.querySelectorAll('[id^="rtl-styles"]');

    const flagIcon = document.getElementById('flagIcon');
    const englishOption = document.getElementById('englishOption');
    const arabicOption = document.getElementById('arabicOption');

    englishOption.addEventListener('click', function() {
        flagIcon.className = 'flag-icon flag-icon-us';
        flagIcon.alt = 'English';
        body.setAttribute('dir', 'ltr');

        defaultStyles.forEach(function(link) {
            link.disabled = false;
        });
        rtlStyles.forEach(function(link) {
            link.disabled = true;
        });
    });

    arabicOption.addEventListener('click', function() {
        flagIcon.className = 'flag-icon flag-icon-ar';
        flagIcon.alt = 'Arabic';
        body.setAttribute('dir', 'rtl');

        defaultStyles.forEach(function(link) {
            link.disabled = true;
        });
        rtlStyles.forEach(function(link) {
            link.disabled = false;
        });
    });

    defaultStyles.forEach(function(link) {
        link.disabled = false;
    });
    rtlStyles.forEach(function(link) {
        link.disabled = true;
    });
</script>
</body>
</html>
