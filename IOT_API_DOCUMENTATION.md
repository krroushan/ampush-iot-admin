# IoT Motor Control App - Backend API Documentation

## Overview
This document outlines the required API endpoints for syncing data from the IoT Motor Control Android application to a backend database. The app manages motor control operations via SMS and stores logs locally, requiring periodic synchronization with a backend server.

## Base URL
```
http://localhost:8000/api/
```

## Authentication
Currently, the IoT API endpoints are public (no authentication required) for easy mobile app integration. In production, you may want to add API key authentication.

## Data Models

### 1. Motor Log Entity
```json
{
  "id": "long (auto-generated)",
  "timestamp": "long (Unix timestamp in milliseconds)",
  "motorStatus": "string (ON|OFF|STATUS)",
  "voltage": "float (nullable)",
  "current": "float (nullable)", 
  "waterLevel": "float (nullable)",
  "mode": "string (nullable)",
  "clock": "string (nullable)",
  "command": "string (MOTORON|MOTOROFF|STATUS)",
  "phoneNumber": "string",
  "isSynced": "boolean",
  "createdAt": "datetime",
  "updatedAt": "datetime"
}
```

---

## API Endpoints

### 1. Sync Single Log
**POST** `/api/logs`

Sync a single motor log entry to the backend.

**Request Body:**
```json
{
  "timestamp": 1704067200000,
  "motorStatus": "ON",
  "voltage": 220.5,
  "current": 2.3,
  "waterLevel": 85.0,
  "mode": "AUTO",
  "clock": "2024-01-01 10:30:00",
  "command": "MOTORON",
  "phoneNumber": "+919876543210"
}
```

**Response:**
```json
{
  "id": 12345,
  "success": true,
  "message": "Log synced successfully",
  "syncedAt": "2024-01-01T10:30:00Z"
}
```

**Status Codes:**
- `200` - Success
- `400` - Bad Request (validation error)
- `500` - Internal Server Error

---

### 2. Batch Sync Logs
**POST** `/api/logs/batch`

Sync multiple motor log entries in a single request (recommended for efficiency).

**Request Body:**
```json
[
  {
    "timestamp": 1704067200000,
    "motorStatus": "ON",
    "voltage": 220.5,
    "current": 2.3,
    "waterLevel": 85.0,
    "mode": "AUTO",
    "clock": "2024-01-01 10:30:00",
    "command": "MOTORON",
    "phoneNumber": "+919876543210"
  },
  {
    "timestamp": 1704067260000,
    "motorStatus": "OFF",
    "voltage": 0.0,
    "current": 0.0,
    "waterLevel": 85.0,
    "mode": "AUTO",
    "clock": "2024-01-01 10:31:00",
    "command": "MOTOROFF",
    "phoneNumber": "+919876543210"
  }
]
```

**Response:**
```json
[
  {
    "id": 12345,
    "success": true,
    "message": "Log synced successfully",
    "syncedAt": "2024-01-01T10:30:00Z"
  },
  {
    "id": 12346,
    "success": true,
    "message": "Log synced successfully",
    "syncedAt": "2024-01-01T10:31:00Z"
  }
]
```

**Status Codes:**
- `200` - Success (all logs synced)
- `207` - Multi-Status (some logs failed)
- `400` - Bad Request
- `500` - Internal Server Error

---

### 3. Get Logs
**GET** `/api/logs`

Retrieve motor logs with optional filtering and pagination.

**Query Parameters:**
- `startDate` (optional): Unix timestamp for start date filter
- `endDate` (optional): Unix timestamp for end date filter
- `phoneNumber` (optional): Filter by phone number
- `motorStatus` (optional): Filter by motor status (ON|OFF|STATUS)
- `page` (optional): Page number (default: 0)
- `size` (optional): Page size (default: 100, max: 1000)

**Example:**
```
GET /api/logs?startDate=1704067200000&endDate=1704153600000&page=0&size=50&phoneNumber=%2B919876543210
```

