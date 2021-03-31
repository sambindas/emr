<?php
include 'includes/master.php';

$allowed_roles = ['Doctor'];
$user_role = getLoggedInUser('role');

if (!in_array($user_role, $allowed_roles) && $user_role != 'Super Admin') {
    $_SESSION['e_msg'] = 'Youre not authorised to access that page.';
    header('Location: patients.php');
    exit;
}
$_SESSION['pt'] = 'Document Patient Encounter';
$id = $_GET['patient'];
if (!$id) {
    $_SESSION['e_msg'] = 'Invalid Patient.';
    header('Location: patients.php');
}
$patient = getPatient($id);
if (!$patient) {
    $_SESSION['e_msg'] = 'Invalid Patient.';
    header('Location: patients.php');
}
$last_visit = getLastVisit($id);
if ($last_visit) {
    if (is_array($last_visit)) {
        $pc = unserialize($last_visit['presenting_complains']);
        $dp = unserialize($last_visit['prescriptions']);
        $lv = '<div style="float:left;">';
        $lv .= '<p><b>Date of Visit:</b> '.date('d-m-Y', strtotime($last_visit['created_at'])).'</p>';
        $lv .= '<p><b>Presenting Complains:</b> '.implode(',', $pc).'</p>';
        $lv .= '<p><b>History of Presenting Complains:</b> '.$last_visit['history_of_complains'].'</p>';
        $lv .= '<p><b>Diagnosis:</b> '.date('d-m-Y', strtotime($last_visit['created_at'])).'</p>';
        $lv .= '<p><b>Durgs Prescribed:</b> '.implode($dp).'</p>';
        $lv .= '<p><b>Outcome:</b> '.$last_visit['outcome'].'</p>';
        $lv .= '<p><b>Admitted:</b> '.$last_visit['admitted'].'</p>';
        $lv .= '<p><b>Follow up Plan:</b> '.$last_visit['follow_up'].'</p>';
        $lv .= '<p><b>Other Notes:</b> '.$last_visit['notes'].'</p>';
        $lv .= '</div>';
    } else {
        die(var_dump('no visit'));
    }
}
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>EMR | New Encounter</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Play:400,700" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="css/owl.carousel.css">
    <link rel="stylesheet" href="css/owl.theme.css">
    <link rel="stylesheet" href="css/summernote/summernote.css">
    <link rel="stylesheet" href="css/owl.transitions.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="css/main.css">
    <!-- morrisjs CSS
		============================================ -->
    <link rel="stylesheet" href="css/morrisjs/morris.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="css/metisMenu/metisMenu.min.css">
    <link rel="stylesheet" href="css/metisMenu/metisMenu-vertical.css">
    <!-- calendar CSS
		============================================ -->
    <link rel="stylesheet" href="css/calendar/fullcalendar.min.css">
    <link rel="stylesheet" href="css/calendar/fullcalendar.print.min.css">
    <!-- modals CSS
		============================================ -->
    <link rel="stylesheet" href="css/modals.css">
    <!-- forms CSS
		============================================ -->
    <link rel="stylesheet" href="css/form/all-type-forms.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/select2/select2.min.css">
    <!-- chosen CSS
        ============================================ -->
    <link rel="stylesheet" href="css/chosen/bootstrap-chosen.css">
    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

    <?php include 'includes/sidebar.php' ?>
    <!-- Start Welcome area -->
    <div class="all-content-wrapper">
        <?php include 'includes/navbar.php' ?>
        <!-- Basic Form Start -->
        <div class="basic-form-area mg-tb-15">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="sparkline12-list">
                            <div class="modal-area-button">
                                <a class="Information Information-color mg-b-10" href="#" data-toggle="modal" data-target="#patientinfo">Patient Info</a>
                                <a class="Warning Warning-color mg-b-10" href="#" data-toggle="modal" data-target="#lastvisit">Last Visit</a>
                                <a class="Danger danger-color" href="#" data-toggle="modal" data-target="#DangerModalhdbgcl">Allergies</a>
                                <a class="Alert Alert-color" href="patients.php" data-toggle="" data-target="#AlertModalhdbgcl">Cancel</a>

                                <div id="patientinfo" class="modal modal-adminpro-general fullwidth-popup-InformationproModal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header header-color-modal bg-color-2">
                                                <h4 class="modal-title">Patient Info</h4>
                                                <div class="modal-close-area modal-close-df">
                                                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <img width="100px" height="100px" src="<?php echo 'img/patient/'.$patient['photo'] ?>">
                                                <h2><?php echo $patient['surname'].' '.$patient['other_names'].' ('.$patient['gender'].')'; ?></h2>
                                                <p><b>Date of Birth:</b> <?php echo date('d-m-Y', strtotime($patient['dob'])); ?></p>
                                                <p><b>Phone:</b> <?php echo $patient['phone']; ?></p>
                                                <p><b>Email:</b> <?php echo $patient['email']; ?></p>
                                                <p><b>Address:</b> <?php echo $patient['address']; ?></p>
                                                <p><b>Genotype:</b> <?php echo $patient['genotype']; ?></p>
                                                <p><b>Blood Group:</b> <?php echo $patient['blood_group']; ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <a data-dismiss="modal" href="#">Close</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="lastvisit" class="modal modal-adminpro-general Customwidth-popup-WarningModal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header header-color-modal bg-color-3">
                                                <h4 class="modal-title">Last Visit</h4>
                                                <div class="modal-close-area modal-close-df">
                                                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <h2><?php echo $patient['surname'].' '.$patient['other_names'].' ('.$patient['gender'].')'; ?></h2><hr>
                                                <?php echo $lv; ?>
                                            </div>
                                            <div class="modal-footer">
                                                <a data-dismiss="modal" href="#">Close</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="DangerModalhdbgcl" class="modal modal-adminpro-general FullColor-popup-DangerModal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header header-color-modal bg-color-4">
                                                <h4 class="modal-title">Allergies</h4>
                                                <div class="modal-close-area modal-close-df">
                                                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <h2><?php echo $patient['surname'].' '.$patient['other_names'].' ('.$patient['gender'].')'; ?></h2>
                                                <?php echo getAllergies($id); ?>
                                            </div>
                                            <div class="modal-footer">
                                                <a data-dismiss="modal" href="#">Cancel</a>
                                                <a href="#">Process</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="AlertModalhdbgcl" class="modal modal-adminpro-general FullColor-popup-AlertModal fade" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header header-color-modal bg-color-5">
                                                <h4 class="modal-title">BG Color Header Modal</h4>
                                                <div class="modal-close-area modal-close-df">
                                                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                                                </div>
                                            </div>
                                            <div class="modal-body">
                                                <span class="adminpro-icon adminpro-warning modal-check-pro information-icon-pro"></span>
                                                <h2>Alert!</h2>
                                                <p>The Modal plugin is a dialog box/popup window that is displayed on top of the current page</p>
                                            </div>
                                            <div class="modal-footer">
                                                <a data-dismiss="modal" href="#">Cancel</a>
                                                <a href="#">Process</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sparkline12-hd">
                                <div class="main-sparkline12-hd">
                                    <h1>Document Encounter for <?php echo $patient['surname'].' '.$patient['other_names']; ?></h1>
                                    <p id="bmi"></p>
                                </div>
                            </div><br>
                            <div class="sparkline12-graph">
                                <div class="basic-login-form-ad">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="all-form-element-inner">
                                                <form action="processing.php" method="post"> 
                                                    <input type="hidden" name="id" value="<?php echo $id ?>">
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Weight(kg)</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" required onkeyup="showBMI()" placeholder="88" id="weight" name="vitals[Weight]" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Height</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" required placeholder="180" onkeyup="showBMI()" id="height" name="vitals[height]" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Blood Pressure</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" required placeholder="120/99" name="vitals[bp]" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Temperature(c)</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" required onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" placeholder="37" name="vitals[temperature]" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Presenting Complains</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <div class="form-select-list">
                                                                    <select data-placeholder="Select a presenting complain..." required multiple="" tabindex="-1" class=" chosen-select form-control custom-select-value" name="presenting_complains[]">
                                                                        <?php
                                                                        $pc = getPresentingComplains();
                                                                        foreach ($pc as $key => $value) {
                                                                            echo '<option value="'.$value.'">'.$value.'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">History of Presenting Complains</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <textarea id="summernote" name="history_of_complains"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">State Allergies (if any)</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" placeholder="Enter Allergies" name="allergies" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner" id="dynamic_field">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Prescriptions</label>
                                                            </div>
                                                            <div class="form-row field_wrapper" id="field_wrapper">
                                                               <div class="col-lg-8 col-md-9 col-sm-9 col-xs-12">
                                                                    <input type="text" placeholder="Enter A Prescription" name="prescriptions[]" class="form-control" />
                                                                </div>
                                                                <div class="col-lg-1 col-md-9 col-sm-9 col-xs-12">
                                                                    <td><button type="button" name="add" id="add" class="btn btn-success">+</button></td>
                                                                </div> 
                                                            </div>
                                                        </div>
                                                        <!-- <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro"></label>
                                                            </div>
                                                            <div class="form-row field_wrapper" id="field_wrapper">
                                                               <div class="col-lg-8 col-md-9 col-sm-9 col-xs-12">
                                                                    <input type="text" placeholder="Enter A Prescription" name="prescriptions[]" class="form-control" />
                                                                </div>
                                                                <div class="col-lg-1 col-md-9 col-sm-9 col-xs-12">
                                                                    <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
                                                                </div> 
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Diagnosis</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="text" name="diagnosis" placeholder="Enter Diagnosis" required class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Patient Condition </label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="bt-df-checkbox pull-left">
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="i-checks pull-left">
                                                                                <label><input type="radio" value="Stable" name="condition[]"> <i></i> Stable </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="i-checks pull-left">
                                                                                <label><input type="radio" value="Fair" name="condition[]"> <i></i> Fair </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="i-checks pull-left">
                                                                                <label><input type="radio" value="Critical" name="condition[]"> <i></i> Critical </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">To Be Admitted? </label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
                                                                <div class="bt-df-checkbox pull-left">
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="i-checks pull-left">
                                                                                <label><input type="radio" value="Yes" name="admitted[]"> <i></i> Yes </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                                            <div class="i-checks pull-left">
                                                                                <label><input type="radio" checked value="No" name="admitted[]"> <i></i> No </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Follow Up Date (if applicable)</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <input type="date" name="follow_up" class="form-control" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                                <label class="login2 pull-right pull-right-pro">Notes</label>
                                                            </div>
                                                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                                                <textarea name="notes" class="form-control" /></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group-inner">
                                                        <div class="login-btn-inner">
                                                            <div class="row">
                                                                <div class="col-lg-3"></div>
                                                                <div class="col-lg-9">
                                                                    <div class="login-horizental cancel-wp pull-left">
                                                                        <button class="btn btn-sm btn-primary login-submit-cs" name="submit_encounter" type="submit">Save Information</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Basic Form End-->
        <div class="footer-copyright-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer-copy-right">
                            <!-- <p>Copyright © 2018 <a href="https://colorlib.com/wp/templates/">Colorlib</a> All rights reserved.</p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jquery
		============================================ -->
    <script src="js/vendor/jquery-1.11.3.min.js"></script>
    <!-- bootstrap JS
		============================================ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- wow JS
		============================================ -->
    <script src="js/wow.min.js"></script>
    <!-- price-slider JS
		============================================ -->
    <script src="js/jquery-price-slider.js"></script>
    <!-- meanmenu JS
		============================================ -->
    <script src="js/jquery.meanmenu.js"></script>
    <!-- owl.carousel JS
		============================================ -->
    <script src="js/owl.carousel.min.js"></script>
    <!-- sticky JS
		============================================ -->
    <script src="js/jquery.sticky.js"></script>
    <!-- scrollUp JS
		============================================ -->
    <script src="js/jquery.scrollUp.min.js"></script>
    <!-- mCustomScrollbar JS
		============================================ -->
    <script src="js/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/scrollbar/mCustomScrollbar-active.js"></script>
    <!-- metisMenu JS
		============================================ -->
    <script src="js/metisMenu/metisMenu.min.js"></script>
    <script src="js/metisMenu/metisMenu-active.js"></script>
    <!-- tab JS
		============================================ -->
    <script src="js/tab.js"></script>
    <!-- icheck JS
		============================================ -->
    <script src="js/icheck/icheck.min.js"></script>
    <script src="js/icheck/icheck-active.js"></script>
    <!-- plugins JS
		============================================ -->
    <script src="js/plugins.js"></script>
    <script src="js/chosen/chosen.jquery.js"></script>
    <script src="js/chosen/chosen-active.js"></script>
    <script src="js/select2/select2.full.min.js"></script>
    <script src="js/select2/select2-active.js"></script>
    <script src="js/summernote/summernote.min.js"></script>
    <script src="js/summernote/summernote-active.js"></script>
    <!-- main JS
		============================================ -->
    <script src="js/main.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#summernote').summernote();
        });

        var i = 1;
        $('#add').click(function(){
            i++;
            $('#dynamic_field').append('<div class="row" id="row'+i+'"><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label class="login2 pull-right pull-right-pro"></label></div><div class="form-row field_wrapper" id="field_wrapper"><div class="col-lg-8 col-md-9 col-sm-9 col-xs-12"><input type="text" placeholder="Enter Another Prescription" name="prescriptions[]" class="form-control" /></div><div class="col-lg-1 col-md-9 col-sm-9 col-xs-12"><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></div></div></div>');
        });

        $(document).on('click','.btn_remove', function(){
            var button_id = $(this).attr("id");
            $("#row"+button_id+"").remove();
        });
        
        function showBMI() {
            var height = $('#height').val();
            var weight = $('#weight').val();
            if (weight == '' || height ==''){
                return false;
            } else {
                var height2 = height/100;
                var height3 = height2*height2;
                var bmi = Math.round(weight/height3);
                if (bmi >= 25) {
                    var result = 'overweight';
                } else if (bmi > 18.4 && bmi < 25) {
                    var result = 'normal';
                } else {
                    var result = 'underweight';
                }
                $('#bmi').html('BMI is '+bmi+'. Patient is considered '+result);
            }
            
        }
    </script>
</body>

</html>