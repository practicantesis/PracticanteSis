<?php
/*
CHANGE LOG

- Creacion de forma
- Validacion si existe el usuario
- aceptar solo letras en usuario
- rellenar campo de mail automaticamente con usuario

- validar nombre, solo letras y espacios
- validar empleado, solo numeros, que no se duplique


*/
/*
session_start();
unset($_SESSION['user']);
session_destroy();
return false;



*/



//header('Access-Control-Allow-Origin: *');

require('php/funciones.php');
include('php/configuraciones.class.php');
//error_reporting(E_ALL);
ConectaSQL('firewall');
//error_reporting(0);
//global $conn;
//$objCONF = new Configuraciones;



session_start();
if(!isset($_SESSION['user'])) {
    if(isset($_POST['user'])) {

/*
        echo "<pre>";
        print_r($_POST);
        print_r($_SESSION);
        echo "xxxx".$_SESSION['user'];
        echo "</pre>";
return false;
*/


        $ldap_server = "ldap.tpitic.com.mx";
        $dn = "cn=feria,dc=transportespitic,dc=com";
        $password = "sistemaspitic";
        $con = ldap_connect('ldap.tpitic.com.mx');
        $bind=ldap_bind($con, $dn, $password);
        $filter = "(&(uid=".$_POST['user'].")(oficina=SIS))";
/*
        if ($_POST['user'] == 'eresendiz') {
            $filter = "(uid=".$_POST['user'].")";
        }
*/
        $srch =ldap_search($con, "ou=People,dc=transportespitic,dc=com",$filter);
        $numero=ldap_count_entries($con, $srch);
        $info = ldap_get_entries($con, $srch);
        // SERVICIOS ATTR = INFRAESTRUCTURA


        if ($numero == 0) {
            $filterb = "(&(uid=".$_POST['user'].")(servicios=INFRAESTRUCTURA))";
            $srchb =ldap_search($con, "ou=People,dc=transportespitic,dc=com",$filterb);
            $numero=ldap_count_entries($con, $srchb);
            $info = ldap_get_entries($con, $srchb);
            //echo $numero;
            //return false;

        }



        if ($numero != 0){
            $logstate = validatePassword($_POST['pass'],$info[0]["userpassword"][0]);
//echo "xx".$_POST['pass']."ssss".$info[0]["userpassword"][0]."ttt".$logstate."xxx";
//die();

	//		echo "XXXXXX $logstate XXXXXXXXXX";
			//return false;


            if($logstate == "SI"){
                $_SESSION['user'] = $info[0]["uid"][0];
            } else {
                    echo "NOT LOGGED USER/GROUP NOT FOUND";
                    sleep(5);
                    header("Location: login.php");
                    die();
            }
        } else {
            echo "NOT LOGGED USER/GROUP NOT FOUND";
            sleep(5);
            header("Location: login.php");
            die();
        }
    } else {
        // user is not logged in, do something like redirect to login.php
        echo "USER NOT GIVEN";
        header("Location: login.php");
        die();
    }
} else {
    // exuste sesion user
}

        //print_r($_POST);
        //print_r($_SESSION);

