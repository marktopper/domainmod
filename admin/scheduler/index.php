<?php
/**
 * /admin/scheduler/index.php
 *
 * This file is part of DomainMOD, an open source domain and internet asset manager.
 * Copyright (C) 2010-2015 Greg Chetcuti <greg@chetcuti.com>
 *
 * Project: http://domainmod.org   Author: http://chetcuti.com
 *
 * DomainMOD is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * DomainMOD is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with DomainMOD. If not, see
 * http://www.gnu.org/licenses/.
 *
 */
?>
<?php
include("../../_includes/start-session.inc.php");
include("../../_includes/init.inc.php");

require_once(DIR_ROOT . "classes/Autoloader.php");
spl_autoload_register('DomainMOD\Autoloader::classAutoloader');

$error = new DomainMOD\Error();
$system = new DomainMOD\System();
$time = new DomainMOD\Timestamp();

include(DIR_INC . "head.inc.php");
include(DIR_INC . "config.inc.php");
include(DIR_INC . "config-demo.inc.php");
include(DIR_INC . "software.inc.php");
include(DIR_INC . "database.inc.php");

$system->authCheck();
$system->checkAdminUser($_SESSION['s_is_admin'], $web_root);

$page_title = "Task Scheduler";
$software_section = "admin-system-task-scheduler";

$sql = "SELECT id
        FROM scheduler
        ORDER BY sort_order ASC";
$result = mysqli_query($connection, $sql) or $error->outputOldSqlError($connection);
?>
<?php include(DIR_INC . 'doctype.inc.php'); ?>
<html>
<head>
    <title><?php echo $system->pageTitle($software_title, $page_title); ?></title>
    <?php include(DIR_INC . "layout/head-tags.inc.php"); ?>
</head>
<body>
<?php include(DIR_INC . "layout/header.inc.php"); ?>
The Task Scheduler allows you to run various system jobs at specified times, which helps keep your <?php
echo $software_title; ?> installation up-to-date and running smoothly, as well as notifies you of important information,
such as emailing you to let you know about upcoming Domain & SSL Certificate expirations.<BR>
<BR>
In order to use the Task Scheduler you must setup a cron/scheduled job on your web server to execute the file
<strong>cron.php</strong>, which is located in the root folder of your <?php echo $software_title; ?> installation.
This file should be executed <em>every 10 minutes</em>, and once it's setup the Task Scheduler will be live.<BR>
<BR>
Using the Task Scheduler is optional, but <em>highly</em> recommended.
<BR><BR>
<?php
$dwdisplay = new DomainMOD\DwDisplay();
$schedule = new DomainMOD\Scheduler();

echo $dwdisplay->tableTop();
while ($row = mysqli_fetch_object($result)) {
    echo $schedule->show($connection, $row->id);
}
echo $dwdisplay->tableBottom();

?>
<?php include(DIR_INC . "layout/footer.inc.php"); ?>
</body>
</html>
