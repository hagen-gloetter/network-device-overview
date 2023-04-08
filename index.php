<?php
    header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
    header("Pragma: no-cache"); // HTTP 1.0.
    header("Expires: 0"); // Proxies.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Network devices</title>
    <meta name="description" content="Network Device Monitor">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <meta name="author" content="LayoutIt!">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/jquery.min.js"></script>
    <!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
    <style>
        * {
            box-sizing: border-box;
        }

        h2 {
            padding: 40px 20px 12px 40px;
        }

        #myInput,
        .eingabe {
            background-image: url('css/searchicon.png');
            background-position: 10px 10px;
            background-repeat: no-repeat;
            width: 100%;
            font-size: 1rem;
            padding: 5px 20px 0px 40px;
            border: 1px solid #ddd;
            margin-bottom: 12px;
        }

        #myTable {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
            font-size: 1.75rem;
        }

        #myTable th,
        #myTable td {
            text-align: left;
            padding: 5px;
        }

        #myTable th {
            font-size: 1rem;
        }

        #myTable tr {
            border-bottom: 1px solid #ddd;
        }

        #myTable tr.header,
        #myTable tr:hover {
            background-color: #f1f1f1;
        }
    </style>
    <!-- Spinner -->
    <style>
        /* Center the loader */
        #loader {
            position: fixed;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            display: none;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Add animation to "page content" */
        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0px;
                opacity: 1
            }
        }

        @keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }

            to {
                bottom: 0;
                opacity: 1
            }
        }

        #myDiv {
            display: block;
            text-align: center;
        }
    </style>
</head>

<body style="margin:0">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-1">
                &nbsp;
            </div>
            <div class="col-md-10">
                <!--			<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name"> -->
                <div id="myDiv">
                    <h2>My Network</h2>
                </div>
                <div id="loader"></div>
                <table class="table table-hover table-sm" id="myTable">
                    <thead>
                        <tr class="header">
                            <th>
                            </th>
                            <th>
                                Hostname
                            </th>
                            <th>
                                IP-Adresse
                            </th>
                            <th style="vertical-align: top">
                                <div>Hostinfo</div>
                            </th>
                        </tr>
                        <tr class="header">
                            <th style="vertical-align: top">
                                <form>
                                    <button type="button" id="button" onClck="return false">reload</button>
                                </form>
                            </th>
                            <th>
                                <input type="text" id="myInput1" onkeyup="myFunction(1,true)" placeholder="Search for Hostname.." title="Type in a Hostname" class="eingabe">
                            </th>
                            <th>
                                <input type="text" id="myInput2" onkeyup="myFunction(2,true)" placeholder="Search for IP.." title="Type in a IP" class="eingabe">
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="cont">
                        <tr>
                            <td colspan="4">READY.</td>
                        </tr>
                    </tbody>
                </table>

                <script>
                    function doLoad() {
                        document.getElementById("loader").style.display = "block";
                        $.ajax({
                            url: "index_nmap.php",
                            success: function(result) {
                                $("#cont").html(result);
                                myFunction(1, false);
                                myFunction(2, false);
                                document.getElementById("loader").style.display = "none";
                            }
                        });
                    }
                    $(document).ready(function() {
                        $("#cont").html("<tr><td colspan=\"4\">LOADING...</td></tr>");
                        $("button").click(function() {
                            doLoad();
                        });
                        doLoad();
                    });
                </script>

            </div><!-- 4 -->

            <div class="col-md-1">
                &nbsp;
            </div>


        </div> <!-- row -->
    </div>
    <script>
        // https://www.w3schools.com/howto/howto_js_filter_table.asp
        function myFunction(fnr, force) {
            var filter = document.getElementById(fnr == 1 ? "myInput1" : "myInput2").value.toUpperCase();
            if (filter || force) {
                var tr = document.getElementById("myTable").getElementsByTagName("tr");
                for (var i = 0; i < tr.length; i++) {
                    var td = tr[i].getElementsByTagName("td")[fnr];
                    if (td)
                        tr[i].style.display = td.innerText.toUpperCase().indexOf(filter) > -1 ? "" : "none";
                }
            }
        }
    </script>
</body>

</html>