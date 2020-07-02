<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista</title>

    <style>
        fieldset{
            border: none;
        }
        #lista-content{
            box-sizing: border-box;
            width: 100%;
            padding: 1rem;
            min-height: 300px;
        }
    </style>
</head>
<body>
    <center>
        <h1>Ajustes Lista</h1>

        <button id="btn" style="margin: 1rem;">Abrir Cliente</button>

        <form id="form" action="" method="POST">
            <fieldset>
                <label for="RANDOM">Modo de Revolver</label>
                <select name="RANDOM" id="RANDOM">
                    <option value="0">SIN RANDOM</option>
                    <option value="1">FISHER YATES</option>
                    <option value="2">SATTOLLO</option>
                    <option value="3">PERMUTACIÓN</option>
                </select>
            </fieldset>

            <fieldset>
                <label for="nronda">Cantidad de Rondas</label>
                <input value="50" type="number" name="nronda" id="nronda">
            </fieldset>

            <fieldset>
                <label for="SEPARAR_GENERO">Separar Generos Iguales</label>
                <select name="SEPARAR_GENERO" id="SEPARAR_GENERO">
                    <option value="1">LIBRE</option>
                    <option value="2">SEPARAR</option>
                </select>
            </fieldset>

            <button>Generar Lista</button>
        </form>
    </center>

    <h3 id="status" style="text-align: center;"></h3>

    <div id="lista-content"></div>

    <script>
        let form = document.getElementById("form"),
            status = document.getElementById("status"),
            listaContent = document.getElementById("lista-content"),
            btn = document.getElementById('btn');

        form.addEventListener('submit', e => {
            e.preventDefault();
            status.innerHTML = "Generando Listas...";

            fetch("php/generar_lista_reproduccion.php", {
                method: 'POST',
                body: new FormData(form)
            }).then(res => res.text())
                .then(data =>{ 
                    listaContent.innerHTML = data;
                    status.innerHTML = "Creando lista de programación...";

                    fetch("php/generar_lista_audios.php")
                        .then(res => res.text())
                        .then(data => status.innerHTML = data)
                        .catch(error => console.error(error));
                })
                .catch(error => console.error(error));
        });

        btn.addEventListener('click', e => {
            //fetch('php/ping.php');
            window.open("cliente.php");
        });
    </script>
</body>
</html>