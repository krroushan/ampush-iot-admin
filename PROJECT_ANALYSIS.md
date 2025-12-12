# IoT Admin Project - Full Analysis

## Project Overview

**Project Name:** ampush-iot-admin  
**Type:** IoT Motor Control Management System  
**Framework:** Laravel 12.0 with Livewire/Volt  
**Purpose:** Admin panel and API backend for managing IoT motor control devices, customers, motor logs, and notifications

---

## Technology Stack

### Backend
- **PHP:** ^8.2
- **Laravel:** ^12.0
- **Authentication:** Laravel Fortify + Laravel Sanctum
- **UI Framework:** Livewire Flux ^2.1.1, Livewire Volt ^1.7.0
- **Firebase:** kreait/firebase-php ^7.22 (for push notifications)

### Frontend
- **CSS Framework:** Tailwind CSS ^4.0.7
- **Build Tool:** Vite ^7.0.4
- **JavaScript:** Axios ^1.7.4

### Database
- **Primary:** SQLite (development)
- **ORM:** Eloquent

### Testing
- **Framework:** Pest PHP ^4.1

---

## Architecture

### Application Structure

```
├── Admin Panel (Web Interface)
│   ├── Dashboard with statistics
│   ├── Customer Management (CRUD)
│   ├── Device Management (CRUD)
│   ├── Motor Logs Management
│   ├── Reports & Analytics
│   └── Notification Management
│
├── Customer API (Mobile App)
│   ├── Authentication (Sanctum)
│   ├── Profile Management
│   ├── Device Listing
│   └── FCM Token Management
│
├── IoT Device API (Public)
│   ├── Motor Log Sync (Single/Batch)
│   ├── Device Registration
│   ├── Activity Updates
│   └── Reports
│
└── Webhook API (Public)
    ├── Motor Command Events
    ├── SMS Status Updates
    └── Health Checks
```

---

## Database Structure

### Core Tables

#### 1. **users**
- **Roles:** `admin`, `customer`
- **Key Fields:**
  - `name`, `email`, `password`
  - `phone_number` (unique identifier for customers)
  - `role` (admin/customer)
  - `fcm_token` (Firebase Cloud Messaging)
  - `unit_price`, `motor_pumping_capacity`
  - `address`, `address_line_1`, `address_line_2`, `city`, `state`, `postal_code`, `country`
  - `profile_photo`
  - Two-factor authentication fields

#### 2. **devices**
- **Key Fields:**
  - `device_name`
  - `sms_number` (unique, used for SMS commands)
  - `user_id` (assigned customer, nullable)
  - `is_active` (boolean)
  - `description`
  - `last_activity_at` (timestamp)

#### 3. **motor_logs**
- **Key Fields:**
  - `device_id` (nullable, linked to device)
  - `user_id` (nullable, linked to customer)
  - `timestamp` (string, Unix timestamp in milliseconds)
  - `motor_status` (ON/OFF/STATUS)
  - `voltage`, `current`, `water_level` (sensor readings)
  - `run_time` (seconds, 0-3600)
  - `mode`, `clock`
  - `command` (MOTORON/MOTOROFF/STATUS)
  - `phone_number` (legacy field, for backward compatibility)
  - `is_synced` (boolean)

#### 4. **notifications**
- **Key Fields:**
  - `title`, `body`
  - `data` (JSON)
  - `type`
  - `user_id`
  - `fcm_token`
  - `sent`, `sent_at`
  - `sent_count`, `failure_count`

---

## Key Features

### 1. **Admin Panel Features**
- ✅ Dashboard with comprehensive statistics
- ✅ Customer management (CRUD operations)
- ✅ Device management (CRUD, assignment/unassignment)
- ✅ Motor logs viewing and management
- ✅ Bulk delete operations
- ✅ Reports (daily, weekly, monthly, custom)
- ✅ Push notification management
- ✅ Two-factor authentication support
- ✅ Profile management with photo upload

### 2. **Customer API Features**
- ✅ Authentication via phone number/email
- ✅ Profile management
- ✅ Device listing (assigned devices)
- ✅ FCM token registration
- ✅ Token refresh mechanism

