<?php


?>

<!DOCTYPE html>
<html lang='pt-br'>
<head>

    <meta charset='utf-8'/>
    <link rel="stylesheet" href="protip.min.css">
    <link href='fullcalendar/main.css' rel='stylesheet'/>
    <script src='fullcalendar/main.js'></script>
    <script src="users/js/jquery.js"></script>
    <script>


        $(document).ready(function () {
            $.protip();
        });

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                eventDidMount: function (info) {
                    var texto = "";
                    if (info.event.allDay == false) {
                        var date = new Date(info.event.start);
                        texto += date.getHours() + ':' + date.getMinutes() + " - ";
                    }
                    texto += info.event.title;
                    if (info.event.extendedProps.description) {
                        texto += " | " + info.event.extendedProps.description;
                    }
                    info.el.classList.add('protip');
                    info.el.setAttribute('data-pt-title', texto)
                    info.el.setAttribute('data-pt-position', 'top')
                    info.el.setAttribute('data-pt-scheme', 'blue')
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                },
                footerToolbar: {
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
                },
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                height: 'auto',
                selectable: true,
                eventSources: [
                    // your event source
                    {
                        url: <?php echo "'biblioteca/setor-" . $_GET["setor"] . "/calendario.json'"; ?> , // use the `url` property
                        // url: 'events.php', // use the `url` property
                    }
                ]

            });
            calendar.render();
        });

    </script>
</head>
<body>

<div id='calendar'></div>

<script src="protip.min.js"></script>

</body>
</html>