//$_SERVER['REMOTE_USER'] = "jferia";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Transportes Pitic SA de CV</title>
    <!-- Jquery -->
    <!--
    <script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.png">
    <!-- Pignose Calender -->
    <link href="./plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="./plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="./plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <!--<script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>-->


    <!-- Chartjs -->
    <script src="./plugins/chart.js/Chart.bundle.min.js"></script>
    <!-- Circle progress -->
    <script src="./plugins/circle-progress/circle-progress.min.js"></script>
    <!-- Datamap -->
    <script src="./plugins/d3v3/index.js"></script>
    <script src="./plugins/topojson/topojson.min.js"></script>
    <!--<script src="./plugins/datamaps/datamaps.world.min.js"></script>-->
    <!-- Morrisjs -->
    <script src="./plugins/raphael/raphael.min.js"></script>
    <script src="./plugins/morris/morris.min.js"></script>
    <!-- Pignose Calender -->
    <script src="./plugins/moment/moment.min.js"></script>
    <script src="./plugins/pg-calendar/js/pignose.calendar.min.js"></script>
    <!-- ChartistJS -->
    <script src="./plugins/chartist/js/chartist.min.js"></script>
    <script src="./plugins/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js"></script>


    <script src="./plugins/validation/jquery.validate.min.js"></script>
    <script src="./plugins/validation/jquery.validate-init.js"></script>

    <script src="./js/dashboard/dashboard-1.js"></script>

    <script src="https://unpkg.com/@popperjs/core@2"></script>


    <script src="js/funciones.js"> </script>

    <!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"> </script>
    <link rel="stylesheet" href= "https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


    <script src="bootstrap-4.3.1/js/bootstrap.min.js"> </script>
    <link rel="stylesheet" href= "bootstrap-4.3.1//css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

<!--
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
-->

    <!-- Editable Stylesheet -->
    <!-- <link href="css/bootstrap-editable.css" rel="stylesheet">-->
    <!--<link href="css/jqueryui-editable.css" rel="stylesheet">-->

<!--
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
-->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round|Open+Sans">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<style type="text/css">
    body {
        color: #404E67;
        background: #F5F7FA;
        font-family: 'Open Sans', sans-serif;
        font-size: 1.5rem;
    }

	.cbox{
	  position:relative;
	  text-align: center;
	  display: inline-block;
	}

    .table-wrapper {
        width: 700px;
        margin: 30px auto;
        background: #fff;
        padding: 20px;
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
    }

    .table-title {
        padding-bottom: 10px;
        margin: 0 0 10px;
    }

    .table-title h2 {
        margin: 6px 0 0;
        font-size: 22px;
    }

    .table-title .add-new {
        float: right;
        height: 30px;
        font-weight: bold;
        font-size: 12px;
        text-shadow: none;
        min-width: 100px;
        border-radius: 50px;
        line-height: 13px;
    }

    .table-title .add-new i {
        margin-right: 4px;
    }

    table.table {
        table-layout: fixed;
    }

    table.table tr th, table.table tr td {
        border-color: #e9e9e9;
    }

    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }

    table.table th:last-child {
        width: 100px;
    }

    table.table td a {
        cursor: pointer;
        display: inline-block;
        margin: 0 5px;
        min-width: 24px;
    }

    table.table td a.add {
        color: #27C46B;
    }

    table.table td a.edit {
        color: #FFC107;
    }

    table.table td a.delete {
        color: #E34724;
    }

    table.table td i {
        font-size: 19px;
    }

    table.table td a.add i {
        font-size: 24px;
        margin-right: -1px;
        position: relative;
        top: 3px;
    }

    table.table .form-control {
        height: 32px;
        line-height: 32px;
        box-shadow: none;
        border-radius: 2px;
    }

    table.table .form-control.error {
        border-color: #f50000;
    }

    table.table td .add {
        display: none;
    }

    .mambo{
        display:flex;
        padding-left:25px;
        text-align: center;
        margin: auto;
        align-content: center;
    }

    .link {
        padding: auto;
        text-decoration: none;
        text-align: center;
        margin: auto;
        outline: none;
        border:#27C46B;
        transition: 0.0s;
    }

    .link:hover{
        text-decoration: none;
        color:#464A53;
    }

    .link:visited{
        text-decoration:none;
        color:#464A53;
    }

    .link:link{
        text-decoration: none;
        color:#464A53;
    }

    .buscador{
        display:flex;
        margin: auto;
       text-align: center;
        padding-left: 35px;
        align-content: center;
    }

    .header-left .input-group {
    margin-top: 6px;
    }

    .enlaces-grid{
        display:grid;
        padding-left: 1em;
        grid-template-columns: repeat(5,auto);
        grid-column-gap: 0.5em;
        justify-content: flex-start;
        height:50px;
        align-items: center;
    }

    .enlaces-grid a{
        text-decoration:none;
       color:white;
       font-family: 'Open Sans', sans-serif;
       font-weight: 2px;
       padding: 2px;
    }

    .enlaces-g{
        display:flex;
       align-items: center;
       justify-content: center;
       max-width: 130px;
       min-width: 130px;
       width: 100%;
      background-color: #00935F;
       border-radius:6px;
       height:30px;
    
    }
    .enlaces-g:hover{
        display:flex;
       align-items: center;
       justify-content: center;
       max-width: 130px;
       min-width: 130px;
       width: 100%;
      background-color: #1A73E8;
       border-radius:6px;
       height:30px;
      
    }
