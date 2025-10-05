# Customer API Documentation

This document provides comprehensive documentation for the Customer Authentication and Profile Management API.

## Base URL
```
http://your-domain.com/api
```

## Authentication
The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header for protected endpoints:
```
Authorization: Bearer {your_token_here}
```

## Response Format
All API responses follow a consistent format:

### Success Response
```json
{
    "success": true,
    "message": "Success message",
    "data": {
        // Response data here
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

---

## Endpoints


### 1. Customer Login
**POST** `/api/customer/login`

Authenticate a customer using email or phone number and receive an access token.

#### Request Body
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**OR**

```json
{
    "phone_number": "+91 9876543210",
    "password": "password123"
}
```

#### Response (200 OK)
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "customer": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone_number": "+91 9876543210",
            "address_line_1": "123 Main Street",
            "address_line_2": "Apt 4B",
            "city": "Mumbai",
            "state": "Maharashtra",
            "postal_code": "400001",
            "country": "India",
            "profile_photo_url": "http://domain.com/images/profile-photos/...",
            "created_at": "2025-01-01T12:00:00.000000Z"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

---

### 2. Get Customer Profile
**GET** `/api/customer/profile`

Get the authenticated customer's profile information.

#### Headers
```
Authorization: Bearer {token}
```

#### Response (200 OK)
```json
{
    "success": true,
    "data": {
        "customer": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "phone_number": "+91 9876543210",
            "address_line_1": "123 Main Street",
            "address_line_2": "Apt 4B",
            "city": "Mumbai",
            "state": "Maharashtra",
            "postal_code": "400001",
            "country": "India",
            "profile_photo_url": "http://domain.com/images/profile-photos/...",
            "created_at": "2025-01-01T12:00:00.000000Z",
            "updated_at": "2025-01-01T12:00:00.000000Z"
        }
    }
}
```

---

### 3. Update Customer Profile
**PUT** `/api/customer/profile`

Update the authenticated customer's profile information.

#### Headers
```
Authorization: Bearer {token}
Content-Type: application/json (or multipart/form-data for file uploads)
```

#### Request Body (Partial updates supported)
```json
{
    "name": "John Smith",
    "phone_number": "+91 9876543211",
    "address_line_1": "456 New Street",
    "city": "Delhi",
    "state": "Delhi",
    "postal_code": "110001"
}
```

#### Response (200 OK)
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "customer": {
            "id": 1,
            "name": "John Smith",
            "email": "john@example.com",
            "phone_number": "+91 9876543211",
            "address_line_1": "456 New Street",
            "address_line_2": "Apt 4B",
            "city": "Delhi",
            "state": "Delhi",
            "postal_code": "110001",
            "country": "India",
            "profile_photo_url": "http://domain.com/images/profile-photos/...",
            "updated_at": "2025-01-01T13:00:00.000000Z"
        }
    }
}
```

---



### 4. Logout
**POST** `/api/customer/logout`

Logout the authenticated customer and revoke the current token.

#### Headers
```
Authorization: Bearer {token}
```

#### Response (200 OK)
```json
{
    "success": true,
    "message": "Logout successful"
}
```

---

### 5. Refresh Token
**POST** `/api/customer/refresh-token`

Generate a new token and revoke the current one.

#### Headers
```
Authorization: Bearer {token}
```

#### Response (200 OK)
```json
{
    "success": true,
    "message": "Token refreshed successfully",
    "data": {
        "token": "2|xyz789...",
        "token_type": "Bearer"
    }
}
```

---

### 6. Health Check
**GET** `/api/health`

Check if the API is running.

#### Response (200 OK)
```json
{
    "success": true,
    "message": "API is healthy",
    "timestamp": "2025-01-01T12:00:00.000000Z",
    "version": "1.0.0"
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created |
| 400 | Bad Request |
| 401 | Unauthenticated |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Server Error |

---

## Validation Rules

### Login
- `email`: Required, valid email (if using email login)
- `phone_number`: Required, string (if using phone login)
- `password`: Required, string
- Note: Either email or phone_number is required, not both

### Profile Update
- All fields are optional (partial updates)
- `name`: String, max 255 characters
- `email`: Valid email, must be unique if changed
- `phone_number`: String, max 20 characters
- `address_line_1`: String, max 255 characters
- `address_line_2`: String, max 255 characters
- `city`: String, max 100 characters
- `state`: String, max 100 characters
- `postal_code`: String, max 20 characters
- `country`: String, max 50 characters
- `profile_photo`: Image file, max 2MB


---

## Example Usage

### cURL Examples

#### Login (Email)
```bash
curl -X POST http://your-domain.com/api/customer/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

#### Login (Phone)
```bash
curl -X POST http://your-domain.com/api/customer/login \
  -H "Content-Type: application/json" \
  -d '{
    "phone_number": "+91 9876543210",
    "password": "password123"
  }'
```

#### Get Profile
```bash
curl -X GET http://your-domain.com/api/customer/profile \
  -H "Authorization: Bearer {token}"
```

#### Update Profile
```bash
curl -X PUT http://your-domain.com/api/customer/profile \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Smith",
    "phone_number": "+91 9876543211"
  }'
```

---

## Image Storage (Local Files)

The API uses local file storage in the `public/images` directory:

### Features:
- **Local Storage**: Images are stored directly on the server in the `public/images/profile-photos/` directory
- **Direct Access**: Images are accessible directly via HTTP without additional processing
- **Simple Management**: Easy to backup, manage, and migrate images
- **No External Dependencies**: No reliance on third-party services
- **Fast Access**: Images served directly from the web server

### File Structure:
- **Upload Directory**: `public/images/profile-photos/`
- **File Naming**: `customer_{random_string}.{extension}`
- **Supported Formats**: jpg, jpeg, png, gif, webp
- **File Size Limit**: Maximum 2MB per image

### Profile Photo URLs:
```json
{
    "profile_photo_url": "http://your-domain.com/images/profile-photos/customer_abc123def456.jpg"
}
```

### File Management:
- **Automatic Cleanup**: Old photos are automatically deleted when updating profile
- **Unique Filenames**: Each upload gets a unique filename to prevent conflicts
- **Direct Serving**: Images are served directly from the web server (no processing needed)

## Security Notes

1. **Token Security**: Store tokens securely and never expose them in client-side code
2. **HTTPS**: Always use HTTPS in production
3. **Rate Limiting**: Implement rate limiting for authentication endpoints
4. **Token Expiration**: Consider implementing token expiration policies
5. **Input Validation**: Always validate and sanitize input data
6. **File Upload**: Validate file types and sizes for profile photos
7. **File Security**: Images are stored in public directory - ensure proper file validation and access controls

---

## Testing

Use tools like Postman, Insomnia, or cURL to test the API endpoints. Make sure to:

1. Test all endpoints with valid data
2. Test validation with invalid data
3. Test authentication with invalid tokens
4. Test file uploads for profile photos
5. Test error scenarios

---

## Support

For API support or questions, please contact the development team.