### 3. **IoT Device API Features**
- ✅ Single log sync (`POST /api/logs`)
- ✅ Batch log sync (`POST /api/logs/batch`)
- ✅ Log retrieval with filtering and pagination
- ✅ Device registration/update
- ✅ Activity tracking
- ✅ Reports (daily, weekly, monthly, yearly, custom)
- ✅ Health check endpoint

### 4. **Notification System**
- ✅ Firebase Cloud Messaging integration
- ✅ Send to all customers
- ✅ Send to specific customer
- ✅ Send by phone numbers
- ✅ Track sent/failure counts

---

## API Structure

### Public APIs (No Authentication)

#### Motor Logs API (`/api/logs`)
- `POST /api/logs` - Sync single log
- `POST /api/logs/batch` - Batch sync logs
- `GET /api/logs` - Get logs with filters
- `GET /api/logs/{id}` - Get single log
- `DELETE /api/logs/{id}` - Delete log
- `GET /api/logs/unsynced/count` - Get unsynced count

#### Devices API (`/api/devices`)
- `GET /api/devices` - List devices with filters
- `GET /api/devices/{identifier}` - Get device by ID or SMS number
- `POST /api/devices` - Register/update device
- `POST /api/devices/{smsNumber}/activity` - Update activity
- `POST /api/devices/by-phone` - Get device by phone

#### Reports API (`/api/reports`)
- `GET /api/reports/daily` - Daily report
- `GET /api/reports/weekly` - Weekly report
- `GET /api/reports/monthly` - Monthly report
- `GET /api/reports/yearly` - Yearly report
- `GET /api/reports/custom` - Custom date range
- `GET /api/reports/summary` - Dashboard summary

#### Webhooks API (`/api/webhooks`)
- `POST /api/webhooks/motor-command` - Motor command events
- `POST /api/webhooks/sms-status` - SMS status updates
- `GET /api/webhooks/health` - Health check

#### Utility APIs
- `GET /api/health` - API health check
- `GET /api/validate-phone` - Phone number validation

### Protected APIs (Sanctum Authentication)

#### Customer API (`/api/customer`)
- `POST /api/customer/login` - Customer login
- `GET /api/customer/profile` - Get profile
- `PUT /api/customer/profile` - Update profile
- `POST /api/customer/logout` - Logout
- `POST /api/customer/refresh-token` - Refresh token
- `GET /api/customer/devices` - Get assigned devices
- `POST /api/customer/fcm-token` - Update FCM token

#### Admin API (`/api/admin`)
- `POST /api/admin/notifications/send-all` - Send to all
- `POST /api/admin/notifications/send-customer/{id}` - Send to customer
- `POST /api/admin/notifications/send-by-phone` - Send by phone
- `GET /api/admin/notifications/customers` - Get customers with tokens

---

## Authentication & Authorization

### Admin Panel
- **Method:** Laravel Fortify
- **Middleware:** `auth`, `verified`, `admin`
- **Features:**
  - Email/password login
  - Two-factor authentication
  - Remember me
  - Password reset

### Customer API
- **Method:** Laravel Sanctum (Token-based)
- **Middleware:** `auth:sanctum`, `customer`
- **Login:** Phone number or email + password
- **Token Management:** Refresh tokens supported

### Custom Middleware
- `EnsureUserIsAdmin` - Validates admin role
- `EnsureCustomerRole` - Validates customer role

---

## Models & Relationships

### User Model
```php
Relationships:
- hasMany(Device::class) - devices
- hasMany(MotorLog::class) - motorLogs
- hasMany(MotorLog::class, 'phone_number') - motorLogsByPhone (legacy)
```

### Device Model
```php
Relationships:
- belongsTo(User::class) - user/customer
- hasMany(MotorLog::class) - motorLogs
- hasMany(MotorLog::class, 'phone_number') - motorLogsByPhone (legacy)

Scopes:
- active() - Active devices
- inactive() - Inactive devices
- assigned() - Assigned to customers
- unassigned() - Not assigned
```

