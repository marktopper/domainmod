<?php
/**
 * /assets/add/registrar.php
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
include(DIR_INC . "software.inc.php");
include(DIR_INC . "database.inc.php");

$system->authCheck();

$page_title = "Adding A New Registrar";
$software_section = "registrars-add";

// Form Variables
$new_registrar = $_POST['new_registrar'];
$new_url = $_POST['new_url'];
$new_notes = $_POST['new_notes'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($new_registrar != "" && $new_url != "") {

        $query = "INSERT INTO registrars
                  (`name`, url, notes, insert_time)
                  VALUES
                  (?, ?, ?, ?)";
        $q = $conn->stmt_init();

        if ($q->prepare($query)) {

            $timestamp = $time->time();

            $q->bind_param('ssss', $new_registrar, $new_url, $new_notes, $timestamp);
            $q->execute();
            $q->close();

        } else {
            $error->outputSqlError($conn, "ERROR");
        }

        $_SESSION['s_result_message'] = "Registrar <div class=\"highlight\">" . $new_registrar . "</div> Added<BR>";

        if ($_SESSION['s_has_registrar'] != '1') {

            $system->checkExistingAssets($connection);

            header("Location: ../../domains.php");

        } else {

            header("Location: ../registrars.php");

        }
        exit;

    } else {

        if ($new_registrar == "") $_SESSION['s_result_message'] .= "Please enter the registrar name<BR>";
        if ($new_url == "") $_SESSION['s_result_message'] .= "Please enter the registrar's URL<BR>";

    }

}
?>
<?php include(DIR_INC . 'doctype.inc.php'); ?>
<html>
<head>
    <title><?php echo $system->pageTitle($software_title, $page_title); ?></title>
    <?php include(DIR_INC . "layout/head-tags.inc.php"); ?>
</head>
<body onLoad="document.forms[0].elements[0].focus()">
<?php include(DIR_INC . "layout/header.inc.php"); ?>
<form name="add_registrar_form" method="post">
    <strong>Registrar Name (100)</strong>
    <a title="Required Field">
        <div class="default_highlight"><strong>*</strong></div>
    </a><BR><BR>
    <input name="new_registrar" type="text" value="<?php echo $new_registrar; ?>" size="50" maxlength="100">
    <BR><BR>
    <strong>Registrar's URL (100)</strong>
    <a title="Required Field">
        <div class="default_highlight"><strong>*</strong></div>
    </a><BR><BR>
    <input name="new_url" type="text" value="<?php echo $new_url; ?>" size="50" maxlength="100">
    <BR><BR>
    <strong>Notes</strong><BR><BR>
    <textarea name="new_notes" cols="60" rows="5"><?php echo $new_notes; ?></textarea>
    <BR><BR>
    <input type="submit" name="button" value="Add This Registrar &raquo;">
</form>
<?php include(DIR_INC . "layout/footer.inc.php"); ?>
</body>
</html>
