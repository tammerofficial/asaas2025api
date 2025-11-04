@if($data['fullscreen'] === 'yes')
    {{-- Fullscreen Map (same as video background) --}}
    <section class="google-maps-section common-google-maps-section maps-section-fullscreen" 
         data-padding-top="{{$data['padding_top']}}" 
         data-padding-bottom="{{$data['padding_bottom']}}" 
         id="{{$data['section_id']}}">
        <div class="maps-background-wrapper">
            <div class="maps-iframe-background">
                @if(!empty($data['api_key']))
                    <!-- Google Maps with API -->
                    <div class="google-map-container" 
                         id="{{$data['unique_id']}}"
                         style="height: 100vh;"
                         data-lat="{{$data['latitude']}}"
                         data-lng="{{$data['longitude']}}"
                         data-address="{{$data['address']}}"
                         data-zoom="{{$data['zoom_level']}}"
                         data-type="{{$data['map_type']}}"
                         data-marker="{{$data['custom_marker']}}"
                         data-api-key="{{$data['api_key']}}"
                         data-info-window="{{$data['show_info_window']}}">
                    </div>
                    <script src="https://maps.googleapis.com/maps/api/js?key={{$data['api_key']}}&callback=initMap{{$data['unique_id']}}" async defer></script>
                    <script>
                    function initMap{{$data['unique_id']}}() {
                        const mapContainer = document.getElementById('{{$data['unique_id']}}');
                        if (!mapContainer) return;
                        
                        const lat = parseFloat(mapContainer.getAttribute('data-lat')) || null;
                        const lng = parseFloat(mapContainer.getAttribute('data-lng')) || null;
                        const address = mapContainer.getAttribute('data-address') || '';
                        const zoom = parseInt(mapContainer.getAttribute('data-zoom')) || 15;
                        const mapType = mapContainer.getAttribute('data-type') || 'roadmap';
                        const markerUrl = mapContainer.getAttribute('data-marker') || '';
                        const showInfo = mapContainer.getAttribute('data-info-window') === 'yes';
                        
                        let mapCenter;
                        
                        if (lat && lng) {
                            mapCenter = { lat: lat, lng: lng };
                        } else if (address) {
                            const geocoder = new google.maps.Geocoder();
                            geocoder.geocode({ address: address }, function(results, status) {
                                if (status === 'OK' && results[0]) {
                                    mapCenter = results[0].geometry.location;
                                    initMap(mapCenter);
                                } else {
                                    mapContainer.innerHTML = '<div class="map-error">' + '{{__('Unable to find location')}}' + '</div>';
                                }
                            });
                            return;
                        } else {
                            mapContainer.innerHTML = '<div class="map-error">' + '{{__('Please provide address or coordinates')}}' + '</div>';
                            return;
                        }
                        
                        initMap(mapCenter);
                        
                        function initMap(center) {
                            const map = new google.maps.Map(mapContainer, {
                                zoom: zoom,
                                center: center,
                                mapTypeId: mapType
                            });
                            
                            let marker;
                            if (markerUrl) {
                                marker = new google.maps.Marker({
                                    position: center,
                                    map: map,
                                    icon: {
                                        url: markerUrl,
                                        scaledSize: new google.maps.Size(50, 50)
                                    }
                                });
                            } else {
                                marker = new google.maps.Marker({
                                    position: center,
                                    map: map
                                });
                            }
                            
                            if (showInfo && address) {
                                const infoWindow = new google.maps.InfoWindow({
                                    content: '<div class="map-info-window"><strong>' + address + '</strong></div>'
                                });
                                marker.addListener('click', function() {
                                    infoWindow.open(map, marker);
                                });
                                infoWindow.open(map, marker);
                            }
                        }
                    }
                    </script>
                @else
                    <!-- Google Maps Embed (No API Key Required) -->
                    @php
                        $embed_url = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8';
                        if (!empty($data['address'])) {
                            $embed_url .= '&q=' . urlencode($data['address']);
                        } elseif (!empty($data['latitude']) && !empty($data['longitude'])) {
                            $embed_url .= '&q=' . $data['latitude'] . ',' . $data['longitude'];
                        } else {
                            $embed_url .= '&q=Google+Maps';
                        }
                    @endphp
                    <iframe 
                        src="{{$embed_url}}"
                        width="100%" 
                        height="100vh"
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                @endif
            </div>
        </div>
    </section>