**Response:**
```json
{
  "logs": [
    {
      "id": 12345,
      "timestamp": 1704067200000,
      "motorStatus": "ON",
      "voltage": 220.5,
      "current": 2.3,
      "waterLevel": 85.0,
      "mode": "AUTO",
      "clock": "2024-01-01 10:30:00",
      "command": "MOTORON",
      "phoneNumber": "+919876543210",
      "isSynced": true,
      "createdAt": "2024-01-01T10:30:00Z",
      "updatedAt": "2024-01-01T10:30:00Z"
    }
  ],
  "totalCount": 150,
  "page": 0,
  "size": 50,
  "hasNext": true
}
```

---

### 4. Get Single Log
**GET** `/api/logs/{id}`

Retrieve a specific motor log by ID.

**Response:**
```json
{
  "id": 12345,
  "timestamp": 1704067200000,
  "motorStatus": "ON",
  "voltage": 220.5,
  "current": 2.3,
  "waterLevel": 85.0,
  "mode": "AUTO",
  "clock": "2024-01-01 10:30:00",
  "command": "MOTORON",
  "phoneNumber": "+919876543210",
  "isSynced": true,
  "createdAt": "2024-01-01T10:30:00Z",
  "updatedAt": "2024-01-01T10:30:00Z"
}
```

---

### 5. Delete Log
**DELETE** `/api/logs/{id}`

Delete a specific motor log by ID.

**Response:**
```json
{
  "success": true,
  "message": "Log deleted successfully"
}
```

---

### 6. Get Unsynced Logs Count
**GET** `/api/logs/unsynced/count`

Get the count of unsynced logs (for monitoring sync status).

**Response:**
```json
{
  "count": 5,
  "message": "Unsynced logs count retrieved successfully"
}
```

---

## Reports API

### 7. Daily Report
**GET** `/api/reports/daily`

Get daily motor operation report.

**Query Parameters:**
- `date` (required): Unix timestamp for the date
- `phoneNumber` (optional): Filter by phone number

**Example:**
```
GET /api/reports/daily?date=1704067200000&phoneNumber=%2B919876543210
```

**Response:**
```json
{
  "period": "daily",
  "startDate": "2024-01-01T00:00:00Z",
  "endDate": "2024-01-01T23:59:59Z",
  "totalOperations": 25,
  "motorOnCount": 12,
  "motorOffCount": 12,
  "statusRequests": 1,
  "averageVoltage": 218.5,
  "averageCurrent": 2.1,
  "averageWaterLevel": 82.3,
  "uptime": "8h 30m",
  "downtime": "15h 30m",
  "totalMinutes": 1440,
  "uptimeMinutes": 510,
  "downtimeMinutes": 930
}
```

---

### 8. Weekly Report
**GET** `/api/reports/weekly`

Get weekly motor operation report.

**Query Parameters:**
- `weekStart` (required): Unix timestamp for the start of the week
- `phoneNumber` (optional): Filter by phone number

**Example:**
```
GET /api/reports/weekly?weekStart=1704067200000&phoneNumber=%2B919876543210
```

**Response:** (Same format as daily report)

---

### 9. Monthly Report
**GET** `/api/reports/monthly`

Get monthly motor operation report.

**Query Parameters:**
- `monthStart` (required): Unix timestamp for the start of the month
- `phoneNumber` (optional): Filter by phone number

**Example:**
```
GET /api/reports/monthly?monthStart=1704067200000&phoneNumber=%2B919876543210
```

**Response:** (Same format as daily report)

---

### 10. Custom Date Range Report
**GET** `/api/reports/custom`

Get custom date range motor operation report.

**Query Parameters:**
- `startDate` (required): Unix timestamp for start date
- `endDate` (required): Unix timestamp for end date
- `phoneNumber` (optional): Filter by phone number

**Example:**
```
GET /api/reports/custom?startDate=1704067200000&endDate=1704153600000&phoneNumber=%2B919876543210
```

