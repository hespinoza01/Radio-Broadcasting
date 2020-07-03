<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cliente Lista</title>
</head>
<body>

    <h2>Cliente Emisora</h2>
    <p>Reprocuciendo: <span id='title'></span></p>
    <audio id='player' src="" controls autoplay muted></audio>

    <script>
        let PLAYLIST, PLAYLIST_INDEX, SONG_INDEX;

        window.addEventListener('load', function() {
            let player = document.getElementById('player'),
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
                        let playPromise = player.play();
                         
                        if (playPromise !== undefined) {
                            playPromise.then(_ => {
                                player.play();
                            })
                            .catch(error => { player.play(); });
                        }
                    });
                    
                    title.textContent = PLAYLIST[SONG_INDEX].filename;
                    SONG_INDEX++;

                    if(SONG_INDEX >= PLAYLIST.length){
                        fetch(`php/playlist.php?index=${PLAYLIST_INDEX}`)
                            .then(res => res.json())
                            .then(data => {})
                            .catch(err => console.error(err));
                    }
                    
                    if(inicio) player.currentTime = data.current_time;
                })
                .catch(error => console.error(error));
            }

            player.addEventListener('ended', () => reproducir());
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