### MotorLog Model
```php
Relationships:
- belongsTo(Device::class) - device
- belongsTo(User::class) - user/customer

Scopes:
- dateRange($start, $end) - Filter by date range
- byPhone($phone) - Filter by phone number
- byStatus($status) - Filter by motor status
- byDevice($deviceId) - Filter by device
- byUser($userId) - Filter by user
- unsynced() - Unsynced logs
```

### Notification Model
```php
Relationships:
- belongsTo(User::class) - user
```

---

## Services

### FirebaseService
**Location:** `app/Services/FirebaseService.php`

**Methods:**
- `sendNotification($token, $title, $body, $data)` - Send to single device
- `sendToMultipleDevices($tokens, $title, $body, $data)` - Multicast
- `sendToTopic($topic, $title, $body, $data)` - Send to topic
- `sendWithAndroidConfig(...)` - Custom Android config

**Configuration:**
- Uses Firebase Admin SDK
- Service account credentials from environment variables
- Error logging integrated

---

## Frontend Architecture

### UI Framework
- **Livewire Flux:** Component library for admin panel
- **Livewire Volt:** Single-file components
- **Tailwind CSS:** Utility-first CSS framework

### Key Views
- `dashboard.blade.php` - Main dashboard
- `customers/*` - Customer management views
- `devices/*` - Device management views
- `motor-logs/*` - Motor logs views
- `flux/*` - Livewire Flux components
- `livewire/*` - Livewire components

---

## File Structure

```
app/
├── Console/Commands/        # Artisan commands
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   ├── Api/            # API controllers
│   │   └── ...             # Web controllers
│   └── Middleware/         # Custom middleware
├── Livewire/               # Livewire components
├── Models/                 # Eloquent models
├── Providers/              # Service providers
└── Services/               # Business logic services

config/                     # Configuration files
database/
├── migrations/             # Database migrations
├── seeders/                # Database seeders
└── factories/              # Model factories

resources/
├── css/                    # Stylesheets
├── js/                     # JavaScript
└── views/                  # Blade templates

routes/
├── api.php                 # API routes
├── web.php                 # Web routes
└── auth.php                # Authentication routes

public/                     # Public assets
storage/                    # Storage (logs, cache, etc.)
tests/                      # Test files
```

---

## Key Dependencies

### Production
- `laravel/framework: ^12.0` - Core framework
- `laravel/fortify: ^1.30` - Authentication
- `laravel/sanctum: ^4.0` - API authentication
- `livewire/flux: ^2.1.1` - UI components
- `livewire/volt: ^1.7.0` - Single-file components
- `kreait/firebase-php: ^7.22` - Firebase integration

### Development
- `pestphp/pest: ^4.1` - Testing framework
- `laravel/pint: ^1.18` - Code style
- `laravel/sail: ^1.41` - Docker environment

---

## Configuration Highlights

### Authentication
- **Fortify:** Email/password, 2FA support
- **Sanctum:** Token-based API auth
- **Middleware:** Custom role-based access control

### Database
- **Default:** SQLite (development)
- **Migrations:** 18 migration files
- **Relationships:** Properly defined with foreign keys

### Firebase
- **Service Account:** Configured via environment variables
- **Project ID:** `ampush-iot`
- **Features:** FCM push notifications

---

## Security Considerations

### Implemented
- ✅ Password hashing (bcrypt)
- ✅ CSRF protection (web routes)
- ✅ SQL injection protection (Eloquent ORM)
- ✅ XSS protection (Blade templating)
- ✅ Role-based access control
- ✅ Two-factor authentication support
- ✅ API token authentication (Sanctum)

### Recommendations
- ⚠️ Add rate limiting to public APIs
- ⚠️ Add API key authentication for IoT endpoints
- ⚠️ Implement HTTPS in production
- ⚠️ Add request validation middleware
- ⚠️ Implement logging and monitoring

---

## Data Flow

### Motor Log Sync Flow
1. IoT device collects motor data
2. Device sends log to `POST /api/logs` or batch to `POST /api/logs/batch`
3. System finds device by `phone_number` (SMS number)
4. Links log to `device_id` and `user_id` if device exists
5. Stores log with `is_synced = true`
6. Returns success response with log ID

