<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cliente Lista</title>
</head>
<body>

    <h2>Historial de conexión</h2>
    <ul id="list"></ul>

    <script>
        const evtSource = new EventSource("php/ping.php");

        evtSource.onmessage = function(evt) {
          const newElement = document.createElement("li");
          const eventList = document.getElementById("list");

          newElement.innerHTML = evt.data;
          eventList.appendChild(newElement);
        }

        /*evtSource.addEventListener("ping", function(event) {
            /*const newElement = document.createElement("li");
            const time = JSON.parse(event.data).time;
            newElement.innerHTML = "ping at " + time;
            eventList.appendChild(newElement);
            console.log(event);
        });*/
    </script>
    
</body>
</html>