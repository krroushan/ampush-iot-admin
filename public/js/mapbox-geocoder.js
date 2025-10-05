/**
 * Mapbox Geocoder for Laravel Customer Forms
 * Provides address autocomplete using Mapbox Search API
 */
class MapboxGeocoder {
    constructor(options = {}) {
        this.options = {
            inputSelector: options.inputSelector || '#address_line_1',
            country: options.country || 'IN', // India
            types: options.types || 'country,district,postcode,locality,place,neighborhood,address,poi,street,category,region',
            onAddressSelect: options.onAddressSelect || null,
            useMapboxOnly: true, // Use only Mapbox API
            ...options
        };
        
        this.input = document.querySelector(this.options.inputSelector);
        this.isLoaded = false;
        this.isSearching = false;
        this.searchResults = [];
        this.showResults = false;
        this.sessionToken = null;
        this.abortController = null;
        
        this.init();
    }
    
    async init() {
        console.log('MapboxGeocoder init() called');
        console.log('Input element found:', this.input);
        
        if (!this.input) {
            console.error('Input element not found for selector:', this.options.inputSelector);
            return;
        }
        
        console.log('Setting up event listeners...');
        this.setupEventListeners();
        this.sessionToken = this.generateSessionToken();
        this.isLoaded = true;
        console.log('MapboxGeocoder initialized successfully');
    }
    
