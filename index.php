<?php
/**
 * Created by Tim Turner | Ronin Design
 * Created on: 10:22 PM 2/17/2015
 * Contact: info@ronin-design.com
 */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Async Stats via WebSocket (pub-sub)</title>
    <link href="example.css" rel="stylesheet" type="text/css">
    <script language="javascript" type="text/javascript" src="../vendor/jquery/jquery-2.0.2.min.js"></script>
    <script language="javascript" type="text/javascript" src="../vendor/flot/jquery.flot.js"></script>
    <script language="javascript" type="text/javascript" src="http://autobahn.s3.amazonaws.com/js/autobahn.min.js"></script>
    <script type="text/javascript">

        $(function() {
            var sync = false;
            var data = [],
                totalPoints = 60;

            var updateInterval = 1;
            $("#updateInterval").val(updateInterval).change(function () {
                var v = $(this).val();
                if (v && !isNaN(+v)) {
                    updateInterval = +v;
                    if (updateInterval < 1) {
                        updateInterval = 1;
                    } else if (updateInterval > 3600) {
                        updateInterval = 3600;
                    }
                    $(this).val("" + updateInterval);
                }
            });

            $("#updatePoints").val(totalPoints).change(function () {
                var v = $(this).val();
                if (v && !isNaN(+v)) {
                    totalPoints = +v;
                    if (totalPoints < 2) {
                        totalPoints = 2;
                    } else if (totalPoints > 3600) {
                        totalPoints = 3600;
                    }
                    if (data.length > totalPoints)
                    {
                        alert("active, pts: "+totalPoints);
                        data = data.slice(data.length - totalPoints);
                    }

                    $(this).val("" + totalPoints);
                }
            });

            var plot = $.plot("#placeholder", [], {
                series: {
                    shadowSize: 0	// Drawing is faster without shadows
                },
                yaxis: {
                    min: 0,
                    max: 10000
                },
                xaxis: {
                    show: false
                }
            });

            function update(value) {
                if (data.length >= totalPoints)
                data = data.slice(1);
                data.push(value);
                plot.getOptions().yaxes[0].max = Math.max.apply(null, data);
                plot.getOptions().xaxes[0].max = totalPoints-1;

                var res = [];
                for (var i = 0; i < data.length; ++i) {
                    res.push([i, data[i]])
                }
                plot.setData([res]);
                plot.setupGrid();
                plot.draw();
                //setTimeout(update, updateInterval);
            }

            $("button.updatePause").click(function () {
                if( $(this).val() == "true")
                    $(this).val("false");
                else
                    $(this).val("true");
            });

            var timer = 1;
            var conn = new ab.Session('ws://68.171.218.172:8080',
                function() {
                    conn.subscribe('connection_bandwidth_received_last_second_total', function(topic, data) {
                        // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                        console.log(data.value);
                        if ($("button.updatePause").val() == "true") {
                            if (timer < updateInterval) {
                                timer++;
                            }
                            else {
                                update(data.value);
                                timer = 1;
                            }
                        }
                    });
                },
                function() {
                    console.warn('WebSocket connection closed');
                },
                {'skipSubprotocolCheck': true}
            );
        });
    </script>
</head>
<body>

<div id="header">
    <h2>Async Stats via WebSocket (pub-sub)</h2>
</div>

<div id="content">

    <div class="demo-container">
        <div id="placeholder" class="demo-placeholder"></div>
    </div>
    <p>
        Graph of TeamSpeak3 server query property: '<i>connection_bandwidth_received_last_second_total</i>'
    </p>

    <p>Number of updates: <input id="updatePoints" type="text" value="" style="text-align: right; width:5em"> seconds (min: 1 sec | max: 3600 sec)</p>

    <p>Time between updates: <input id="updateInterval" type="text" value="" style="text-align: right; width:5em"> seconds (min: 1 sec | max: 3600 sec)</p>

    <p>
        <button class="updatePause" value="true">Toggle Server Sync</button>
    </p>
</div>
</body>
</html>
