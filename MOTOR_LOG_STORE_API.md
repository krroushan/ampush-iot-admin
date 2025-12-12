# Motor Log Store API

## POST /api/logs

### Request Body

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `timestamp` | string | Yes | Timestamp (min 10 characters) |
| `motorStatus` | string | Yes | Motor status: ON, OFF, or STATUS |
| `voltage` | number | No | Voltage reading (min: 0) |
| `current` | number | No | Current reading (min: 0) |
| `waterLevel` | number | No | Water level percentage (0-100) |
| `runTime` | integer | No | Run time in seconds (0-3600) |
| `mode` | string | No | Mode (max 20 characters) |
| `clock` | string | No | Clock time (max 50 characters) |
| `command` | string | Yes | Command: MOTORON, MOTOROFF, or STATUS |
| `phoneNumber` | string | Yes | Phone number (max 20 characters) |

### Success Response (200)

```json
{
  "id": 123,
  "success": true,
  "message": "Log synced successfully",
  "syncedAt": "2025-01-15T10:30:00.000Z"
}
```

### Validation Error Response (400)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "timestamp": ["The timestamp field is required."],
    "motorStatus": ["The motor status field is required."]
  }
}
```

### Server Error Response (500)

```json
{
  "success": false,
  "message": "Failed to sync log",
  "error": "Error message details"
}
```

## GET /api/logs/{id}

### Path Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Log ID |

### Success Response (200)

```json
{
  "id": 123,
  "timestamp": "2025-01-15 10:30:00",
  "motorStatus": "ON",
  "voltage": 220.5,
  "current": 2.1,
  "waterLevel": 75,
  "mode": "AUTO",
  "clock": "10:30:00",
  "command": "MOTORON",
  "phoneNumber": "+1234567890",
  "deviceName": "Water Pump 01",
  "isSynced": true,
  "createdAt": "2025-01-15T10:30:00.000Z",
  "updatedAt": "2025-01-15T10:30:00.000Z"
}
```

### Not Found Response (404)

```json
{
  "success": false,
  "message": "Log not found",
  "error": "Error message details"
}
```

## GET /api/logs

### Query Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `startDate` | integer | No | Start date timestamp (min: 0) |
| `endDate` | integer | No | End date timestamp (min: 0) |
| `phoneNumber` | string | No | Phone number filter (max 20 characters) |
| `motorStatus` | string | No | Motor status: ON, OFF, or STATUS |
| `page` | integer | No | Page number (default: 0, min: 0) |
| `size` | integer | No | Records per page (default: 100, min: 1, max: 1000) |

### Success Response (200)

```json
{
  "logs": [
    {
      "id": 123,
      "timestamp": "2025-01-15 10:30:00",
      "motorStatus": "ON",
      "voltage": 220.5,
      "current": 2.1,
      "waterLevel": 75,
      "mode": "AUTO",
      "clock": "10:30:00",
      "command": "MOTORON",
      "phoneNumber": "+1234567890",
      "deviceName": "Water Pump 01",
      "isSynced": true,
      "createdAt": "2025-01-15T10:30:00.000Z",
      "updatedAt": "2025-01-15T10:30:00.000Z"
    }
  ],
  "totalCount": 100,
  "page": 0,
  "size": 100,
  "hasNext": false
}
```

### Validation Error Response (400)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "startDate": ["The start date must be an integer."],
    "size": ["The size must not be greater than 1000."]
  }
}
```

### Server Error Response (500)

```json
{
  "success": false,
  "message": "Failed to retrieve logs",
  "error": "Error message details"
}
```