    generateSessionToken() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c == 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }
    
    setupEventListeners() {
        console.log('Setting up input event listener...');
        // Input change with debouncing
        let timeoutId;
        this.input.addEventListener('input', (e) => {
            console.log('Input event triggered, value:', e.target.value);
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                if (e.target.value.length > 2) {
                    console.log('Triggering search for:', e.target.value);
                    this.searchAddress(e.target.value);
                } else {
                    console.log('Value too short, clearing results');
                    this.clearResults();
                }
            }, 300);
        });
        
        // Click outside to close results
        document.addEventListener('click', (e) => {
            if (!this.input.contains(e.target) && !this.getResultsContainer()?.contains(e.target)) {
                this.hideResults();
            }
        });
        
        // Escape key to close results
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.hideResults();
            }
        });
    }
    
    async searchAddress(query) {
        if (!this.isLoaded || !this.sessionToken) return;
        
        // Cancel previous request
        if (this.abortController) {
            this.abortController.abort();
        }
        
        this.abortController = new AbortController();
        this.isSearching = true;
        this.showLoading();
        
        console.log('Searching for:', query);
        
        try {
            const mapboxResults = await this.searchMapbox(query);
            if (mapboxResults && mapboxResults.length > 0) {
                this.searchResults = mapboxResults;
                this.showResults = true;
                this.renderResults();
                console.log('Found results:', mapboxResults.length);
            } else {
                console.log('No results found for:', query);
                this.clearResults();
            }
            
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Search error:', error);
                this.clearResults();
            }
        } finally {
            this.isSearching = false;
            this.hideLoading();
        }
    }
    
    
    async searchMapbox(query) {
        try {
            const accessToken = window.AddressConfig?.mapboxAccessToken || '';
            
            if (!accessToken) {
                throw new Error('Mapbox access token not found');
            }
            
            // Build the API URL exactly like your working example
            const url = `https://api.mapbox.com/search/searchbox/v1/suggest?` +
                `q=${encodeURIComponent(query)}&` +
                `access_token=${accessToken}&` +
                `session_token=${this.sessionToken}&` +
                `language=en&` +
                `country=${this.options.country}&` +
                `limit=10&` +
                `types=${this.options.types}`;
            
            console.log('Mapbox API URL:', url);
            
            const response = await fetch(url, {
                signal: this.abortController.signal,
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Mapbox API Error:', response.status, errorText);
                throw new Error(`Mapbox search request failed: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Mapbox API Response:', data);
            
            if (data.suggestions && Array.isArray(data.suggestions)) {
                return data.suggestions.map(suggestion => ({
                    ...suggestion,
                    source: 'mapbox'
                }));
            }
            
            console.log('No suggestions in response');
            return null;
            
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Mapbox search error:', error);
            }
            return null;
        }
    }
    
    async retrieveAddress(mapboxId) {
        if (!this.isLoaded || !this.sessionToken) return null;
        
        try {
            const accessToken = window.AddressConfig?.mapboxAccessToken || '';
            
            const url = `https://api.mapbox.com/search/searchbox/v1/retrieve/${mapboxId}?` +
                `access_token=${accessToken}&` +
                `session_token=${this.sessionToken}`;
            
            console.log('Retrieving address:', url);
            
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Retrieve API Error:', response.status, errorText);
                throw new Error(`Retrieve request failed: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Retrieve API Response:', data);
            
            return data.features?.[0] || null;
            
        } catch (error) {
            console.error('Retrieve error:', error);
            return null;
        }
    }
    
    async handleAddressSelect(suggestion) {
        try {
            this.isSearching = true;
            this.showLoading();
            
            console.log('Selected suggestion:', suggestion);
            
            // Handle Mapbox result
            const addressData = await this.handleMapboxSelection(suggestion);
            
            // Clear input and results
            this.input.value = '';
            this.hideResults();
            this.clearResults();
            
            // Call callback if provided
            if (this.options.onAddressSelect) {
                this.options.onAddressSelect(addressData);
            }
            
            // Auto-fill form fields
            this.fillFormFields(addressData);
            
            console.log('Address data filled:', addressData);
            
        } catch (error) {
            console.error('Address selection error:', error);
            alert('Failed to get address details');
        } finally {
            this.isSearching = false;
            this.hideLoading();
        }
    }
    
    
    async handleMapboxSelection(suggestion) {
        // Retrieve full address details from Mapbox
        const feature = await this.retrieveAddress(suggestion.mapbox_id);
        
        if (feature) {
            const { properties, geometry } = feature;
            const { context } = properties;
            
            console.log('Retrieved feature:', feature);
            console.log('Properties:', properties);
            console.log('Context:', context);
            
            // Extract detailed address information from the retrieve API response
            const addressData = {
                line1: this.extractAddressLine1(properties, context),
                line2: this.extractAddressLine2(properties, context),
                city: context?.place?.name || context?.locality?.name || '',
                state: context?.region?.name || '',
                pincode: context?.postcode?.name || '',
                country: context?.country?.name || 'India',
                coordinates: {
                    type: 'Point',
                    coordinates: geometry.coordinates // [longitude, latitude]
                }
            };
            
            console.log('Extracted detailed address data:', addressData);
            return addressData;
        } else {
            // Fallback to suggestion data (no retrieve needed)
            console.log('Using suggestion data directly');
            return {
                line1: suggestion.name || '',
                line2: suggestion.address || '',
                city: suggestion.context?.place?.name || suggestion.context?.locality?.name || '',
                state: suggestion.context?.region?.name || '',
                pincode: suggestion.context?.postcode?.name || '',
                country: suggestion.context?.country?.name || 'India',
                coordinates: null
            };
        }
    }
    
    extractAddressLine1(properties, context) {
        // Priority order for line1:
        // 1. Full address from context.address.name (most detailed)
        // 2. Properties.address (floor/unit info)
        // 3. Properties.name (business name)
        
        if (context?.address?.name) {
            return context.address.name;
        } else if (properties.address) {
            return properties.address;
        } else {
            return properties.name || '';
        }
    }
    
    extractAddressLine2(properties, context) {
        // For line2, we can use business name if we used address for line1
        if (context?.address?.name && properties.name) {
            return properties.name;
        }
        return '';
    }
    
    fillFormFields(addressData) {
        console.log('Filling form fields with:', addressData);
        
        // Fill address line 1
        const addressLine1 = document.querySelector('input[name="address_line_1"]');
        if (addressLine1 && addressData.line1) {
            addressLine1.value = addressData.line1;
        }
        
        // Fill address line 2
        const addressLine2 = document.querySelector('input[name="address_line_2"]');
        if (addressLine2 && addressData.line2) {
            addressLine2.value = addressData.line2;
        }
        
        // Fill city
        if (addressData.city) {
            // Trigger state change first to load cities
            const stateSelect = document.getElementById('state');
            if (stateSelect && addressData.state) {
                // Find matching state
                const stateOption = Array.from(stateSelect.options).find(
                    option => option.text.toLowerCase().includes(addressData.state.toLowerCase())
                );
                if (stateOption) {
                    stateSelect.value = stateOption.value;
                    stateSelect.dispatchEvent(new Event('change'));
                    
                    // Wait a bit for cities to load, then select city
                    setTimeout(() => {
                        const citySelect = document.getElementById('city');
                        if (citySelect) {
                            const cityOption = Array.from(citySelect.options).find(
                                option => option.text.toLowerCase().includes(addressData.city.toLowerCase())
                            );
                            if (cityOption) {
                                citySelect.value = cityOption.value;
                            }
                        }
                    }, 500);
                }
            }
        }
        
        // Fill postal code
        const postalCode = document.querySelector('input[name="postal_code"]');
        if (postalCode && addressData.pincode) {
            postalCode.value = addressData.pincode;
        }
        
        // Fill country
        const country = document.querySelector('input[name="country"]');
        if (country && addressData.country) {
            country.value = addressData.country;
        }
    }
    
    renderResults() {
        console.log('Rendering results, count:', this.searchResults.length);
        let resultsContainer = this.getResultsContainer();
        
        if (!resultsContainer) {
            console.log('Creating new results container');
            resultsContainer = this.createResultsContainer();
        }
        
        if (this.searchResults.length === 0) {
            console.log('No results to show, hiding');
            this.hideResults();
            return;
        }
        
        console.log('Clearing and populating results container');
        resultsContainer.innerHTML = '';
        
        this.searchResults.forEach((suggestion, index) => {
            
            const button = document.createElement('button');
            button.type = 'button';
            button.style.cssText = `
                width: 100%;
                padding: 12px 16px;
                text-align: left;
                border: none;
                background: white;
                cursor: pointer;
                border-bottom: 1px solid #f3f4f6;
                display: block;
                transition: background-color 0.15s ease;
            `;
            button.addEventListener('mouseenter', () => {
                button.style.backgroundColor = '#f8fafc';
            });
            button.addEventListener('mouseleave', () => {
                button.style.backgroundColor = 'white';
            });
            
            // Use different icons based on feature type
            let iconSvg = '';
            if (suggestion.feature_type === 'poi') {
                iconSvg = `<svg style="width: 16px; height: 16px; color: #3b82f6; margin-top: 2px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>`;
            } else {
                iconSvg = `<svg style="width: 16px; height: 16px; color: #9ca3af; margin-top: 2px; flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>`;
            }
            
            button.innerHTML = `
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    ${iconSvg}
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-size: 14px; font-weight: 600; color: #1f2937; margin-bottom: 4px; line-height: 1.4;">
                            ${suggestion.name}
                        </div>
                        ${suggestion.full_address ? `
                            <div style="font-size: 12px; color: #6b7280; margin-bottom: 2px; line-height: 1.3;">
                                ${suggestion.full_address}
                            </div>
                        ` : ''}
                        ${suggestion.place_formatted ? `
                            <div style="font-size: 11px; color: #9ca3af; margin-bottom: 2px; line-height: 1.3;">
                                ${suggestion.place_formatted}
                            </div>
                        ` : ''}
                        ${suggestion.poi_category && suggestion.poi_category.length > 0 ? `
                            <div style="font-size: 11px; color: #059669; font-weight: 500; margin-bottom: 2px;">
                                ${suggestion.poi_category.join(', ')}
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
            
            button.addEventListener('click', () => {
                this.handleAddressSelect(suggestion);
            });
            
            resultsContainer.appendChild(button);
        });
        
        this.showResults = true;
        
        
        this.displayResults(); // Actually show the dropdown!
    }
    
    createResultsContainer() {
        const container = document.createElement('div');
        
        // Use inline styles for better compatibility
        container.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 9999;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            max-height: 300px;
            overflow-y: auto;
            display: none;
            margin-top: 4px;
        `;
        
        
        const inputContainer = this.input.parentElement;
        if (!inputContainer.classList.contains('relative')) {
            inputContainer.classList.add('relative');
        }
        
        inputContainer.appendChild(container);
        return container;
    }
    
    getResultsContainer() {
        const inputContainer = this.input.parentElement;
        // Look for a div with position: absolute (our results container)
        const containers = inputContainer.querySelectorAll('div');
        for (let container of containers) {
            if (container.style.position === 'absolute' && container.style.zIndex === '9999') {
                return container;
            }
        }
        return null;
    }
    
    displayResults() {
        const container = this.getResultsContainer();
        if (container) {
            container.style.display = 'block';
        }
    }
    
    hideResults() {
        const container = this.getResultsContainer();
        if (container) {
            container.style.display = 'none';
        }
        this.showResults = false;
    }
    
    clearResults() {
        this.searchResults = [];
        this.hideResults();
    }
    
    showLoading() {
        // You can implement a loading indicator here
        console.log('Searching...');
    }
    
    hideLoading() {
        // Hide loading indicator
        console.log('Search complete');
    }
}

// Initialize when DOM is ready
function initializeMapboxGeocoder() {
    console.log('Initializing Mapbox Geocoder...');
    
    const addressInput = document.querySelector('input[name="address_line_1"]');
    const mapboxToken = window.AddressConfig?.mapboxAccessToken;
    
    console.log('Address input found:', addressInput);
    console.log('Mapbox token found:', !!mapboxToken);
    console.log('AddressConfig:', window.AddressConfig);
    
    // Check if we're on a customer form page and Mapbox token is available
    if (addressInput && mapboxToken) {
        console.log('Initializing Mapbox Geocoder for Address Line 1');
        window.mapboxGeocoderInstance = new MapboxGeocoder({
            inputSelector: '#address_line_1',
            country: 'IN',
            onAddressSelect: function(addressData) {
                console.log('Address selected:', addressData);
            }
        });
    } else {
        console.log('Mapbox Geocoder not initialized - missing token or input field');
        console.log('Address input exists:', !!addressInput);
        console.log('Mapbox token exists:', !!mapboxToken);
    }
}

// Try multiple initialization methods
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeMapboxGeocoder);
} else {
    // DOM is already loaded
    initializeMapboxGeocoder();
}

// Also try after a short delay to ensure all scripts are loaded
setTimeout(initializeMapboxGeocoder, 1000);

// Export for use in other scripts
window.MapboxGeocoder = MapboxGeocoder;
