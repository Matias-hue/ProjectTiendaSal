@extends('layouts.app')
@section('content')
@include('layouts.header')
<!DOCTYPE html>
<html lang="es">
<main>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubicación - Sal La Isabela</title>
    <link rel="stylesheet" href="{{asset('css/styles.css') }}" />
</head> 
<body>
    <div id="map"></div>

    <script>
        function initMap() {
            const ubicacion = {lat: 19.039, lng: -70.123};

            const map = new google.maps.map(document.getElementById("map"), {
                zoom: 15
            });

            const marker = new.google.maps.Marker({
                position: ubicacion,
                map: map,
                title: "Sal La Isabela",
            });
        }
    </script>

    <script async
      src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&callback=initMap">
    </script>
</body>
</main>
</html>
@include('layouts.footer')
@endsection