.eg a{
    padding-left: 30px;
    padding-right: 30px;
}
    

</style>


</head>


<script type="text/javascript">

$(function() {
   $("#search-box").autocomplete({
        source: "./php/search.php",
    });
});

$(document).ready(function() {
  $("#search-box").keyup(function() {
    //alert( "Handler for .keyup() called." );
    $.ajax({
      type: "POST",
      url: "php/searchuser.php",
      data: 'keyword=' + $(this).val(),
      beforeSend: function() {
        $("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
      },
      success: function(data) {
        $("#suggesstion-box").show();
        $("#suggesstion-box").html(data);
        $("#search-box").css("background", "#FFF");
      }
    });
  });
});

$(document).ready(function() {
  $("#search-box-du").keyup(function() {
    //alert( "Handler for .keyup() called." );
    $.ajax({
      type: "POST",
      url: "php/searchDevuser.php",
      data: 'keyword=' + $(this).val(),
      beforeSend: function() {
        $("#search-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
      },
      success: function(data) {
        $("#suggesstion-box-du").show();
        $("#suggesstion-box-du").html(data);
        $("#search-box-du").css("background", "#FFF");
      }
    });
  });
});


$(function() {
   $("#addlus").autocomplete({
        source: "./php/search.php",
    });
});

$(document).ready(function() {
  $("#addlus").keyup(function() {
    //alert( "Handler for .keyup() called." );
    $.ajax({
      type: "POST",
      url: "php/searchuser.php",
      data: 'keyword=' + $(this).val(),
      beforeSend: function() {
        $("#addlus").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
      },
      success: function(data) {
        $("#2suggesstion-box").show();
        $("#2suggesstion-box").html(data);
        $("#addlus").css("background", "#FFF");
      }
    });
  });
});

$(document).ready(function() {
  $("#gsearch-box").keyup(function() {
    //GrpSrchTip
    var qtip = document.getElementById("GrpSrchTip").value;
    //alert(qtip);
    //alert( "Handler for .keyup() called." );
    $.ajax({
      type: "POST",
      url: "php/searchgroup.php",
      //data: { 'qtip: qtip, keyword=' + $(this).val() },
      data: 'keyword=' + $(this).val()+qtip,
      beforeSend: function() {
        $("#gsearch-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
      },
      success: function(data) {
        $("#gsuggesstion-box").show();
        $("#gsuggesstion-box").html(data);
        $("#gsearch-box").css("background", "#FFF");
      }
    });
  });
});

$(document).ready(function() {
  $("#smbgsearch-box").keyup(function() {
    //GrpSrchTip
    var qtip = document.getElementById("smbGrpSrchTip").value;
    //alert(qtip);
    //alert( "Handler for .keyup() called." );
    $.ajax({
      type: "POST",
      url: "php/smbsearchgroup.php",
      //data: { 'qtip: qtip, keyword=' + $(this).val() },
      data: 'keyword=' + $(this).val()+qtip,
      beforeSend: function() {
        $("#smbgsearch-box").css("background", "#FFF url(LoaderIcon.gif) no-repeat 165px");
      },
      success: function(data) {
        $("#smbgsuggesstion-box").show();
        $("#smbgsuggesstion-box").html(data);
        $("#smbgsearch-box").css("background", "#FFF");
      }
    });
  });
});



 $.fn.editable.defaults.mode = 'inline';
 $(document).ready(function() {
    $('#givenname').editable();
    });


/*
$(document).ready(function() {
    //EDITABLE
    $('#givennameX').editable({
        type:  'text',
                               pk:    1,
                               name:  'username',
                               url:   'post.php',
                               title: 'Enter username'
    });
});
*/

function selectCountry(val) {
  $("#search-box").val(val);
  $("#suggesstion-box").hide();
}
///////////////////////////////

(function($) {
'use strict';
$(function() {
if ($('#editable-form').length) {
$.fn.editable.defaults.mode = 'inline';
$.fn.editableform.buttons =
'<button type="submit" class="btn btn-primary btn-sm editable-submit">' +
    '<i class="fa fa-fw fa-check"></i>' +
    '</button>' +
'<button type="button" class="btn btn-warning btn-sm editable-cancel">' +
    '<i class="fa fa-fw fa-times"></i>' +
    '</button>';
$('#username').editable({
type: 'text',
pk: 1,
name: 'username',
title: 'Enter username'
});

$('#firstname').editable({
validate: function(value) {
if ($.trim(value) === '') return 'This field is required';
}
});

$('#sex').editable({
source: [{
value: 1,
text: 'Male'
},
{
value: 2,
text: 'Female'
}
]
});

$('#status').editable();

$('#group').editable({
showbuttons: false
});

$('#vacation').editable({
datepicker: {
todayBtn: 'linked'
}
});

$('#dob').editable();

$('#event').editable({
placement: 'right',
combodate: {
firstItem: 'name'
}
});

$('#meeting_start').editable({
format: 'yyyy-mm-dd hh:ii',
viewformat: 'dd/mm/yyyy hh:ii',
validate: function(v) {
if (v && v.getDate() === 10) return 'Day cant be 10!';
},
datetimepicker: {
todayBtn: 'linked',
weekStart: 1
}
});

$('#comments').editable({
showbuttons: 'bottom'
});

$('#note').editable();
$('#pencil').on("click", function(e) {
e.stopPropagation();
e.preventDefault();
$('#note').editable('toggle');
});

$('#state').editable({
source: ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Dakota", "North Carolina", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"]
});

$('#state2').editable({
value: 'California',
typeahead: {
name: 'state',
local: ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Dakota", "North Carolina", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"]
}
});

$('#fruits').editable({
pk: 1,
limit: 3,
source: [{
value: 1,
text: 'banana'
},
{
value: 2,
text: 'peach'
},
{
value: 3,
text: 'apple'
},
{
value: 4,
text: 'watermelon'
},
{
value: 5,
text: 'orange'
}
]
});

$('#tags').editable({
inputclass: 'input-large',
select2: {
tags: ['html', 'javascript', 'css', 'ajax'],
tokenSeparators: [",", " "]
}
});

$('#address').editable({
url: '/post',
value: {
city: "Moscow",
street: "Lenina",
building: "12"
},
validate: function(value) {
if (value.city === '') return 'city is required!';
},
display: function(value) {
if (!value) {
$(this).empty();
return;
}
var html = '<b>' + $('<div>').text(value.city).html() + '</b>, ' + $('<div>').text(value.street).html() + ' st., bld. ' + $('<div>').text(value.building).html();
        $(this).html(html);
        }
        });

        $('#user .editable').on('hidden', function(e, reason) {
        if (reason === 'save' || reason === 'nochange') {
        var $next = $(this).closest('tr').next().find('.editable');
        if ($('#autoopen').is(':checked')) {
        setTimeout(function() {
        $next.editable('show');
        }, 300);
        } else {
        $next.focus();
        }
        }
        });
        }
        });
        })(jQuery);


///////////////////////////////


</script>


<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->


    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="index.php" style="height:50px;">
                    <b class="logo-abbr"><img src="images/logo-color.png" alt="Logo pitic"> </b>
                    <span class="logo-compact"><img src="./images/logo-color.png" alt="Logo pitic"></span>
                    <span class="brand-title">
                        <img src="images/Pitic.png" alt="Logo pitic" width="155" height="50">
                    </span>
                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->

        <!--**********************************
            Header start
        ***********************************-->
        <div class="header">
            <div class="header-content clearfix">

                <div class="nav-control">
                    <div class="hamburger">
                        <span class="toggle-icon"><i class="icon-menu"></i></span>
                    </div>
                </div>
                <div class="header-left">
                    <div class="input-group icons">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-transparent border-0 pr-2 pr-sm-3" id="basic-addon1"><i class="mdi mdi-magnify"></i></span>
                        </div>
                        <input type="search" class="form-control" placeholder="Search Dashboard" aria-label="Search Dashboard">
                        <div class="drop-down animated flipInX d-md-none">
                            <form action="#">
                                <input type="text" class="form-control" placeholder="Search">
                            </form>
                        </div>
                    </div>
                </div>
                <!--Menu de enlaces-->
                <div class="enlaces-grid">
                  <div class="enlaces-g ">
                <a href="https://www.tpitic.com.mx/reportes_mambo.php" target="_blank">Reportes mambo</a>
                </div>
                <div class="enlaces-g eg" >
                <a href="http://extensiones.tpitic.com.mx/" target="_blank" >Extensiones</a>
                </div>
                <div class="enlaces-g eg">
                <a href="http://192.168.120.119/cobradores/" target="_blank" >Cobradores</a>
                </div>
                <div class="enlaces-g eg">
                <a href="http://192.168.120.119/seguros/" target="_blank" >Seguros</a>
                </div>
                <div class="enlaces-g eg">
                <a href="http://ti.tpitic.com.mx/INFRAESTRUCTURA-PRODUCCION/Infraestructura/informe/index.php?666U2lzdGVtYXM=897" target="_blank" >Moviles</a>
                </div>  
                </div>
                
                <!--Menu de enlaces-->
                <div class="header-right">
                    <ul class="clearfix">
                        <li class="icons dropdown">
                            <div class="user-img c-pointer position-relative"   data-toggle="dropdown">
                                <span class="activity active"></span>
                                <img src="images/user/1.png" height="40" width="40" alt="">
                            </div>
                            <div class="drop-down dropdown-profile animated fadeIn dropdown-menu">
                                <div class="dropdown-content-body">
                                    <ul>
                                        <li>
                                            <a href="app-profile.html"><i class="icon-user"></i> <span><?php echo $_SESSION['user']; ?> </span></a>
                                        </li>
                                        <li>
                                            <a href="javascript:void()">
                                                <i class="icon-envelope-open"></i> <span>Inbox</span> <div class="badge gradient-3 badge-pill gradient-1">3</div>
                                            </a>
                                        </li>

                                        <hr class="my-2">
                                        <li>
                                            <a href="page-lock.html"><i class="icon-lock"></i> <span>Lock Screen</span></a>
                                        </li>
                                        <li><a href="login.php"><i class="icon-key"></i> <span>Logout</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--**********************************
            Header end ti-comment-alt
        ***********************************-->

        <!--**********************************
            Sidebar start
        ***********************************-->
        <div class="nk-sidebar">
            <div class="nk-nav-scroll">
                <ul class="metismenu" id="menu">

            <li class="nav-label">LDAP</li>
                <li><a href="#" onclick="ShowLDAPG('LDAPGroup')">
				 <i class="icon-grid menu-icon"></i><span class="nav-text">Grupos LDAP </span>
				</a></li>

				<li><a href="#" onclick="ShowLDAP('LDAPUsers')">
				<i class="icon-people menu-icon"></i><span class="nav-text">Usuarios LDAP </span>
				</a></li>

				<li><a href="#" onclick="ShowLDAP('AddLDAPUsers')">
				<i class="icon-user-follow menu-icon"></i><span class="nav-text">Agregar usuario </span>
				</a></li>

			<li class="nav-label">DEVICES</li>
				<li><a href="#" onclick="ShowCells('x')">
				<i class="icon-user-follow menu-icon"></i><span class="nav-text">Celulares</span>
				</a></li>

                <li><a href="#" onclick="ShowLDAP('AddLDAPDevUsers')">
                <i class="icon-user-follow menu-icon"></i><span class="nav-text">Agregar DevUser </span>
                </a></li>

                <li><a href="#" onclick="ShowLDAP('AddLDAPCell')">
                <i class="icon-user-follow menu-icon"></i><span class="nav-text">Agregar Celular </span>
                </a></li>


                <li><a href="#" onclick="ShowLDAP('LDAPDevUsers')">
                <i class="icon-people menu-icon"></i><span class="nav-text">Device Users</span>
                </a></li>

                <li><a href="#" onclick="ShowLDAP('NukeDev')">
                <i class="icon-people menu-icon"></i><span class="nav-text">Eliminar</span>
                </a></li>



            <li class="nav-label">SAMBA</li>
                <li><a href="#" onclick="ShowLDAPG('SMBLDAPGroup')">
                 <i class="icon-grid menu-icon"></i><span class="nav-text">Grupos LDAP Samba</span>
                </a></li>



            <li class="nav-label">OPENVPN</li>
                        <li><a href="#" onclick="ShowOPENVPN()">
				 <i class="icon-shuffle menu-icon"></i><span class="nav-text">Conexiones </span>
				</a></li>



                    <li class="nav-label">Informacion</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()">
                             <i class="icon-location-pin menu-icon"></i><span class="nav-text">IP Adressing </span>

                        </a>
                        <ul aria-expanded="false">
                            <li><a href="#" onclick="ShowTravel()">IPs Travellers</a></li>
                            <!-- <li><a href="./index-2.html">Home 2</a></li> -->
                        </ul>
                    </li>

                    <li class="nav-label">HARDWARE</li>
                    <!-- IMPRESORAS -->
                    <li><a href="#" onclick="Show('print')">
				    <i class="icon-printer menu-icon"></i><span class="nav-text">Impresoras </span>
				    </a>

                    <!-- SENSORES -->
                    <li><a href="#" onclick="ShowHtml('sensor')">
                    <i class="icon-printer menu-icon"></i><span class="nav-text">Sensor de Temperatura</span>
                    </a>

                    </li>


                    <li>
                        <!--<a class="has-arrow" href="javascript:void()" aria-expanded="false">
                            <i class="icon-speedometer menu-icon"></i><span class="nav-text">Switches</span>
                        </a>-->

                    <?php
                        //error_reporting(0);
                        //error_reporting(E_ALL);
                        $list=array();
                        $arreglo=array();
                        $list=GetDeviceListFromLDAP("ou=NetworkSwitches,ou=Devices,dc=transportespitic,dc=com","devicetag");
                        foreach ($list as &$valor) {
                            echo '<ul aria-expanded="false">';
                            //xx//echo "<li><a href=\"#\" onclick=\"Show('sw','$valor')\">$valor</a></li>";
                            $html='<ul aria-expanded="false"><li><a href="#" onclick="Show('."'sw','$valor'".')">'.$valor.'</a></li></ul>';
                            //$valor = preg_match('/^[A-Z]+[0-9]+([A-Z]{3})/', $valor, $matches);
                            $valor = preg_match('/^[A-Z]+[0-9]+([A-Z]{2}[A-Z0-9]{1})/', $valor, $matches);
                            $oficina = $matches[1];
                            $regional=GetRegionaleFromOficina($oficina,'NONE');
                            //array_push($arreglo[$regional], $html."x");
                            $arreglo[$regional].=$html;
                            echo '</ul>';

                        }
                        //error_reporting(E_ALL);
                       //echo "<pre>";
                        //print_r($list);
                        //echo "</pre>";
                    ?>

                    </li>



                    <?php
                        $regios= GetRegionalesFromOficinas();
                        foreach ($regios as &$valor) {
                            echo '<li>';
                            echo '<a class="has-arrow" href="javascript:void()" aria-expanded="false">';
                            echo '<i class="icon-speedometer menu-icon"></i><span class="nav-text">Switches '.$valor.'</span></a>';
                            echo  $arreglo[$valor];
                            echo '</li>';
                            //$valor = $valor * 2;
                        }


                        //echo "<pre>";
                        //print_r($regios);
                        //echo "</pre>";
                    ?>

                    </a>


                    <li class="nav-label">Herramientas</li>
                    <li>
                        <a class="has-arrow" href="javascript:void()">
                             <i class="icon-location-pin menu-icon"></i><span class="nav-text">INVENTARIOS</span>

                        </a>
                        <ul aria-expanded="false">
                            <li><a href="#" onclick="ShowLastTag()">Ultimo Tag</a></li>
                            <!-- <li><a href="./index-2.html">Home 2</a></li> -->
                        </ul>
                    </li>




                </ul>
            </div>
        </div>
        <!--**********************************
            Sidebar end
        ***********************************-->

        <!--**********************************
            Content body start
            style="display:none;
        ***********************************-->
        <div class="content-body">
            <div id="loaderDiv" style="display:none;">
                CONECTANDO...
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <DIV class='topdivclass' ID='TOPDIV'></DIV>
                <DIV ID='MEDDIV'></DIV>
                <div class="container-fluid mt-6">
                    <div id="VPNTable"></div>
                    <div id="NewLDAPUser"></div>
                    <div id="NewLDAPDevUser"></div>
                    <div id="AddLDAPCell"></div>
                    <div id="LDAPUser" style="display:none;">
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <img class="img-fluid" src="images/big/pankaj.jpg" alt="">
                                    <div class="card-body">
                                        <h5 class="card-title">Buscar Usuario</h5>
                                        <div class="frmSearch">
                                            <input type="text" id="search-box" autocomplete="off" placeholder="User" />
                                            <div id="suggesstion-box"></div>
                                        </div>
                                        <p class="card-text">Buscar en LDAP</p>
                                        <p class="card-text"><small class="text-muted"></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div id="LDAPUserTable"><!--xxxxxxxxxx--></div>
                    </div>
                    <div id="LDAPDevUser" style="display:none;">
                        <div class="row">
                            <div class="col-md-6 col-lg-3">
                                <div class="card">
                                    <img class="img-fluid" src="images/big/pankaj.jpg" alt="">
                                    <div class="card-body">
                                        <h5 class="card-title">Buscar Usuario De Devices</h5>
                                        <div class="frmSearch">
                                            <input type="text" id="search-box-du" autocomplete="off" placeholder="DevUser" />
                                            <div id="suggesstion-box-du"></div>
                                        </div>
                                        <p class="card-text">Buscar en LDAP Dev Users</p>
                                        <p class="card-text"><small class="text-muted"></small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div id="LDAPDevUserTable"><!--xxxxxxxxxx--></div>
                    </div>
                    <div id="SmbLDAPGroups" style="display:none;">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <!--<button type="button" class="btn btn-secondary" onclick="SmbLoadGroupQuery('Poruser')">Buscar Grupo SMB Por Usuario</button>-->
                            <button type="button" class="btn btn-secondary" onclick="SmbLoadGroupQuery('Pornombre')">Buscar Grupo SMB Por Nombre</button>
                        </div>
                    </div>
                    <div id="LDAPGroups" style="display:none;">
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-secondary" onclick="LoadGroupQuery('Poruser')">Buscar Grupos Por Usuario</button>
                            <button type="button" class="btn btn-secondary" onclick="LoadGroupQuery('Pornombre')">Buscar Grupo Por Nombre</button>
                        </div>
                    </div>
                    <div id="AddUserLDAP" style="display:none;">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form">
                                        <div class="row">
				                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">
								Email address
							</label>
							<input type="email" class="form-control" id="exampleInputEmail1">
						</div>
						<div class="form-group">

							<label for="exampleInputPassword1">
								Password
							</label>
							<input type="password" class="form-control" id="exampleInputPassword1">
						</div>
						<div class="form-group">

							<label for="exampleInputFile">
								File input
							</label>
							<input type="file" class="form-control-file" id="exampleInputFile">
							<p class="help-block">
								Example block-level help text here.
							</p>
						</div>
						<div class="checkbox">

							<label>
								<input type="checkbox"> Check me out
							</label>
						</div>
						<button type="submit" class="btn btn-primary">
							Submit
						</button>
				</div>
				<div class="col-md-6">
						<div class="form-group">

							<label for="exampleInputEmail1">
								Email address
							</label>
							<input type="email" class="form-control" id="exampleInputEmail1">
						</div>
						<div class="form-group">

							<label for="exampleInputPassword1">
								Password
							</label>
							<input type="password" class="form-control" id="exampleInputPassword1">
						</div>
						<div class="form-group">

							<label for="exampleInputFile">
								File input
							</label>
							<input type="file" class="form-control-file" id="exampleInputFile">
							<p class="help-block">
								Example block-level help text here.
							</p>
						</div>
						<div class="checkbox">

							<label>
								<input type="checkbox"> Check me out
							</label>
						</div>
						<button type="submit" class="btn btn-primary">
							Submit
						</button>

				</div>
			</div>
      </form>
		</div>
	</div>
</div>


						</div>

                        <div id="SrchLDAPGp" style="display:none;">
                            <div class="row">
                                <div class="col-md-6 col-lg-3">
                                    <div class="card">
                                                <img class="img-fluid" src="images/big/group.jpg" alt="">
                                        <div class="card-body">
                                            <h5 class="card-title"><div id="encabezadobusq"></div></h5>
                                            <div class="frmSearch">
                                                    <input type="text" id="gsearch-box" autocomplete="off" placeholder="Grupo" />
                                                    <div id="gsuggesstion-box"></div>
                                                    <input type="hidden" id="GrpSrchTip" name="GrpSrchTip" value="DUNNO">
                                            </div>
                                            <p class="card-text">Buscar en LDAP</p>
                                            <p class="card-text"><small class="text-muted"><!--Que monita card--></small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="SmbSrchLDAPGp" style="display:none;">
                            <div class="row">
                                <div class="col-md-6 col-lg-3">
                                    <div class="card">
                                                <img class="img-fluid" src="images/big/group.jpg" alt="">
                                        <div class="card-body">
                                            <h5 class="card-title"><div id="smbencabezadobusq"></div></h5>
                                            <div class="frmSearch">
                                                    <input type="text" id="smbgsearch-box" autocomplete="off" placeholder="Grupo" />
                                                    <div id="smbgsuggesstion-box"></div>
                                                    <input type="hidden" id="smbGrpSrchTip" name="smbGrpSrchTip" value="DUNNO">
                                            </div>
                                            <p class="card-text">Buscar smb en LDAP</p>
                                            <p class="card-text"><small class="text-muted"><!--Que monita card--></small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

<!--
-->




<DIV ID='BOTTDIV'></DIV>





            <!-- NUKED newldapuser -->




            <!-- #/ container -->
        </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************-->
        <div class="footer">
            
        </div>
        <!--**********************************
            Footer end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->

    <script src="plugins/common/common.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/gleek.js"></script>
    <script src="js/styleSwitcher.js"></script>
    <!-- <script src="js/bootstrap-editable.js"></script>-->
    <script src="js/jqueryui-editable.js"></script>
    <script src="./plugins/tables/js/jquery.dataTables.min.js"></script>
    <script src="./plugins/tables/js/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="./plugins/tables/js/datatable-init/datatable-basic.min.js"></script>


</body>

</html>
