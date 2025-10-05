/**
 * Configuration for Address Autocomplete
 * Add your API keys here
 */
window.AddressConfig = {
    // Google Places API Key
    // Get your key from: https://developers.google.com/maps/documentation/places/web-service/get-api-key
    googlePlacesApiKey: 'AIzaSyC5U0I-ke6cvtuhSAKLUZup6ykt-zHUdis', // Your Google Maps API key
    
    // Mapbox Access Token (alternative to Google Places)
    // Get your token from: https://account.mapbox.com/access-tokens/
    mapboxAccessToken: 'pk.eyJ1IjoicnNhbWJzMzU3MCIsImEiOiJjbWY0NDB1ZWswMHN6MmtzYTIzam9rNzU1In0.IWoAqNJXZQCViwBRIWCyyA', // Your Mapbox access token
    
    // Default settings
    defaultCountry: 'IN', // India
    language: 'en',
    region: 'IN'
};

// Example usage:
// window.AddressConfig.googlePlacesApiKey = 'YOUR_GOOGLE_PLACES_API_KEY';
// window.AddressConfig.mapboxAccessToken = 'YOUR_MAPBOX_ACCESS_TOKEN';
