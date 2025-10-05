# IoT Device Management API Documentation

## Overview
This API provides endpoints for managing IoT devices, including registration, retrieval, and updates. Devices can be assigned to customers and tracked for activity monitoring.

**Base URL:** `https://laravel.wizzyweb.com/api`

---

## Authentication
Most device endpoints are public to allow device registration and updates without authentication. However, customer-specific endpoints require authentication via Laravel Sanctum.

**Authentication Header:**
```
Authorization: Bearer {access_token}
```

---

## API Endpoints

### 1. Get All Devices (with Filters)

Retrieve a paginated list of all devices with optional filtering.

**Endpoint:** `GET /devices`

**Query Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `sms_number` | string | No | Filter by SMS number |
| `phone_number` | string | No | Filter by customer phone number |
| `is_active` | boolean | No | Filter by active status (1 or 0) |
| `per_page` | integer | No | Items per page (default: 15) |
| `page` | integer | No | Page number |

**Response:**
```json
{
    "success": true,
    "message": "Devices retrieved successfully",
    "data": [
        {
            "id": 1,
            "device_name": "Water Pump Controller #1",
            "sms_number": "+919876543210",
            "user_id": 5,
            "is_active": true,
            "description": "Main water pump controller",
            "device_model": "IoT Motor Controller v2.0",
            "firmware_version": "1.2.5",
            "last_activity_at": "2025-10-04T10:30:00.000000Z",
            "created_at": "2025-09-01T08:00:00.000000Z",
            "updated_at": "2025-10-04T10:30:00.000000Z",
            "user": {
                "id": 5,
                "name": "John Doe",
                "phone_number": "+919876543210",
                "email": "john@example.com"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 15,
        "total": 42
    }
}
```

**cURL Example:**
```bash
curl -X GET "https://laravel.wizzyweb.com/api/devices?sms_number=+919876543210&is_active=1" \
  -H "Accept: application/json"
```

---

### 2. Get Device by ID or SMS Number

Retrieve detailed information about a specific device by its ID or SMS number.

**Endpoint:** `GET /devices/{identifier}`

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `identifier` | string/integer | Yes | Device ID or SMS number |

**Response:**
```json
{
    "success": true,
    "message": "Device retrieved successfully",
    "data": {
        "id": 1,
        "device_name": "Water Pump Controller #1",
        "sms_number": "+919876543210",
        "user_id": 5,
        "is_active": true,
        "description": "Main water pump controller",
        "device_model": "IoT Motor Controller v2.0",
        "firmware_version": "1.2.5",
        "last_activity_at": "2025-10-04T10:30:00.000000Z",
        "created_at": "2025-09-01T08:00:00.000000Z",
        "updated_at": "2025-10-04T10:30:00.000000Z",
        "user": {
            "id": 5,
            "name": "John Doe",
            "phone_number": "+919876543210",
            "email": "john@example.com"
        },
        "stats": {
            "total_logs": 1523,
            "last_activity": "2025-10-04T10:30:00.000000Z"
        }
    }
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "Device not found"
}
```

**cURL Example:**
```bash
curl -X GET "https://laravel.wizzyweb.com/api/devices/+919876543210" \
  -H "Accept: application/json"
```

---

### 3. Register or Update Device

Register a new device or update an existing device's information. If a device with the same SMS number exists, it will be updated.

**Endpoint:** `POST /devices`

**Request Body:**
```json
{
    "device_name": "Water Pump Controller #1",
    "sms_number": "+919876543210",
    "device_model": "IoT Motor Controller v2.0",
    "firmware_version": "1.2.5",
    "description": "Main water pump controller"
}
```

**Validation Rules:**
| Field | Type | Required | Rules |
|-------|------|----------|-------|
| `device_name` | string | Yes | Max 255 characters |
| `sms_number` | string | Yes | Max 20 characters |
| `device_model` | string | No | Max 255 characters |
| `firmware_version` | string | No | Max 255 characters |
| `description` | string | No | - |

**Response (New Device - 201):**
```json
{
    "success": true,
    "message": "Device registered successfully",
    "data": {
        "id": 1,
        "device_name": "Water Pump Controller #1",
        "sms_number": "+919876543210",
        "user_id": null,
        "is_active": true,
        "description": "Main water pump controller",
        "device_model": "IoT Motor Controller v2.0",
        "firmware_version": "1.2.5",
        "last_activity_at": "2025-10-04T10:30:00.000000Z",
        "created_at": "2025-10-04T10:30:00.000000Z",
        "updated_at": "2025-10-04T10:30:00.000000Z"
    }
}
```

