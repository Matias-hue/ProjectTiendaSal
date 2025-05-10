@extends('layouts.app')

@section('content')
    @include('layouts.header')
    <div class="py-4 px-8 text-sm text-gray-500">Ubicación</div>
    <main class="px-8">
        <h2 class="text-3xl font-bold mb-6">Nuestra Ubicación</h2>
        <div class="map-container">
            <iframe
                width="100%"
                height="400"
                frameborder="0" style="border:0"
                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyByIuA5KDKP7TXNbBt5Ra_aaxWPSdNdDoo&q=-26.82129485543429,-55.02591580168832&zoom=15"
                allowfullscreen>
            </iframe>
        </div>
    </main>
    @include('layouts.footer')
@endsection