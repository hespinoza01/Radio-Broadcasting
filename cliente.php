<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cliente Lista</title>
</head>
<body>

    <h2>Cliente Emisora</h2>
    <p>Reprocuciendo: <span id='title'></span></p>
    <p>tiempo transcurrido: <span id='current'></span></p>
    <p>duración: <span id='duration'></span></p>
    <button onclick="player.play()">Reproducir</button>
    <button onclick="player.pause()">Pausar</button>

    <script>
        let PLAYLIST, PLAYLIST_INDEX, SONG_INDEX;
        let player = new Audio();

        window.addEventListener('load', function() {
            let current = document.getElementById('current'),
                duration = document.getElementById('duration'),
                title = document.getElementById('title');

            function setSource(source, _player) {
                return new Promise((resolve, reject) => {
                    _player.src = source; console.log(source);
                    resolve();
                });
            }

            function reproducir(inicio=false){
                fetch('php/reproducir.php', { method: 'POST' })
                .then(res => res.json())
                .then(data => {
                    console.log({
                        playlist_index: data.playlist_index,
                        song_index: data.song_index,
                        song: data.playlist[data.playlist_index],
                        current_time: data.current_time
                    });

                    if(inicio){
                        PLAYLIST = data.playlist;
                        PLAYLIST_INDEX = data.playlist_index;
                        SONG_INDEX = data.song_index;
                    }

                    setSource(
                        `php/song.php?path=${PLAYLIST[SONG_INDEX].filename}`, player
                    ).then(() => {
                        if(inicio) player.currentTime = data.current_time;
                        let playPromise = player.play();
                         
                        if (playPromise !== undefined) {
                            playPromise.then(_ => {
                                player.play();
                            })
                            .catch(error => { player.play(); });
                        }
                    });
                    
                    title.textContent = PLAYLIST[SONG_INDEX].filename;
                    duration.innerHTML = getReadableTime(PLAYLIST[SONG_INDEX].playtime);
                    SONG_INDEX++;

                    if(SONG_INDEX >= PLAYLIST.length){
                        fetch(`php/playlist.php?index=${PLAYLIST_INDEX}`)
                            .then(res => res.json())
                            .then(data => {})
                            .catch(err => console.error(err));
                    }
                    
                    //if(inicio) player.currentTime = data.current_time;
                })
                .catch(error => console.error(error));
            }

            function getReadableTime(duration) {
                duration = parseInt(duration);

                let horas = Math.floor(duration / 3600),
                    minutos = Math.floor((duration - (horas * 3600)) / 60),
                    segundos = duration - (horas * 3600) - (minutos * 60),
                    tiempo = "";

                if (horas < 10) { horas = "0" + horas; }
                if (minutos < 10) { minutos = "0" + minutos; }
                if (segundos < 10) { segundos = "0" + segundos; }

                if (horas === "00") {
                    tiempo = minutos + ":" + segundos;
                } else {
                    tiempo = horas + ":" + minutos + ":" + segundos;
                }
                return tiempo;
            }

            player.addEventListener('ended', () => reproducir());
            player.addEventListener('timeupdate', () => { current.innerHTML = getReadableTime(player.currentTime) });
            reproducir(true);
        });

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