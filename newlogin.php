
<!DOCTYPE html>
<html class="h-100" lang="en">

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

</style>


</head>

<body class="h-100">
    
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

    



    <div class="login-form-bg h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100">
                <div class="col-xl-6">
                    <div class="form-input-content">
                        <div class="card login-form mb-0">
                            <div class="card-body pt-5">
                                <a class="text-center" href="login.php"> <h4>PORTAL DE INFRAESTRUCTURA</h4></a>
        
                                <form class="mt-5 mb-5 login-input" method="post" action="index.php">
                                    <div class="form-group">
                                        <input type="text" name="user" id="user" class="form-control" placeholder="Usuario">
                                        <div id="thapass"></div>
                                        <input type="hidden" name="src" id="src" value="rp">
                                    </div>
<!--                                    
                                    <div class="form-group">
                                        <input type="password" name="pass" id="pass" class="form-control" placeholder="Contraseña">
                                         <center><img src="icons/botones/eye.png" alt="ojo" id="ojo"></center>
                                    </div>

                                    <button type="submit" class="btn login-form__btn submit w-100">Ingresar</button><br> <br>
-->                                    
                                    <button type="button" onclick="respa()" class="btn login-form__btn submit w-100">Reset Pass</button>
                                </form>
                                <!--<p class="mt-5 login-form__footer">Dont have account? <a href="page-register.html" class="text-primary">Sign Up</a> now</p>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--prueba de git fetch en servidor con putty-->
    <script>
    var boton=document.getElementById('ojo');
        var input=document.getElementById('pass');
        boton.addEventListener('click',mostrarClave);
        
        function mostrarClave(){
            if(input.type=="password"){
                input.type="text";
                boton.src="icons/botones/invisible.png";
                setTimeout("ocultar()",5000);
            }else{
                input.type="password";
                boton.src="icons/botones/eye.png";
            }
        }
        function ocultar(){
            input.type="password";
            boton.src="icons/botones/eye.png";
        }
    </script>

    

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





