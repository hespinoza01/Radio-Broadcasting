<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cliente Lista</title>
</head>
<body>

    <h2>Cliente Emisora</h2>
    <p>Reprocuciendo: <span id='title'></span></p>
    <audio id='player' src="php/reproducir.php" controls></audio>

    <script>
        function setSource(source, player) {
            return new Promise((resolve, reject) => {
                player.src = source;
                resolve();
            });
        }

        function reproducir(inicio=false){
            fetch('php/reproducir.php', { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                console.log(data);
                player.play();
                title.textContent = data.filename;
                player.currentTime = (inicio) ? data.current_time : data.current_time;
            });
        }

        player.addEventListener('ended', () => reproducir());

        reproducir(true);

    </script>

    <!--<ul id="list"></ul>

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
    </script>-->
    
</body>
</html>