function initMap() {
    const mapElement = document.getElementById("map");
    if (!mapElement) return;

    const ubicacion = { lat: -26.82129485543429, lng: -55.02591580168832 };

    const map = new google.maps.Map(mapElement, {
        zoom: 15,
        center: ubicacion,
    });

    new google.maps.Marker({
        position: ubicacion,
        map: map,
        title: "Sal La Isabela",
    });
}

document.addEventListener('DOMContentLoaded', function () {
    console.log('JavaScript de Ubicación cargado');
});