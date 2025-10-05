# Customer API Setup Guide

This guide will help you set up and test the Customer Authentication API for mobile app integration.

## 🚀 Quick Setup

### 1. Environment Configuration
Make sure your `.env` file has the following settings:

```env
# Database
DB_CONNECTION=sqlite
DB_DATABASE=/Users/ajay/Documents/my/iotadmin/database/database.sqlite

# Mail Configuration (for general emails)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Sanctum Configuration
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Start the Server
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## 📱 API Endpoints Overview

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/health` | Health check | No |
| POST | `/api/customer/login` | Login customer | No |
| GET | `/api/customer/profile` | Get profile | Yes |
| PUT | `/api/customer/profile` | Update profile | Yes |
| POST | `/api/customer/logout` | Logout | Yes |
| POST | `/api/customer/refresh-token` | Refresh token | Yes |

## 🧪 Testing the API

### Option 1: Using cURL

#### Test Health Endpoint
```bash
curl -X GET http://localhost:8000/api/health
```

#### Login (Email) Customer
```bash
curl -X POST http://localhost:8000/api/customer/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

#### Login (Phone Number)
```bash
curl -X POST http://localhost:8000/api/customer/login \
  -H "Content-Type: application/json" \
  -d '{
    "phone_number": "+91 9876543210",
    "password": "password123"
  }'
```

#### Get Profile (replace TOKEN with actual token)
```bash
curl -X GET http://localhost:8000/api/customer/profile \
  -H "Authorization: Bearer TOKEN"
```

### Option 2: Using Postman

1. Import the `Customer_API.postman_collection.json` file
2. Set the `base_url` environment variable to `http://localhost:8000`
3. Run the requests in order:
   - Health Check
   - Customer Registration
   - Customer Login (this will auto-save the token)
   - Get Profile
   - Update Profile
   - etc.

### Option 3: Using Browser

Visit: `http://localhost:8000/api/health` to test the basic endpoint.

## 📱 Mobile App Integration

### iOS (Swift)
```swift
// Example API call
func login(email: String, password: String) {
    let url = URL(string: "http://your-domain.com/api/customer/login")!
    var request = URLRequest(url: url)
    request.httpMethod = "POST"
    request.setValue("application/json", forHTTPHeaderField: "Content-Type")
    
    let body = [
        "email": email,
        "password": password
    ]
    
    request.httpBody = try? JSONSerialization.data(withJSONObject: body)
    
    URLSession.shared.dataTask(with: request) { data, response, error in
        // Handle response
    }.resume()
}
```

### Android (Kotlin)
```kotlin
// Example API call
fun login(email: String, password: String) {
    val client = OkHttpClient()
    val requestBody = JSONObject().apply {
        put("email", email)
        put("password", password)
    }.toString()
    
    val request = Request.Builder()
        .url("http://your-domain.com/api/customer/login")
        .post(requestBody.toRequestBody("application/json".toMediaType()))
        .build()
    
    client.newCall(request).enqueue(object : Callback {
        override fun onResponse(call: Call, response: Response) {
            // Handle response
        }
        override fun onFailure(call: Call, e: IOException) {
            // Handle error
        }
    })
}
```

### React Native
```javascript
// Example API call
const login = async (email, password) => {
  try {
    const response = await fetch('http://your-domain.com/api/customer/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        email,
        password,
      }),
    });
    
    const data = await response.json();
    if (data.success) {
      // Store token and navigate
      AsyncStorage.setItem('auth_token', data.data.token);
    }
  } catch (error) {
    console.error('Login error:', error);
  }
};
```

## 🔐 Authentication Flow

1. **Login**: Customer logs in with email or phone number
2. **Token Storage**: Store the Bearer token securely
3. **API Calls**: Include token in Authorization header
4. **Token Refresh**: Use refresh endpoint when needed
5. **Logout**: Revoke token on logout

## 📊 Response Format

All API responses follow this format:

### Success Response
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        // Validation errors (if applicable)
    }
}
```

## 🛡️ Security Best Practices

1. **HTTPS**: Always use HTTPS in production
2. **Token Storage**: Store tokens securely (Keychain on iOS, Keystore on Android)
3. **Token Expiration**: Implement token refresh logic
4. **Input Validation**: Validate all inputs on the client side
5. **Rate Limiting**: Implement rate limiting for authentication endpoints

## 🐛 Troubleshooting

### Common Issues

1. **CORS Errors**: Make sure your domain is in the `SANCTUM_STATEFUL_DOMAINS`
2. **Token Invalid**: Check if the token is correctly formatted and not expired
3. **Validation Errors**: Check the request body format and required fields
4. **File Upload Issues**: Ensure proper multipart/form-data encoding

### Debug Mode

Enable debug mode in `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Check logs in `storage/logs/laravel.log` for detailed error information.

## 📞 Support

For issues or questions:
1. Check the API documentation: `API_DOCUMENTATION.md`
2. Review the Postman collection: `Customer_API.postman_collection.json`
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test with cURL commands provided above

## 🚀 Production Deployment

For production deployment:

1. **Environment**: Set `APP_ENV=production`
2. **Database**: Use MySQL/PostgreSQL instead of SQLite
3. **HTTPS**: Enable SSL certificates
4. **Rate Limiting**: Implement API rate limiting
5. **Monitoring**: Set up error monitoring and logging
6. **Backup**: Regular database backups

---

The Customer API is now ready for mobile app integration! 🎉