@else
    {{-- Regular Map --}}
    <div class="google-maps-section common-google-maps-section" 
         data-padding-top="{{$data['padding_top']}}" 
         data-padding-bottom="{{$data['padding_bottom']}}" 
         id="{{$data['section_id']}}">
        <div class="container">
            <div class="maps-wrapper">
            @if(!empty($data['api_key']))
                <!-- Google Maps with API -->
                <div class="google-map-container" 
                     id="{{$data['unique_id']}}"
                     style="height: {{$data['map_height']}}px;"
                     data-lat="{{$data['latitude']}}"
                     data-lng="{{$data['longitude']}}"
                     data-address="{{$data['address']}}"
                     data-zoom="{{$data['zoom_level']}}"
                     data-type="{{$data['map_type']}}"
                     data-marker="{{$data['custom_marker']}}"
                     data-api-key="{{$data['api_key']}}"
                     data-info-window="{{$data['show_info_window']}}">
                </div>
                <script src="https://maps.googleapis.com/maps/api/js?key={{$data['api_key']}}&callback=initMap{{$data['unique_id']}}" async defer></script>
                <script>
                function initMap{{$data['unique_id']}}() {
                    const mapContainer = document.getElementById('{{$data['unique_id']}}');
                    if (!mapContainer) return;
                    
                    const lat = parseFloat(mapContainer.getAttribute('data-lat')) || null;
                    const lng = parseFloat(mapContainer.getAttribute('data-lng')) || null;
                    const address = mapContainer.getAttribute('data-address') || '';
                    const zoom = parseInt(mapContainer.getAttribute('data-zoom')) || 15;
                    const mapType = mapContainer.getAttribute('data-type') || 'roadmap';
                    const markerUrl = mapContainer.getAttribute('data-marker') || '';
                    const showInfo = mapContainer.getAttribute('data-info-window') === 'yes';
                    
                    let mapCenter;
                    
                    if (lat && lng) {
                        mapCenter = { lat: lat, lng: lng };
                    } else if (address) {
                        const geocoder = new google.maps.Geocoder();
                        geocoder.geocode({ address: address }, function(results, status) {
                            if (status === 'OK' && results[0]) {
                                mapCenter = results[0].geometry.location;
                                initMap(mapCenter);
                            } else {
                                mapContainer.innerHTML = '<div class="map-error">' + '{{__('Unable to find location')}}' + '</div>';
                            }
                        });
                        return;
                    } else {
                        mapContainer.innerHTML = '<div class="map-error">' + '{{__('Please provide address or coordinates')}}' + '</div>';
                        return;
                    }
                    
                    initMap(mapCenter);
                    
                    function initMap(center) {
                        const map = new google.maps.Map(mapContainer, {
                            zoom: zoom,
                            center: center,
                            mapTypeId: mapType
                        });
                        
                        let marker;
                        if (markerUrl) {
                            marker = new google.maps.Marker({
                                position: center,
                                map: map,
                                icon: {
                                    url: markerUrl,
                                    scaledSize: new google.maps.Size(50, 50)
                                }
                            });
                        } else {
                            marker = new google.maps.Marker({
                                position: center,
                                map: map
                            });
                        }
                        
                        if (showInfo && address) {
                            const infoWindow = new google.maps.InfoWindow({
                                content: '<div class="map-info-window"><strong>' + address + '</strong></div>'
                            });
                            marker.addListener('click', function() {
                                infoWindow.open(map, marker);
                            });
                            infoWindow.open(map, marker);
                        }
                    }
                }
                </script>
            @else
                <!-- Google Maps Embed (No API Key Required) -->
                @php
                    $embed_url = 'https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8';
                    if (!empty($data['address'])) {
                        $embed_url .= '&q=' . urlencode($data['address']);
                    } elseif (!empty($data['latitude']) && !empty($data['longitude'])) {
                        $embed_url .= '&q=' . $data['latitude'] . ',' . $data['longitude'];
                    } else {
                        $embed_url .= '&q=Google+Maps';
                    }
                @endphp
                <iframe 
                    src="{{$embed_url}}"
                    width="100%" 
                    height="{{$data['map_height']}}"
                    style="border:0; border-radius: 10px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            @endif
            </div>
        </div>
    </div>
@endif

<style>
/* Regular Map Styles */
.google-maps-section {
    padding: 40px 0;
}

.maps-wrapper {
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.google-map-container {
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
}

/* Fullscreen Map Styles (same as video background) */
.maps-section-fullscreen {
    position: relative;
    width: 100%;
    min-height: 500px;
    overflow: hidden;
    padding: 0 !important;
}

.maps-background-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 500px;
}

.maps-iframe-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.maps-iframe-background iframe,
.maps-iframe-background .google-map-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100vw;
    height: 100vh;
    min-height: 100%;
    min-width: 100vw;
    border: none;
    border-radius: 0;
}

.map-error {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    color: #666;
    font-size: 1rem;
    padding: 20px;
}

.map-info-window {
    padding: 10px;
    max-width: 250px;
}

@media (max-width: 768px) {
    .google-map-container {
        height: 300px !important;
    }
}
</style>

