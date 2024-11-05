<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Car Pool</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- styles -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="css/datetimepicker.css">

    <!-- fav and touch icons -->
    <link rel="shortcut icon" href="http://twitter.github.com/bootstrap/assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="http://twitter.github.com/bootstrap/assets/ico/apple-touch-icon-57-precomposed.png">

    <style>
        /* Set text color to white */
        h2, span {
            color: #fff !important;
        }
        
        body {
            background-image: url('img/new.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        #main-content {
            background-color: rgba(255, 255, 255, 0.9); /* White with some transparency */
            padding: 20px;
            border-radius: 10px; /* Add some rounded corners */
        }
    </style>

</head>

<body onload="load()">
<!-- Part 1: Wrap all page content here -->

<!-- Begin page content -->
<div class="container">
    <?php
    if(isset($_GET['changed']))
        echo("<div class=\"alert alert-info\">\nAccount details changed successfully!\n</div>");
    else if(isset($_GET['nerror']))
        echo("<div class=\"alert alert-error\">\nPlease enter all the details asked before you can continue!\n</div>");
    ?>

    <?php
        include("header.php");
    include('menu.php');
    ?>

    <div class="row-fluid" id="main-content">
        <div class="span2"></div>
        <div class="span5">
            <h2 align="center" style="color: white;"><small>Share your ride</small></h2>
            <hr>
            <br/>
            <form method="post" action="update.php">
                <input type="hidden" name="action" value="shareride" />
                <input type="hidden" id="total" name="totalRequests" value=0 />
                <input type="text" id="From" name="from" data-provide="typeahead" class="typeahead" placeholder="Source" required/><br/>
                <div class="inputs">
                </div>
                <input type="text" id="To" name="to" data-provide="typeahead" class="typeahead" placeholder="Destination" required/><br/>
          
                Start Time of your ride:
                <div class="input-group date" id="uptimepicker">
                    <input type="text" class="form-control" name="uptime" required>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
                <br/>
                Mode of Travel: <br/>
                <label class="radio inline">
                    <input type="radio" name="vehicle" value="car">Car
                </label>
                <label class="radio inline">
                    <input type="radio" name="vehicle" value="bike">Bike
                </label>

                <br/> <br/>
                <div class="input-append">
                    <input type="text" name="time" placeholder="Approx duration of travel" required>
                    <span class="add-on">Hrs</span>
                </div>
                <br/>
                <input type="number" name="number" placeholder="Number of vacancies"> <br/>
                <div class="input-prepend">
                    <span class="add-on">Rs</span>
                    <input class="span10" id="prependedInput" type="number" name="cost" placeholder="Cost per person" required>
                </div><br/><br/>
                <textarea width="500px" rows="3" name="description" placeholder="Any further details which might help people select your ride"></textarea> <br/>
                <input class="btn" type="submit" name="submit" value="Share"/>
            </form>
        </div>
    </div>
</div>
<div id="push"></div>
</div> <!-- /wrap -->


<!-- javascript files -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#uptimepicker').datetimepicker({
            format: 'yyyy-MM-dd HH:mm:ss',
        });
    });
</script>
<script type="text/javascript">
    <?php
    $query = "SELECT city_name from cities";
    $result = mysqli_query($conn, $query);
    echo "var city = new Array();";
    while($row = mysqli_fetch_array($result)){
        echo 'city.push("' . $row["city_name"]. '");';
    }
    echo '$(".typeahead").typeahead({source : city})';
    ?>
</script>
<script type="text/javascript">
    var i = $('.inputs').size();

    $('#add').click(function() {
        var nameName = "dynamic" + i;
        $('<div><input type="text"data-provide="typeahead"   class="field" placeholder="Hop " +"' +i+'" name="'+ nameName+'"   value="" /></div>').fadeIn('slow').appendTo('.inputs');

        $(".field").typeahead({source : city});
        i++;
        var fields= Number($("#total").val())+Number(1);
        $("#total").val(fields);
    });

    $('#remove').click(function() {
        if(i > 1) {
            $('.field:last').remove();
            var fields= Number($("#total").val())-Number(1);
            i--;
        }
    });

    $('#reset').click(function() {
        while(i >= 1) {
            $('.field:last').remove();
            var fields= Number($("#total").val())-Number(1);
            i--;
        }
    });

    function RefreshMap(){
        // You can remove this function since it's related to map functionality
    }

</script>
</body>
</html>
