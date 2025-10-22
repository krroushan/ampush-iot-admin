# Phone Number Validation API

## Validate Phone Number Registration

Check if a phone number is registered in the motor control system. This endpoint is useful for mobile app login validation to show appropriate messages to users.

### Endpoint
```
GET /api/validate-phone?phone={phone_number}
```

### Parameters

| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `phone` | string | Yes | Phone number to validate | `+1234567890` |

### Example Requests

```bash
# Validate a registered phone number
GET /api/validate-phone?phone=+915754027372039

# Validate an unregistered phone number
GET /api/validate-phone?phone=+999999999999
```

### Response Format

#### Success Response (Phone Number is Registered)
```json
{
  "success": true,
  "message": "Phone number is registered",
  "isRegistered": true
}
```

#### Error Response (Phone Number Not Registered)
```json
{
  "success": false,
  "message": "This number is not registered with our motor control system. Please choose a registered number.",
  "isRegistered": false
}
```

#### Error Response (Missing Phone Number)
```json
{
  "success": false,
  "message": "Phone number is required",
  "isRegistered": false
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `success` | boolean | Whether the request was successful |
| `message` | string | Human-readable message |
| `isRegistered` | boolean | Whether the phone number is registered |

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Phone number is registered |
| 400 | Missing phone number parameter |
| 404 | Phone number not registered |

### Usage Examples

#### JavaScript/Fetch
```javascript
// Validate phone number
async function validatePhone(phoneNumber) {
    try {
        const response = await fetch(`/api/validate-phone?phone=${encodeURIComponent(phoneNumber)}`);
        const data = await response.json();
        
        if (data.isRegistered) {
            console.log('Phone number is registered:', data.device.name);
            // Allow user to proceed with login
            return true;
        } else {
            console.log('Phone number not registered:', data.message);
            // Show error message to user
            alert(data.message);
            return false;
        }
    } catch (error) {
        console.error('Validation error:', error);
        return false;
    }
}

// Usage
validatePhone('+915754027372039');
```

#### React Native
```javascript
// Validate phone number in React Native
const validatePhoneNumber = async (phoneNumber) => {
    try {
        const response = await fetch(`https://your-domain.com/api/validate-phone?phone=${encodeURIComponent(phoneNumber)}`);
        const data = await response.json();
        
        if (data.isRegistered) {
            // Phone is registered, allow login
            return { isValid: true, device: data.device };
        } else {
            // Phone not registered, show error
            return { 
                isValid: false, 
                error: data.message 
            };
        }
    } catch (error) {
        return { 
            isValid: false, 
            error: 'Network error. Please try again.' 
        };
    }
};

// Usage in component
const handlePhoneSubmit = async () => {
    const result = await validatePhoneNumber(phoneNumber);
    
    if (result.isValid) {
        // Proceed with login
        navigation.navigate('Dashboard');
    } else {
        // Show error message
        Alert.alert('Invalid Phone Number', result.error);
    }
};
```

#### cURL
```bash
# Validate registered phone number
curl -X GET "https://your-domain.com/api/validate-phone?phone=%2B915754027372039"

# Validate unregistered phone number
curl -X GET "https://your-domain.com/api/validate-phone?phone=%2B999999999999"
```

#### PHP
```php
// Validate phone number
function validatePhoneNumber($phoneNumber) {
    $url = "https://your-domain.com/api/validate-phone?phone=" . urlencode($phoneNumber);
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if ($data['isRegistered']) {
        echo "Phone number is registered: " . $data['device']['name'];
        return true;
    } else {
        echo "Error: " . $data['message'];
        return false;
    }
}

// Usage
validatePhoneNumber('+915754027372039');
```

### Mobile App Integration

#### Android (Java)
```java
// Validate phone number in Android
public void validatePhoneNumber(String phoneNumber) {
    String url = "https://your-domain.com/api/validate-phone?phone=" + URLEncoder.encode(phoneNumber, "UTF-8");
    
    // Make HTTP request
    // On success:
    if (response.isRegistered) {
        // Allow user to proceed
        showMessage("Phone number is registered");
    } else {
        // Show error message
        showError(response.message);
    }
}
```

#### iOS (Swift)
```swift
// Validate phone number in iOS
func validatePhoneNumber(_ phoneNumber: String) {
    let urlString = "https://your-domain.com/api/validate-phone?phone=\(phoneNumber.addingPercentEncoding(withAllowedCharacters: .urlQueryAllowed) ?? "")"
    
    guard let url = URL(string: urlString) else { return }
    
    URLSession.shared.dataTask(with: url) { data, response, error in
        if let data = data {
            do {
                let result = try JSONDecoder().decode(ValidationResponse.self, from: data)
                DispatchQueue.main.async {
                    if result.isRegistered {
                        // Allow user to proceed
                        self.showSuccess("Phone number is registered")
                    } else {
                        // Show error message
                        self.showError(result.message)
                    }
                }
            } catch {
                print("Error: \(error)")
            }
        }
    }.resume()
}
```

### Error Handling

The API provides clear error messages for different scenarios:

1. **Missing Phone Number**: Returns 400 with message "Phone number is required"
2. **Unregistered Phone**: Returns 404 with message "This number is not registered with our motor control system. Please choose a registered number."
3. **Network Errors**: Handle network connectivity issues in your app

### Notes

- **Authentication**: This is a public API endpoint, no authentication required
- **Rate Limiting**: Standard rate limiting applies
- **Phone Format**: Accepts phone numbers in any format (with/without country code, with/without +)
- **Response Time**: Typically responds within 100-300ms
- **Caching**: Responses are not cached, always returns fresh data
- **Device Status**: Only returns device info if the device is found in the system

### Related Endpoints

- `POST /api/customer/login` - Customer login with phone number
- `GET /api/customer/devices` - Get devices for logged-in customer
- `GET /api/logs?deviceId={id}` - Get motor logs for specific device