**Response:** (Same format as daily report)

---

### 11. Dashboard Summary
**GET** `/api/reports/summary`

Get summary report for dashboard (last 7 days by default).

**Query Parameters:**
- `phoneNumber` (optional): Filter by phone number
- `days` (optional): Number of days to include (default: 7, max: 365)

**Example:**
```
GET /api/reports/summary?phoneNumber=%2B919876543210&days=30
```

**Response:**
```json
{
  "period": "last_30_days",
  "startDate": "2024-01-01T00:00:00Z",
  "endDate": "2024-01-31T23:59:59Z",
  "totalOperations": 750,
  "motorOnCount": 375,
  "motorOffCount": 375,
  "statusRequests": 50,
  "uniquePhoneNumbers": 3,
  "averageVoltage": 218.5,
  "averageCurrent": 2.1,
  "averageWaterLevel": 82.3
}
```

---

## Webhook Integration

### 12. Motor Command Webhook
**POST** `/api/webhooks/motor-command`

The app sends real-time motor command events to this webhook for monitoring.

**Request Body:**
```json
{
  "event": "motor_command",
  "command": "MOTOR_ON",
  "status": "SMS_SENT",
  "phone_number": "+919876543210",
  "timestamp": 1704067200000,
  "app": "IoT_Motor_Control",
  "version": "1.0.0"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Motor command webhook processed successfully",
  "received_at": "2024-01-01T10:30:00Z",
  "event_id": "motor_cmd_65a1b2c3d4e5f"
}
```

---

### 13. SMS Status Webhook
**POST** `/api/webhooks/sms-status`

The app sends SMS status updates to this webhook.

**Request Body:**
```json
{
  "event": "sms_status",
  "sms_type": "MOTOR_ON",
  "phone_number": "+919876543210",
  "message": "MOTORON",
  "success": true,
  "timestamp": 1704067200000,
  "app": "IoT_Motor_Control"
}
```

**Response:**
```json
{
  "success": true,
  "message": "SMS status webhook processed successfully",
  "received_at": "2024-01-01T10:30:00Z",
  "event_id": "sms_status_65a1b2c3d4e5f"
}
```

---

### 14. Webhook Health Check
**GET** `/api/webhooks/health`

Check webhook system health.

**Response:**
```json
{
  "status": "healthy",
  "timestamp": "2024-01-01T10:30:00Z",
  "webhooks": {
    "motor_command": "/api/webhooks/motor-command",
    "sms_status": "/api/webhooks/sms-status"
  },
  "version": "1.0.0"
}
```

---

### 15. General API Health Check
**GET** `/api/health`

Check overall API health.

**Response:**
```json
{
  "success": true,
  "message": "API is healthy",
  "timestamp": "2024-01-01T10:30:00Z",
  "version": "1.0.0"
}
```

---

## Error Handling

### Standard Error Response
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

### Common Error Codes
- `200` - Success
- `400` - Bad Request (validation error)
- `404` - Not Found
- `500` - Internal Server Error
- `207` - Multi-Status (partial success in batch operations)

---

## Validation Rules

### Motor Log Validation
- `timestamp`: Required, integer, min: 0
- `motorStatus`: Required, string, in: ON, OFF, STATUS
- `voltage`: Optional, numeric, min: 0
- `current`: Optional, numeric, min: 0
- `waterLevel`: Optional, numeric, min: 0, max: 100
- `mode`: Optional, string, max: 20
- `clock`: Optional, string, max: 50
- `command`: Required, string, in: MOTORON, MOTOROFF, STATUS
- `phoneNumber`: Required, string, max: 20

### Webhook Validation
- `event`: Required, string
- `command`: Required, string, in: MOTOR_ON, MOTOR_OFF, STATUS
- `status`: Required, string, in: SMS_SENT, SMS_DELIVERED, SMS_FAILED, MOTOR_ON, MOTOR_OFF, ERROR
- `phone_number`: Required, string, max: 20
- `timestamp`: Required, integer, min: 0
- `app`: Required, string
- `version`: Required, string (for motor command webhook)

