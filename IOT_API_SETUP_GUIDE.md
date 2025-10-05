# IoT Motor Control API - Setup Guide

## Quick Setup

### 1. Database Migration
The motor_logs table has been created. You can verify with:
```bash
php artisan migrate:status
```

### 2. API Endpoints Available

#### Motor Logs API
- `POST /api/logs` - Sync single log
- `POST /api/logs/batch` - Batch sync logs
- `GET /api/logs` - Get logs with filters
- `GET /api/logs/{id}` - Get single log
- `DELETE /api/logs/{id}` - Delete log
- `GET /api/logs/unsynced/count` - Get unsynced count

#### Reports API
- `GET /api/reports/daily` - Daily report
- `GET /api/reports/weekly` - Weekly report
- `GET /api/reports/monthly` - Monthly report
- `GET /api/reports/custom` - Custom date range report
- `GET /api/reports/summary` - Dashboard summary

#### Webhooks API
- `POST /api/webhooks/motor-command` - Motor command events
- `POST /api/webhooks/sms-status` - SMS status events
- `GET /api/webhooks/health` - Webhook health check

#### System API
- `GET /api/health` - General API health check

### 3. Test the API

#### Health Check
```bash
curl -X GET http://localhost:8000/api/health
```

#### Sync Single Log
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

#### Get Daily Report
```bash
curl -X GET "http://localhost:8000/api/reports/daily?date=1704067200000"
```

#### Send Webhook Event
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

### 4. Database Structure

The `motor_logs` table includes:
- `id` - Auto-increment primary key
- `timestamp` - Unix timestamp in milliseconds
- `motor_status` - ON, OFF, or STATUS
- `voltage`, `current`, `water_level` - Sensor readings (nullable)
- `mode`, `clock` - Additional data (nullable)
- `command` - MOTORON, MOTOROFF, or STATUS
- `phone_number` - Phone number for SMS commands
- `is_synced` - Sync status flag
- `created_at`, `updated_at` - Timestamps

### 5. Features Implemented

✅ **Motor Log Management**
- Single log sync
- Batch log sync (recommended for efficiency)
- Log retrieval with filtering and pagination
- Log deletion
- Unsynced count tracking

✅ **Advanced Reporting**
- Daily, weekly, monthly reports
- Custom date range reports
- Dashboard summary
- Uptime/downtime calculations
- Average sensor readings

✅ **Real-time Webhooks**
- Motor command events
- SMS status updates
- Comprehensive logging
- Health monitoring

✅ **Data Validation**
- Comprehensive input validation
- Error handling with detailed messages
- Proper HTTP status codes

✅ **Performance Optimizations**
- Database indexes for fast queries
- Efficient pagination
- Batch processing support

### 6. Mobile App Integration

The API is designed for easy integration with the IoT Motor Control Android app:

1. **Sync Strategy**: Use batch sync every 15 minutes
2. **Error Handling**: Implement retry logic with exponential backoff
3. **Real-time Updates**: Use webhooks for immediate notifications
4. **Reports**: Fetch reports for dashboard display

### 7. Production Considerations

For production deployment:

1. **Authentication**: Add API key authentication
2. **Rate Limiting**: Implement rate limiting middleware
3. **HTTPS**: Use SSL certificates
4. **Monitoring**: Set up logging and monitoring
5. **Backup**: Implement database backup strategy

### 8. API Documentation

Complete API documentation is available in `IOT_API_DOCUMENTATION.md` with:
- Detailed endpoint descriptions
- Request/response examples
- Validation rules
- Error codes
- cURL examples

---

**Status**: ✅ All IoT Motor Control API endpoints are implemented and tested successfully!
