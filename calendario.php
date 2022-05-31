<?php
/*
UserSpice 5
An Open Source PHP User Management System
by the UserSpice Team at http://UserSpice.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
require_once 'users/init.php';
require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
if (!securePage($_SERVER['PHP_SELF'])) {
    die();
}

?>

<!DOCTYPE html>
<html lang='pt-br'>
<head>

    <meta charset='utf-8' />
    <link rel="stylesheet" href="protip.min.css">
    <link href='fullcalendar/main.css' rel='stylesheet' />
    <script src='fullcalendar/main.js'></script>

    <script>

        $(document).ready(function(){
            $.protip();
        });

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                customButtons: {
                    myCustomButton: {
                        text: 'Adicionar',
                        click: function() {
                            window.location = ('calendario-adicionar.php')
                        }
                    }
                },
                eventDidMount: function(info) {
                    var texto = "";
                    if(info.event.allDay == false){
                        var date = new Date(info.event.start);
                        texto += date.getHours() + ':' + date.getMinutes() + " - ";
                    }
                    texto += info.event.title;
                    if( info.event.extendedProps.description ) {
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
                    right: 'myCustomButton',
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
                            url: 'calendario-listar.php', // use the `url` property
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

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