**Response (Updated Device - 200):**
```json
{
    "success": true,
    "message": "Device updated successfully",
    "data": {
        "id": 1,
        "device_name": "Water Pump Controller #1",
        "sms_number": "+919876543210",
        "user_id": 5,
        "is_active": true,
        "description": "Main water pump controller - updated",
        "device_model": "IoT Motor Controller v2.0",
        "firmware_version": "1.3.0",
        "last_activity_at": "2025-10-04T10:35:00.000000Z",
        "created_at": "2025-09-01T08:00:00.000000Z",
        "updated_at": "2025-10-04T10:35:00.000000Z"
    }
}
```

**Error Response (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "device_name": ["The device name field is required."],
        "sms_number": ["The sms number field is required."]
    }
}
```

**cURL Example:**
```bash
curl -X POST "https://laravel.wizzyweb.com/api/devices" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{
    "device_name": "Water Pump Controller #1",
    "sms_number": "+919876543210",
    "device_model": "IoT Motor Controller v2.0",
    "firmware_version": "1.2.5",
    "description": "Main water pump controller"
  }'
```

---

### 4. Update Device Activity

Update the last activity timestamp for a device. This endpoint should be called periodically by devices to indicate they are online and functioning.

**Endpoint:** `POST /devices/{smsNumber}/activity`

**URL Parameters:**
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `smsNumber` | string | Yes | Device SMS number |

**Response:**
```json
{
    "success": true,
    "message": "Device activity updated successfully",
    "data": {
        "id": 1,
        "device_name": "Water Pump Controller #1",
        "sms_number": "+919876543210",
        "last_activity_at": "2025-10-04T10:45:00.000000Z",
        "updated_at": "2025-10-04T10:45:00.000000Z"
    }
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "Device not found"
}
```

**cURL Example:**
```bash
curl -X POST "https://laravel.wizzyweb.com/api/devices/+919876543210/activity" \
  -H "Accept: application/json"
```

---

### 5. Get Device by Phone Number

Retrieve device information by its SMS/phone number.

**Endpoint:** `POST /devices/by-phone`

**Request Body:**
```json
{
    "phone_number": "+919876543210"
}
```

**Validation Rules:**
| Field | Type | Required | Rules |
|-------|------|----------|-------|
| `phone_number` | string | Yes | Max 20 characters |

**Response:**
```json
{
    "success": true,
    "message": "Device retrieved successfully",
    "data": {
        "id": 1,
        "device_name": "Water Pump Controller #1",
        "sms_number": "+919876543210",
        "user_id": 5,
        "is_active": true,
        "description": "Main water pump controller",
        "device_model": "IoT Motor Controller v2.0",
        "firmware_version": "1.2.5",
        "last_activity_at": "2025-10-04T10:30:00.000000Z",
        "created_at": "2025-09-01T08:00:00.000000Z",
        "updated_at": "2025-10-04T10:30:00.000000Z",
        "user": {
            "id": 5,
            "name": "John Doe",
            "phone_number": "+919876543210",
            "email": "john@example.com"
        }
    }
}
```

**Error Response (404):**
```json
{
    "success": false,
    "message": "Device not found"
}
```

**cURL Example:**
```bash
curl -X POST "https://laravel.wizzyweb.com/api/devices/by-phone" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"phone_number": "+919876543210"}'
```

---

### 6. Get My Devices (Authenticated)

Retrieve all devices assigned to the authenticated customer.

**Endpoint:** `GET /customer/devices`

**Headers:**
```
Authorization: Bearer {access_token}
```

**Response:**
```json
{
    "success": true,
    "message": "My devices retrieved successfully",
    "data": [
        {
            "id": 1,
            "device_name": "Water Pump Controller #1",
            "sms_number": "+919876543210",
            "user_id": 5,
            "is_active": true,
            "description": "Main water pump controller",
            "device_model": "IoT Motor Controller v2.0",
            "firmware_version": "1.2.5",
            "last_activity_at": "2025-10-04T10:30:00.000000Z",
            "created_at": "2025-09-01T08:00:00.000000Z",
            "updated_at": "2025-10-04T10:30:00.000000Z"
        },
        {
            "id": 2,
            "device_name": "Backup Pump Controller",
            "sms_number": "+919876543211",
            "user_id": 5,
            "is_active": true,
            "description": "Backup water pump",
            "device_model": "IoT Motor Controller v1.0",
            "firmware_version": "1.1.0",
            "last_activity_at": "2025-10-04T09:15:00.000000Z",
            "created_at": "2025-09-15T10:00:00.000000Z",
            "updated_at": "2025-10-04T09:15:00.000000Z"
        }
    ]
}
```

**Error Response (401):**
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

**cURL Example:**
```bash
curl -X GET "https://laravel.wizzyweb.com/api/customer/devices" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {access_token}"
```

---

## Usage Workflow

### Device Registration Flow
1. Device powers on or is configured
2. Device calls `POST /devices` with its information
3. If device already exists (same SMS number), information is updated
4. If new, device is registered and awaits admin assignment to customer

### Activity Monitoring Flow
1. Device periodically calls `POST /devices/{smsNumber}/activity` (e.g., every 5-10 minutes)
2. Backend updates `last_activity_at` timestamp
3. Admin dashboard can show devices that haven't reported in a while as "offline"

### Customer Device Management Flow
1. Customer logs in via mobile app
2. App calls `GET /customer/devices` to get all assigned devices
3. App can monitor device status and view motor logs for each device

---

## Admin Panel Features

The admin panel provides a comprehensive interface for device management:

### Features:
- **Device List**: View all devices with pagination and filtering
- **Statistics Cards**: Total, Active, Inactive, Assigned, and Unassigned devices
- **Filters**: 
  - Search by device name, SMS number, model, or customer
  - Filter by status (Active/Inactive)
  - Filter by assignment (Assigned/Unassigned)
  - Filter by specific customer
- **Device Details**: View detailed information including:
  - Device information (name, SMS number, model, firmware)
  - Customer information (if assigned)
  - Motor log statistics
  - Recent motor logs
- **CRUD Operations**: Create, Read, Update, and Delete devices
- **Customer Assignment**: Assign/unassign devices to customers
- **Real-time Filtering**: Instant search and filter updates

---

## Integration Examples

### Arduino/ESP32 Device Registration
```cpp
#include <HTTPClient.h>
#include <ArduinoJson.h>

