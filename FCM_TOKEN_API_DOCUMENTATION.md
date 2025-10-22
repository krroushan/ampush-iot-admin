# FCM Token API Documentation

## Overview
This document describes the FCM (Firebase Cloud Messaging) Token API endpoint for mobile app integration.

---

## Base URL
```
http://your-domain.com/api
```

---

## Authentication
All FCM token endpoints require **Bearer Token** authentication using Laravel Sanctum.

**Header:**
```
Authorization: Bearer {token}
```

---

## API Endpoint

### **Update FCM Token**

Updates the Firebase Cloud Messaging token for the authenticated customer user. This token is used to send push notifications to the user's mobile device.

**Endpoint:** `POST /customer/fcm-token`

**Authentication:** Required (Customer role)

**Middleware:** `auth:sanctum`, `customer`

**Request Headers:**
```
Content-Type: application/json
Authorization: Bearer {customer_token}
```

**Request Body:**
```json
{
    "fcm_token": "string (required, max 255 characters)"
}
```

**Example Request:**
```bash
curl -X POST http://127.0.0.1:8000/api/customer/fcm-token \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 2|saUFcGgErem3DeiKWPlCNqWy1pCmbOoUk3L3BJSMdc305f3b" \
  -d '{
    "fcm_token": "dxrZ1miXQISwZi9aGVs4CV:APA91bH..."
  }'
```

---

## Response Examples

### **Success Response (200 OK)**

```json
{
    "success": true,
    "message": "FCM token updated successfully",
    "data": {
        "user_id": 5,
        "fcm_token": "dxrZ1miXQISwZi9aGVs4CV:APA91bH..."
    }
}
```

### **Validation Error (422 Unprocessable Entity)**

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "fcm_token": [
            "The fcm token field is required."
        ]
    }
}
```

### **Unauthorized Error (401 Unauthorized)**

```json
{
    "success": false,
    "message": "Unauthorized"
}
```

### **Access Denied (403 Forbidden)**

```json
{
    "success": false,
    "message": "Access denied. Customer role required."
}
```

### **Server Error (500 Internal Server Error)**

```json
{
    "success": false,
    "message": "Failed to update FCM token",
    "error": "Error details..."
}
```

---

## Integration Flow

### **Mobile App Integration Steps:**

1. **User Login**
   ```
   POST /api/customer/login
   ```
   - Returns authentication token

2. **Obtain FCM Token**
   - Use Firebase SDK in mobile app
   - Get device FCM token from Firebase

3. **Update FCM Token**
   ```
   POST /api/customer/fcm-token
   ```
   - Send FCM token with authentication header
   - Token is stored in `users.fcm_token` column

4. **Receive Notifications**
   - Admin sends notification from admin panel
   - Firebase delivers notification to device using stored FCM token

---

## Storage Details

**Database Table:** `users`

**Column:** `fcm_token` (VARCHAR, nullable)

**Location in Table:**
```
users
├── id
├── name
├── email
├── phone_number
├── fcm_token  ← Stored here
├── role
└── ... other columns
```

---

## Android/Kotlin Example

```kotlin
// 1. Get FCM token from Firebase
FirebaseMessaging.getInstance().token.addOnCompleteListener { task ->
    if (task.isSuccessful) {
        val fcmToken = task.result
        
        // 2. Update FCM token via API
        updateFcmToken(fcmToken)
    }
}

// 3. API Call
fun updateFcmToken(fcmToken: String) {
    val request = JSONObject().apply {
        put("fcm_token", fcmToken)
    }
    
    val url = "http://your-domain.com/api/customer/fcm-token"
    val token = "your-bearer-token" // From login response
    
    val httpRequest = object : JsonObjectRequest(
        Method.POST, url, request,
        { response ->
            Log.d("FCM", "Token updated: ${response.toString()}")
        },
        { error ->
            Log.e("FCM", "Error: ${error.message}")
        }
    ) {
        override fun getHeaders(): Map<String, String> {
            return hashMapOf(
                "Authorization" to "Bearer $token",
                "Content-Type" to "application/json"
            )
        }
    }
    
    requestQueue.add(httpRequest)
}
```

---

## iOS/Swift Example

```swift
// 1. Get FCM token from Firebase
Messaging.messaging().token { token, error in
    if let error = error {
        print("Error fetching FCM token: \(error)")
    } else if let token = token {
        print("FCM token: \(token)")
        
        // 2. Update FCM token via API
        updateFcmToken(fcmToken: token)
    }
}