### Notification Flow
1. Admin creates notification via web panel or API
2. System retrieves customer FCM tokens
3. FirebaseService sends notifications via FCM
4. Notification record created with sent/failure counts
5. Status tracked in `notifications` table

### Device Registration Flow
1. IoT device calls `POST /api/devices` with device info
2. System checks if device exists (by SMS number)
3. If exists, updates device info
4. If new, creates device record (unassigned)
5. Admin assigns device to customer via web panel
6. Device can update activity via `POST /api/devices/{smsNumber}/activity`

---

## Testing

### Test Structure
- **Framework:** Pest PHP
- **Location:** `tests/`
- **Coverage:**
  - Feature tests for authentication
  - Dashboard tests
  - Settings tests

### Test Files
- `tests/Feature/Auth/*` - Authentication tests
- `tests/Feature/DashboardTest.php` - Dashboard tests
- `tests/Feature/Settings/*` - Settings tests

---

## Documentation

### API Documentation Files
- `API_DOCUMENTATION.md` - General API docs
- `API_SETUP_GUIDE.md` - Setup instructions
- `IOT_API_DOCUMENTATION.md` - IoT API details
- `IOT_API_SETUP_GUIDE.md` - IoT setup guide
- `DEVICES_API_DOCUMENTATION.md` - Device API
- `MOTOR_LOGS_BY_DEVICE_API.md` - Motor logs API
- `MOTOR_LOG_STORE_API.md` - Log store API
- `FCM_TOKEN_API_DOCUMENTATION.md` - FCM token API
- `FIREBASE_NOTIFICATIONS_API.md` - Notifications API
- `REPORTS_API_DOCUMENTATION.md` - Reports API
- `PHONE_VALIDATION_API.md` - Phone validation
- `LIVE_API_TEST_RESULTS.md` - Test results

---

## Known Patterns & Conventions

### Naming Conventions
- **Controllers:** PascalCase (e.g., `MotorLogController`)
- **Models:** PascalCase (e.g., `MotorLog`)
- **Routes:** kebab-case (e.g., `/motor-logs`)
- **API Responses:** camelCase (e.g., `motorStatus`)

### Response Format
```json
{
  "success": true/false,
  "message": "Description",
  "data": {...},
  "errors": {...}
}
```

### Error Handling
- Validation errors: 400 with `errors` object
- Not found: 404 with message
- Server errors: 500 with error details
- Unauthorized: 401
- Forbidden: 403

---

## Performance Considerations

### Database
- Indexes on frequently queried fields
- Eager loading for relationships (`with()`)
- Pagination for large datasets

### API
- Batch sync support for efficiency
- Pagination with configurable page size
- Filtering to reduce data transfer

### Caching
- Framework-level caching available
- No explicit caching implemented yet

---

## Deployment Considerations

### Environment Variables Required
- `APP_NAME`
- `APP_ENV`
- `APP_DEBUG`
- `APP_URL`
- `DB_CONNECTION`
- `FIREBASE_PROJECT_ID`
- `FIREBASE_PRIVATE_KEY`
- `FIREBASE_CLIENT_EMAIL`
- `FIREBASE_CLIENT_ID`

### Production Checklist
- [ ] Set `APP_DEBUG=false`
- [ ] Configure production database
- [ ] Set up HTTPS
- [ ] Configure Firebase credentials
- [ ] Set up queue workers
- [ ] Configure logging
- [ ] Set up backups
- [ ] Add rate limiting
- [ ] Configure CORS
- [ ] Set up monitoring

---

## Summary

This is a comprehensive IoT motor control management system with:
- **Admin Panel:** Full-featured web interface for managing customers, devices, and logs
- **Customer API:** Mobile app integration with authentication
- **IoT API:** Public endpoints for device data sync
- **Notification System:** Firebase Cloud Messaging integration
- **Reporting:** Comprehensive analytics and reports
- **Modern Stack:** Laravel 12, Livewire, Tailwind CSS

The system is well-structured with proper separation of concerns, comprehensive API documentation, and support for both web and mobile clients.

