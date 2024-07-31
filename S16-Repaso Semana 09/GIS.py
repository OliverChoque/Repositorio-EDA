import folium
import requests

# Crear un mapa centrado en Nueva York
m = folium.Map(location=[40.7128, -74.0060], zoom_start=12)

# Descargar y cargar los datos GeoJSON de estaciones de metro
subway_url = 'https://data.cityofnewyork.us/resource/kk4q-3rt2.geojson'
subway_data = requests.get(subway_url).json()
subway_layer = folium.GeoJson(subway_data, name='Estaciones de Metro')

# Descargar y cargar los datos GeoJSON de parques
parks_url = 'https://data.cityofnewyork.us/resource/p7jc-c8ak.geojson'
parks_data = requests.get(parks_url).json()
parks_layer = folium.GeoJson(parks_data, name='Parques')

# Añadir las capas al mapa
subway_layer.add_to(m)
parks_layer.add_to(m)

# Añadir control de capas
folium.LayerControl().add_to(m)

# Guardar el mapa en un archivo HTML
m.save('nyc_map_with_layers.html')

print("El mapa ha sido generado y guardado como 'nyc_map_with_layers.html'")