// 3. API Call
func updateFcmToken(fcmToken: String) {
    let url = URL(string: "http://your-domain.com/api/customer/fcm-token")!
    let token = "your-bearer-token" // From login response
    
    var request = URLRequest(url: url)
    request.httpMethod = "POST"
    request.setValue("application/json", forHTTPHeaderField: "Content-Type")
    request.setValue("Bearer \(token)", forHTTPHeaderField: "Authorization")
    
    let body: [String: String] = ["fcm_token": fcmToken]
    request.httpBody = try? JSONSerialization.data(withJSONObject: body)
    
    URLSession.shared.dataTask(with: request) { data, response, error in
        if let data = data {
            let json = try? JSONSerialization.jsonObject(with: data)
            print("Response: \(json ?? [:])")
        }
    }.resume()
}
```

---

## Testing

### **Using cURL:**

```bash
# Step 1: Login as customer
curl -X POST http://127.0.0.1:8000/api/customer/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "customer@test.com",
    "password": "password"
  }'

# Response will include token:
# "token": "2|saUFcGgErem3DeiKWPlCNqWy1pCmbOoUk3L3BJSMdc305f3b"

# Step 2: Update FCM token
curl -X POST http://127.0.0.1:8000/api/customer/fcm-token \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer 2|saUFcGgErem3DeiKWPlCNqWy1pCmbOoUk3L3BJSMdc305f3b" \
  -d '{
    "fcm_token": "test-fcm-token-12345"
  }'
```

### **Using Postman:**

1. **Create New Request**
   - Method: `POST`
   - URL: `http://127.0.0.1:8000/api/customer/fcm-token`

2. **Headers**
   - `Content-Type`: `application/json`
   - `Authorization`: `Bearer {your_token}`

3. **Body (raw JSON)**
   ```json
   {
       "fcm_token": "your-fcm-token-here"
   }
   ```

4. **Click Send**

---

## Related API Endpoints

### **Customer Login**
```
POST /api/customer/login
```
Returns authentication token required for FCM token update

### **Customer Profile**
```
GET /api/customer/profile
```
Includes FCM token in response

### **Customer Devices**
```
GET /api/customer/devices
```
Lists devices assigned to customer

---

## Security Notes

1. **Role-Based Access:** Only users with `role = 'customer'` can update FCM tokens
2. **Token Validation:** FCM token is validated (required, string, max 255 characters)
3. **Authentication Required:** Must provide valid Bearer token
4. **HTTPS Recommended:** Use HTTPS in production for secure token transmission
5. **Token Privacy:** FCM tokens are stored securely and not exposed in public APIs

---

## Troubleshooting

### **"Access denied. Customer role required."**
- You're using an admin token instead of customer token
- Login with a customer account to get the correct token

### **"Unauthorized"**
- Token is missing or invalid
- Login again to get a fresh token

### **"The fcm token field is required."**
- Request body is missing `fcm_token` field
- Ensure you're sending valid JSON with `fcm_token` key

### **FCM token not updating**
- Check that `fcm_token` is in the User model's `$fillable` array
- Verify the migration has been run: `php artisan migrate`
- Check database column exists: `php artisan tinker` → `Schema::hasColumn('users', 'fcm_token')`

---

## Version History

**v1.0.0** - October 5, 2025
- Initial release
- FCM token storage in users table
- Update FCM token endpoint
- Customer authentication integration

---

## Support

For API support or issues, please contact your backend administrator.

**Test Credentials:**
- Email: `customer@test.com`
- Password: `password`
- Role: `customer`

