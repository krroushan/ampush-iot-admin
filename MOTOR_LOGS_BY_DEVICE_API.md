# Motor Logs by Device ID - API Documentation

## Get Motor Logs by Device ID

Retrieve motor logs filtered by specific device ID with optional filtering and pagination.

### Endpoint
```
GET /api/logs?deviceId={device_id}
```

### Parameters

| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `deviceId` | integer | Yes | Device ID to filter motor logs | `1` |
| `startDate` | string | No | Start date filter (YYYY-MM-DD) | `2025-01-01` |
| `endDate` | string | No | End date filter (YYYY-MM-DD) | `2025-01-31` |
| `phoneNumber` | string | No | Phone number filter | `+1234567890` |
| `motorStatus` | string | No | Motor status filter (ON/OFF) | `ON` |
| `page` | integer | No | Page number (default: 0) | `0` |
| `size` | integer | No | Records per page (default: 100) | `50` |

### Example Request

```bash
# Get motor logs for device ID 1
GET /api/logs?deviceId=1

# Get motor logs for device ID 1 with date range
GET /api/logs?deviceId=1&startDate=2025-01-01&endDate=2025-01-31

# Get motor logs for device ID 1 with pagination
GET /api/logs?deviceId=1&page=0&size=20

# Get motor logs for device ID 1 with motor status filter
GET /api/logs?deviceId=1&motorStatus=ON
```

### Response Format

```json
{
  "logs": [
    {
      "id": 1,
      "deviceId": 1,
      "userId": 3,
      "timestamp": "2025-01-15 10:30:00",
      "motorStatus": "ON",
      "voltage": 220.5,
      "current": 2.1,
      "waterLevel": 75,
      "runTime": 120,
      "mode": "AUTO",
      "clock": "10:30:00",
      "command": "START",
      "phoneNumber": "+1234567890",
      "deviceName": "Water Pump 01",
      "isSynced": true,
      "device": {
        "id": 1,
        "name": "Water Pump 01",
        "smsNumber": "+1234567890",
        "isActive": true
      },
      "user": {
        "id": 3,
        "name": "John Doe",
        "email": "john@example.com",
        "phoneNumber": "+9876543210"
      },
      "createdAt": "2025-01-15T10:30:00.000Z",
      "updatedAt": "2025-01-15T10:30:00.000Z"
    }
  ],
  "totalCount": 25,
  "page": 0,
  "size": 100,
  "hasNext": false
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `id` | integer | Motor log ID |
| `deviceId` | integer | Device ID |
| `userId` | integer | User/Customer ID |
| `timestamp` | string | Log timestamp |
| `motorStatus` | string | Motor status (ON/OFF) |
| `voltage` | float | Voltage reading |
| `current` | float | Current reading |
| `waterLevel` | integer | Water level percentage |
| `runTime` | integer | Motor run time in minutes |
| `mode` | string | Motor mode |
| `clock` | string | Device clock time |
| `command` | string | Command sent to device |
| `phoneNumber` | string | Device phone number |
| `deviceName` | string | Device name |
| `isSynced` | boolean | Sync status |
| `device` | object | Device information |
| `user` | object | User/Customer information |
| `createdAt` | string | Creation timestamp |
| `updatedAt` | string | Last update timestamp |

### Error Responses

#### 400 Bad Request
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "deviceId": ["The device id field is required."]
  }
}
```

#### 404 Not Found
```json
{
  "success": false,
  "message": "Device not found"
}
```

#### 500 Internal Server Error
```json
{
  "success": false,
  "message": "Failed to retrieve motor logs",
  "error": "Database connection error"
}
```

### Usage Examples

#### JavaScript/Fetch
```javascript
// Get motor logs for device ID 1
fetch('/api/logs?deviceId=1')
  .then(response => response.json())
  .then(data => {
    console.log('Motor logs:', data.logs);
    console.log('Total count:', data.totalCount);
  })
  .catch(error => console.error('Error:', error));
```

#### cURL
```bash
# Get motor logs for device ID 1
curl -X GET "https://your-domain.com/api/logs?deviceId=1"

# Get motor logs with date range
curl -X GET "https://your-domain.com/api/logs?deviceId=1&startDate=2025-01-01&endDate=2025-01-31"

# Get motor logs with pagination
curl -X GET "https://your-domain.com/api/logs?deviceId=1&page=0&size=20"
```

#### PHP
```php
// Get motor logs for device ID 1
$response = file_get_contents('https://your-domain.com/api/logs?deviceId=1');
$data = json_decode($response, true);

if ($data['success']) {
    $logs = $data['logs'];
    $totalCount = $data['totalCount'];
} else {
    echo 'Error: ' . $data['message'];
}
```

### Notes

- **Authentication**: This is a public API endpoint, no authentication required
- **Rate Limiting**: Standard rate limiting applies
- **Pagination**: Use `page` and `size` parameters for pagination
- **Date Format**: Use YYYY-MM-DD format for date parameters
- **Device ID**: Must be a valid device ID that exists in the system
- **Response Time**: Typically responds within 200-500ms
- **Caching**: Responses are not cached, always returns fresh data

### Related Endpoints

- `GET /api/logs` - Get all motor logs
- `GET /api/logs/{id}` - Get specific motor log by ID
- `POST /api/logs` - Create new motor log
- `POST /api/logs/batch` - Create multiple motor logs
