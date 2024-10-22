<?php
session_start();
require_once 'config.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['username'])) {
  header('Location: index.php');
  exit();
}

// Verificar si la sesión ha expirado
if (time() - $_SESSION['last_action'] > 600) { // 10 minutos
  session_regenerate_id(true);
  $_SESSION = array();
  session_destroy();
  header('Location: index.php');
  exit();
}

// Actualizar la hora de la última acción
$_SESSION['last_action'] = time();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mapa</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/leaflet.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.3/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-control-custom/dist/leaflet-control-custom.js"></script>
    <link rel="stylesheet" href="./style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-custom/dist/leaflet-control-custom.css" />

    <style>
        .leaflet-control {
            background-color: white;
            border-radius: 8px;
        }

        #sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #f8f9fa;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        #sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        #sidebar .sidebar-content {
            padding: 20px;
        }

        .navbar-brand span {
            font-size: 30px;
            cursor: pointer;
        }

        #map {
            height: 100vh; /* Establece la altura del mapa para ocupar todo el espacio disponible */
        }

        .col-md-3 {
            padding: 0; /* Elimina el espacio de relleno en el div col-md-3 */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">
        Plataforma Veng
        <a class="navbar-brand" href="#">
            <img src="./mg/logo_black_veng.png" width="35" height="35" alt="">
        </a>
        <span onclick="openSidebar()">&#9776; Detecciones</span> 
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Salir</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div id="map"></div> 
        </div>
    </div>
</div>

<div id="sidebar">
    <a href="javascript:void(0)" class="closebtn" onclick="closeSidebar()">&times;</a>
    <div class="sidebar-content">
        <div class="card">
            <div class="card-header">
                Fechas
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush" id="deteccionesList">
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    var map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}.png', {
        maxZoom: 19,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
            '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        id: 'mapbox.streets'
    }).addTo(map);

    var osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
    });

    var esriLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
        attribution: 'Tiles &copy; Esri'
    });

    // Capa para los polígonos
    var polygonsLayer = L.layerGroup().addTo(map);

    // Control de capas con el checkbox para mostrar los polígonos
    var overlayMaps = {
        "Mapa": map,
        "OpenStreetMap": osmLayer,
        "Esri": esriLayer,
    };

    L.control.layers(null, overlayMaps).addTo(map);

    // Datos de las detecciones desde get_dates.php
    let divDeteccionesContainer = document.getElementById("deteccionesList");
    fetch('get_dates.php')
        .then(response => response.json())
        .then(data => {
            // Recorre los datos y crea un marcador por cada detección
            data.forEach((detection, index) => {
                var capaDeteccion = L.layerGroup().addTo(map);
                var bounds = []; // Array para almacenar los límites de los polígonos

                fetch('get_polygons.php?id=' + detection.id)
                    .then(response => response.json())
                    .then(data => {
                        // Recorre los datos y crea un polígono por cada uno
                        data.forEach(polygon => {
                            var geojson = L.geoJSON(polygon, {
                                style: function(feature) {
                                    return { fillColor: getRandomColor(), fillOpacity: 0.5, color: 'white', weight: 1 };
                                }
                            });
                            // Agrega un popup al polígono con los datos requeridos
                             //agregar cambios a 'get_polygons.php'
                            var popupContent = `Centro: ${polygon.Centro}<br>
                                                Superficie: ${polygon.Superficie}<br>
                                                ID Mancha: ${polygon.IdMancha}`;
                            geojson.bindPopup(popupContent);
                            
                            capaDeteccion.addLayer(geojson);

                            // Agrega los límites del polígono al array de límites
                            bounds.push(geojson.getBounds());
                        });
                    })
                    .then(() => {
                        // Agrega un evento de clic al checkbox para hacer zoom en los polígonos correspondientes
                        let checkDeteccion = document.getElementById("checkbox_" + detection.id);
                        checkDeteccion.addEventListener("change", function() {
                            if (this.checked) {
                                polygonsLayer.addLayer(capaDeteccion);
                                // Ajusta los límites de los polígonos para hacer zoom
                                var groupBounds = L.latLngBounds(bounds);
                                map.fitBounds(groupBounds);
                            } else {
                                polygonsLayer.removeLayer(capaDeteccion);
                            }
                        });
                    });

                let li = document.createElement("li");
                let label = document.createElement("label");
                let checkDeteccion = document.createElement("input");
                checkDeteccion.setAttribute("type", "checkbox");
                checkDeteccion.classList.add("form-check-input"); // Agrega la clase de Bootstrap para checkboxes
                checkDeteccion.id = "checkbox_" + detection.id; // Asigna un ID único al checkbox
                label.setAttribute("for", "checkbox_" + detection.id); // Establece el atributo "for" correctamente
                let spanID = document.createElement("span");
                spanID.innerText = "ID: " + detection.id;
                let spanFecha = document.createElement("span");
                spanFecha.innerText = " Fecha: " + detection.fecha;
                li.appendChild(checkDeteccion);
                li.appendChild(label);
                li.appendChild(spanID);
                li.appendChild(spanFecha);
                divDeteccionesContainer.appendChild(li);
            });
        });

    function openSidebar() {
        document.getElementById("sidebar").style.width = "250px";
    }

    function closeSidebar() {
        document.getElementById("sidebar").style.width = "0";
    }
    
    //Color hexadecimal aleatorio para los poligonos
    function getRandomColor() {
        var letters = "0123456789ABCDEF";
        var color = "#";
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)]; // se genera un número aleatorio entre 0 y 15
        }
        return color;
    }
</script>
</body>
</html>