---

## Rate Limiting
- **Sync endpoints**: 100 requests per minute
- **Report endpoints**: 20 requests per minute
- **Webhook endpoints**: 200 requests per minute

---

## Data Retention
- **Motor logs**: Retained for 2 years
- **Reports**: Generated on-demand, not stored
- **Webhook events**: Logged for 30 days

---

## Security Considerations
1. **HTTPS**: All endpoints must use HTTPS in production
2. **Input Validation**: All input data is validated
3. **SQL Injection**: Using Eloquent ORM with parameterized queries
4. **Rate Limiting**: Implement rate limiting in production
5. **Logging**: All API requests and responses are logged
6. **Data Encryption**: Consider encrypting sensitive data at rest

---

## Database Schema

### Motor Logs Table
```sql
CREATE TABLE motor_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    timestamp BIGINT NOT NULL,
    motor_status VARCHAR(10) NOT NULL,
    voltage FLOAT NULL,
    current FLOAT NULL,
    water_level FLOAT NULL,
    mode VARCHAR(20) NULL,
    clock VARCHAR(50) NULL,
    command VARCHAR(20) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    is_synced BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_timestamp (timestamp),
    INDEX idx_phone_number (phone_number),
    INDEX idx_is_synced (is_synced),
    INDEX idx_phone_timestamp (phone_number, timestamp),
    INDEX idx_status_timestamp (motor_status, timestamp)
);
```

---

## Implementation Notes

### Sync Strategy
1. **Immediate Sync**: For real-time motor commands (no delay)
2. **Periodic Sync**: Every 15 minutes for batch operations
3. **Retry Logic**: Exponential backoff for failed syncs
4. **Conflict Resolution**: Last-write-wins strategy

### Data Flow
1. App receives SMS with motor data
2. Data is stored locally in Room database
3. Background worker syncs unsynced data to backend
4. Backend processes and stores data
5. App marks data as synced locally

### Monitoring
- Webhook integration for real-time monitoring
- Comprehensive logging for debugging
- Health check endpoints for system monitoring
- Performance metrics and analytics

---

## Testing

### Test Data
```json
{
  "timestamp": 1704067200000,
  "motorStatus": "ON",
  "voltage": 220.5,
  "current": 2.3,
  "waterLevel": 85.0,
  "mode": "AUTO",
  "clock": "2024-01-01 10:30:00",
  "command": "MOTORON",
  "phoneNumber": "+919876543210"
}
```

### Test Scenarios
1. Single log sync
2. Batch log sync
3. Network failure handling
4. Data validation
5. Rate limiting
6. Large dataset handling
7. Report generation
8. Webhook processing

---

## Example cURL Commands

### Sync Single Log
```bash
curl -X POST http://localhost:8000/api/logs \
  -H "Content-Type: application/json" \
  -d '{
    "timestamp": 1704067200000,
    "motorStatus": "ON",
    "voltage": 220.5,
    "current": 2.3,
    "waterLevel": 85.0,
    "mode": "AUTO",
    "clock": "2024-01-01 10:30:00",
    "command": "MOTORON",
    "phoneNumber": "+919876543210"
  }'
```

### Get Daily Report
```bash
curl -X GET "http://localhost:8000/api/reports/daily?date=1704067200000&phoneNumber=%2B919876543210"
```

### Send Motor Command Webhook
```bash
curl -X POST http://localhost:8000/api/webhooks/motor-command \
  -H "Content-Type: application/json" \
  -d '{
    "event": "motor_command",
    "command": "MOTOR_ON",
    "status": "SMS_SENT",
    "phone_number": "+919876543210",
    "timestamp": 1704067200000,
    "app": "IoT_Motor_Control",
    "version": "1.0.0"
  }'
```

---

**Note**: This API documentation covers all the endpoints implemented in the Laravel backend for the IoT Motor Control Android application's data synchronization requirements.
