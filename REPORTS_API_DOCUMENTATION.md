# Motor Reports API Documentation

**Base URL:** `https://laravel1.wizzyweb.com/api`

Complete API reference for motor consumption reports with device-wise breakdown.

---

## 📋 Table of Contents

1. [API Endpoints](#api-endpoints)
2. [Authentication](#authentication)
3. [Daily Report](#1-daily-report)
4. [Monthly Report](#2-monthly-report)
5. [Yearly Report](#3-yearly-report)
6. [Custom Range Report](#4-custom-range-report)
7. [Phone Validation](#5-phone-validation)
8. [Response Structure](#response-structure)
9. [Error Codes](#error-codes)

---

## API Endpoints

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/reports/daily` | GET | Get daily report with hourly breakdown |
| `/reports/monthly` | GET | Get monthly report with daily breakdown |
| `/reports/yearly` | GET | Get yearly report with monthly breakdown |
| `/reports/custom` | GET | Get custom date range report |
| `/validate-phone` | GET | Validate if phone number is registered |

---

## Authentication

All report APIs are **public** (no authentication required).

---

## 1. Daily Report

Get daily motor consumption report with 24-hour breakdown.

### Endpoint
```
GET /reports/daily
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `date` | string | No | Date in YYYY-MM-DD format (default: today) |
| `device_id` | integer | No | Filter by specific device |
| `user_id` | integer | No | Filter by specific user/customer |
| `phone` | string | No | Filter by customer phone number |

### Request Examples
```
GET /reports/daily
GET /reports/daily?date=2025-10-24
GET /reports/daily?user_id=2
GET /reports/daily?user_id=2&date=2025-10-24
GET /reports/daily?device_id=15
GET /reports/daily?phone=9102318033
```

### Response (200 OK)
```json
{
  "success": true,
  "reportType": "daily",
  "date": "2025-10-24",
  "summary": {
    "currentPower": 960,
    "dailyConsumption": 9.8,
    "totalRuntime": 480,
    "totalWater": 24000,
    "totalCost": 58.80,
    "motorCycles": 12,
    "averageRuntime": 40.0,
    "unitPrice": 6.00,
    "pumpingCapacity": 50
  },
  "hourlyData": [
    {
      "hour": "00:00",
      "energy": 0.0,
      "power": 0.0,
      "runtime": 0,
      "water": 0,
      "cost": 0.0,
      "cycles": 0
    },
    {
      "hour": "06:00",
      "energy": 0.518,
      "power": 1.035,
      "runtime": 30,
      "water": 1500,
      "cost": 3.11,
      "cycles": 1
    }
  ],
  "deviceWiseBreakdown": [
    {
      "deviceId": 9,
      "deviceName": "Home Pump",
      "smsNumber": "+919999999991",
      "energy": 9.8,
      "runtime": 480,
      "water": 24000,
      "cost": 58.80,
      "cycles": 12
    }
  ],
  "device": null,
  "customer": {
    "id": 2,
    "name": "Roushan Kumar",
    "phoneNumber": "9102318033",
    "unitPrice": 6.00,
    "pumpingCapacity": 50
  }
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `success` | boolean | Request status |
| `reportType` | string | "daily" |
| `date` | string | Report date (YYYY-MM-DD) |
| `summary.currentPower` | integer | Current motor power in Watts |
| `summary.dailyConsumption` | float | Total energy in kWh |
| `summary.totalRuntime` | integer | Total runtime in minutes |
| `summary.totalWater` | integer | Total water pumped in liters |
| `summary.totalCost` | float | Total electricity cost in ₹ |
| `summary.motorCycles` | integer | Number of motor cycles |
| `summary.averageRuntime` | float | Average runtime per cycle in minutes |
| `summary.unitPrice` | float | Electricity rate per kWh |
| `summary.pumpingCapacity` | integer | Pump capacity in L/min |
| `hourlyData` | array | 24 hourly breakdown objects |
| `deviceWiseBreakdown` | array/null | Device-wise breakdown (only when querying by user_id/phone without device_id) |
| `device` | object/null | Device info (when filtered by device_id) |
| `customer` | object/null | Customer info (when filtered by user_id/phone) |

---

## 2. Monthly Report

Get monthly motor consumption report with daily breakdown.

### Endpoint
```
GET /reports/monthly
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `month` | integer | No | Month (1-12, default: current month) |
| `year` | integer | No | Year (default: current year) |
| `device_id` | integer | No | Filter by specific device |
| `user_id` | integer | No | Filter by specific user/customer |
| `phone` | string | No | Filter by customer phone number |

### Request Examples
```
GET /reports/monthly
GET /reports/monthly?month=10&year=2025
GET /reports/monthly?user_id=2
GET /reports/monthly?month=10&year=2025&user_id=2
GET /reports/monthly?device_id=15
GET /reports/monthly?phone=9102318033
```

### Response (200 OK)
```json
{
  "success": true,
  "reportType": "monthly",
  "month": 10,
  "year": 2025,
  "monthName": "October",
  "summary": {
    "monthlyConsumption": 234.3,
    "totalRuntime": 11520,
    "totalWater": 576000,
    "totalCost": 1405.80,
    "motorCycles": 156,
    "averageDailyConsumption": 7.56,
    "averageDailyRuntime": 372,
    "unitPrice": 6.00,
    "pumpingCapacity": 50
  },
  "dailyData": [
    {
      "date": "2025-10-01",
      "day": 1,
      "energy": 8.5,
      "runtime": 420,
      "water": 21000,
      "cost": 51.00,
      "cycles": 5
    },
    {
      "date": "2025-10-24",
      "day": 24,
      "energy": 15.2,
      "runtime": 720,
      "water": 36000,
      "cost": 91.20,
      "cycles": 8
    }
  ],
  "deviceWiseBreakdown": [
    {
      "deviceId": 9,
      "deviceName": "Home Pump",
      "smsNumber": "+919999999991",
      "energy": 150.0,
      "runtime": 7200,
      "water": 360000,
      "cost": 900.00,
      "cycles": 96
    },
    {
      "deviceId": 15,
      "deviceName": "Farm Pump",
      "smsNumber": "+919999999992",
      "energy": 84.3,
      "runtime": 4320,
      "water": 216000,
      "cost": 505.80,
      "cycles": 60
    }
  ],
  "device": null,
  "customer": {
    "id": 2,
    "name": "Roushan Kumar",
    "phoneNumber": "9102318033",
    "unitPrice": 6.00,
    "pumpingCapacity": 50
  }
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `summary.monthlyConsumption` | float | Total energy in kWh for the month |
| `summary.totalRuntime` | integer | Total runtime in minutes |
| `summary.totalWater` | integer | Total water in liters |
| `summary.totalCost` | float | Total cost in ₹ |
| `summary.motorCycles` | integer | Total motor cycles |
| `summary.averageDailyConsumption` | float | Average energy per day in kWh |
| `summary.averageDailyRuntime` | integer | Average runtime per day in minutes |
| `dailyData` | array | Daily breakdown (up to 31 days) |
| `deviceWiseBreakdown` | array/null | Device-wise breakdown (when applicable) |

---

## 3. Yearly Report

Get yearly motor consumption report with monthly breakdown.

### Endpoint
```
GET /reports/yearly
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `year` | integer | No | Year (default: current year) |
| `device_id` | integer | No | Filter by specific device |
| `user_id` | integer | No | Filter by specific user/customer |
| `phone` | string | No | Filter by customer phone number |

### Request Examples
```
GET /reports/yearly
GET /reports/yearly?year=2025
GET /reports/yearly?user_id=2
GET /reports/yearly?year=2025&user_id=2
GET /reports/yearly?device_id=15
```

### Response (200 OK)
```json
{
  "success": true,
  "reportType": "yearly",
  "year": 2025,
  "summary": {
    "annualConsumption": 2800.0,
    "totalRuntime": 138240,
    "totalWater": 6912000,
    "totalCost": 16800.00,
    "motorCycles": 1872,
    "averageMonthlyConsumption": 233.33,
    "averageMonthlyRuntime": 11520,
    "unitPrice": 6.00,
    "pumpingCapacity": 50
  },
  "monthlyData": [
    {
      "month": 1,
      "monthName": "January",
      "energy": 0.0,
      "runtime": 0,
      "water": 0,
      "cost": 0.0,
      "cycles": 0
    },
    {
      "month": 10,
      "monthName": "October",
      "energy": 473.0,
      "runtime": 22680,
      "water": 1134000,
      "cost": 2838.00,
      "cycles": 312
    }
  ],
  "deviceWiseBreakdown": [
    {
      "deviceId": 9,
      "deviceName": "Home Pump",
      "smsNumber": "+919999999991",
      "energy": 1680.0,
      "runtime": 82800,
      "water": 4140000,
      "cost": 10080.00,
      "cycles": 1120
    },
    {
      "deviceId": 15,
      "deviceName": "Farm Pump",
      "smsNumber": "+919999999992",
      "energy": 1120.0,
      "runtime": 55440,
      "water": 2772000,
      "cost": 6720.00,
      "cycles": 752
    }
  ],
  "device": null,
  "customer": {
    "id": 2,
    "name": "Roushan Kumar",
    "phoneNumber": "9102318033",
    "unitPrice": 6.00,
    "pumpingCapacity": 50
  }
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `summary.annualConsumption` | float | Total energy in kWh for the year |
| `summary.totalRuntime` | integer | Total runtime in minutes |
| `summary.totalWater` | integer | Total water in liters |
| `summary.totalCost` | float | Total cost in ₹ |
| `summary.motorCycles` | integer | Total motor cycles |
| `summary.averageMonthlyConsumption` | float | Average energy per month in kWh |
| `summary.averageMonthlyRuntime` | integer | Average runtime per month in minutes |
| `monthlyData` | array | Monthly breakdown (12 months) |
| `deviceWiseBreakdown` | array/null | Device-wise breakdown (when applicable) |

---

## 4. Custom Range Report

Get motor consumption report for custom date range.

### Endpoint
```
GET /reports/custom
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `start_date` | string | **Yes** | Start date (YYYY-MM-DD) |
| `end_date` | string | **Yes** | End date (YYYY-MM-DD) |
| `device_id` | integer | No | Filter by specific device |
| `user_id` | integer | No | Filter by specific user/customer |
| `phone` | string | No | Filter by customer phone number |
| `group_by` | string | No | Grouping: 'hour', 'day', 'month' (default: 'day') |

### Request Examples
```
GET /reports/custom?start_date=2025-10-01&end_date=2025-10-31
GET /reports/custom?start_date=2025-10-01&end_date=2025-10-31&user_id=2
GET /reports/custom?start_date=2025-10-01&end_date=2025-10-07&group_by=day
GET /reports/custom?start_date=2025-10-01&end_date=2025-10-31&device_id=15
```

### Response (200 OK)
```json
{
  "success": true,
  "reportType": "custom",
  "startDate": "2025-10-01",
  "endDate": "2025-10-31",
  "dateRange": "01/10/2025 ~ 31/10/2025",
  "groupBy": "day",
  "summary": {
    "totalConsumption": 1500.0,
    "totalRuntime": 69120,
    "totalWater": 3456000,
    "totalCost": 9000.00,
    "motorCycles": 936,
    "totalDays": 31,
    "averageDailyConsumption": 48.39,
    "averageDailyRuntime": 2230,
    "unitPrice": 6.00,
    "pumpingCapacity": 50
  },
  "data": [
    {
      "date": "2025-10-01",
      "energy": 9.8,
      "runtime": 480,
      "water": 24000,
      "cost": 58.80,
      "cycles": 12
    },
    {
      "date": "2025-10-02",
      "energy": 12.5,
      "runtime": 600,
      "water": 30000,
      "cost": 75.00,
      "cycles": 15
    }
  ],
  "deviceWiseBreakdown": [
    {
      "deviceId": 9,
      "deviceName": "Home Pump",
      "smsNumber": "+919999999991",
      "energy": 900.0,
      "runtime": 41472,
      "water": 2073600,
      "cost": 5400.00,
      "cycles": 562
    },
    {
      "deviceId": 15,
      "deviceName": "Farm Pump",
      "smsNumber": "+919999999992",
      "energy": 600.0,
      "runtime": 27648,
      "water": 1382400,
      "cost": 3600.00,
      "cycles": 374
    }
  ],
  "device": null,
  "customer": {
    "id": 2,
    "name": "Roushan Kumar",
    "phoneNumber": "9102318033",
    "unitPrice": 6.00,
    "pumpingCapacity": 50
  }
}
```

### Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `summary.totalConsumption` | float | Total energy in kWh |
| `summary.totalRuntime` | integer | Total runtime in minutes |
| `summary.totalWater` | integer | Total water in liters |
| `summary.totalCost` | float | Total cost in ₹ |
| `summary.motorCycles` | integer | Total motor cycles |
| `summary.totalDays` | integer | Total days in range |
| `summary.averageDailyConsumption` | float | Average energy per day in kWh |
| `summary.averageDailyRuntime` | integer | Average runtime per day in minutes |
| `data` | array | Grouped data based on group_by parameter |
| `deviceWiseBreakdown` | array/null | Device-wise breakdown (when applicable) |

---

## 5. Phone Validation

Validate if a phone number is registered with the motor control system.

### Endpoint
```
GET /validate-phone
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `phone` | string | **Yes** | Phone number to validate |

### Request Examples
```
GET /validate-phone?phone=9102318033
GET /validate-phone?phone=9999999999
```

### Response - Registered (200 OK)
```json
{
  "success": true,
  "message": "Phone number is registered",
  "isRegistered": true
}
```

### Response - Not Registered (404 Not Found)
```json
{
  "success": false,
  "message": "This number is not registered with our motor control system. Please choose a registered number.",
  "isRegistered": false
}
```

### Response - Missing Parameter (400 Bad Request)
```json
{
  "success": false,
  "message": "Phone number is required",
  "isRegistered": false
}
```

---

## Response Structure

### Device-Wise Breakdown

When querying by `user_id` or `phone` **without** `device_id` filter, the response includes `deviceWiseBreakdown`:

```json
"deviceWiseBreakdown": [
  {
    "deviceId": 9,
    "deviceName": "Home Pump",
    "smsNumber": "+919999999991",
    "energy": 9.8,
    "runtime": 480,
    "water": 24000,
    "cost": 58.80,
    "cycles": 12
  }
]
```

**When is it included?**

| Query Filter | deviceWiseBreakdown Included? |
|--------------|-------------------------------|
| `user_id=2` | ✅ YES |
| `phone=9102318033` | ✅ YES |
| `device_id=15` | ❌ NO (already filtered) |
| `user_id=2&device_id=15` | ❌ NO (already filtered) |

### Units

| Metric | Unit | Field Name |
|--------|------|------------|
| Energy | kWh | `energy`, `dailyConsumption`, etc. |
| Power | W (Watts) | `power`, `currentPower` |
| Runtime | minutes | `runtime`, `totalRuntime` |
| Water | liters | `water`, `totalWater` |
| Cost | ₹ (Rupees) | `cost`, `totalCost` |
| Unit Price | ₹/kWh | `unitPrice` |
| Pumping Capacity | L/min | `pumpingCapacity` |

---

## Error Codes

| HTTP Code | Description |
|-----------|-------------|
| 200 | Success |
| 400 | Bad Request (invalid parameters) |
| 404 | Not Found (resource doesn't exist) |
| 500 | Internal Server Error |

### Error Response Format
```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

---

## Notes

### Calculations

- **Energy (kWh)** = (Voltage × Current / 1000) × (Runtime / 3600)
- **Cost (₹)** = Energy × User's Unit Price
- **Water (L)** = (Runtime / 60) × User's Pumping Capacity

### User Settings

Each user has personalized settings:
- `unit_price`: Electricity cost per kWh (default: ₹6.00)
- `motor_pumping_capacity`: Pump flow rate in L/min (default: 50)

### Timestamps

- All dates in **YYYY-MM-DD** format
- Times in **HH:MM** format (24-hour)
- Timezone: Server timezone

### Performance

- Response time: < 1 second
- Recommended: Cache reports for 5-15 minutes
- All responses in JSON format

---

## Quick Reference

```
Base URL: https://laravel1.wizzyweb.com/api

Daily:     GET /reports/daily?user_id={id}&date={YYYY-MM-DD}
Monthly:   GET /reports/monthly?user_id={id}&month={1-12}&year={YYYY}
Yearly:    GET /reports/yearly?user_id={id}&year={YYYY}
Custom:    GET /reports/custom?user_id={id}&start_date={YYYY-MM-DD}&end_date={YYYY-MM-DD}
Validate:  GET /validate-phone?phone={phone_number}
```

---

**API Version:** 1.0.0  
**Last Updated:** November 2, 2025  
**Status:** ✅ Live & Production Ready