void registerDevice() {
    HTTPClient http;
    http.begin("https://laravel.wizzyweb.com/api/devices");
    http.addHeader("Content-Type", "application/json");
    
    StaticJsonDocument<200> doc;
    doc["device_name"] = "ESP32 Water Pump";
    doc["sms_number"] = "+919876543210";
    doc["device_model"] = "ESP32-WROOM-32";
    doc["firmware_version"] = "1.0.0";
    doc["description"] = "Water pump controller";
    
    String requestBody;
    serializeJson(doc, requestBody);
    
    int httpResponseCode = http.POST(requestBody);
    
    if (httpResponseCode > 0) {
        String response = http.getString();
        Serial.println(response);
    }
    
    http.end();
}

void updateActivity() {
    HTTPClient http;
    http.begin("https://laravel.wizzyweb.com/api/devices/+919876543210/activity");
    http.addHeader("Content-Type", "application/json");
    
    int httpResponseCode = http.POST("{}");
    http.end();
}
```

### Mobile App Integration (React Native/Flutter)
```javascript
// Get customer's devices
async function getMyDevices(token) {
    const response = await fetch('https://laravel.wizzyweb.com/api/customer/devices', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    });
    
    const data = await response.json();
    return data.data;
}

// Get device details
async function getDeviceDetails(smsNumber) {
    const response = await fetch(`https://laravel.wizzyweb.com/api/devices/${smsNumber}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    });
    
    const data = await response.json();
    return data.data;
}
```

---

## Error Codes

| HTTP Code | Description |
|-----------|-------------|
| 200 | Success |
| 201 | Created |
| 401 | Unauthorized (invalid or missing token) |
| 404 | Resource not found |
| 422 | Validation error |
| 500 | Server error |

---

## Best Practices

1. **Device Registration**: Register devices on first boot or configuration
2. **Activity Updates**: Call activity endpoint every 5-10 minutes for online monitoring
3. **Error Handling**: Implement retry logic with exponential backoff for network failures
4. **SMS Number Format**: Use consistent format (e.g., +91XXXXXXXXXX) for Indian numbers
5. **Firmware Version**: Update version string when deploying firmware updates
6. **Security**: Store API endpoints in device configuration, not hardcoded

---

## Support

For API issues or questions, contact: hello@example.com

