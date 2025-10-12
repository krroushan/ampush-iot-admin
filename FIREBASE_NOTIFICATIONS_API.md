# 🔥 **FIREBASE PUSH NOTIFICATIONS API DOCUMENTATION**

## **Overview**
This API allows you to send push notifications to IoT customers using Firebase Cloud Messaging (FCM). The system supports sending notifications to all customers, specific customers, or customers by phone numbers.

---

## **🔐 Authentication**
All admin notification endpoints require authentication using Laravel Sanctum Bearer token.

```bash
Authorization: Bearer {your_admin_token}
```

---

## **📱 Customer FCM Token Management**

### **Update FCM Token**
**Endpoint:** `POST /api/customer/fcm-token`  
**Authentication:** Required (Customer)  
**Description:** Allows customers to update their FCM token for receiving push notifications.

#### **Request Body:**
```json
{
    "fcm_token": "string (required, max:255)"
}
```

#### **Response:**
```json
{
    "success": true,
    "message": "FCM token updated successfully",
    "data": {
        "user_id": 123,
        "fcm_token": "fcm_token_here"
    }
}
```

#### **cURL Example:**
```bash
curl -X POST "https://laravel.wizzyweb.com/api/customer/fcm-token" \
  -H "Authorization: Bearer {customer_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "fcm_token": "fcm_token_from_mobile_app"
  }'
```

---

## **🔔 Admin Notification APIs**

### **1. Send to All Customers**
**Endpoint:** `POST /api/admin/notifications/send-all`  
**Authentication:** Required (Admin)  
**Description:** Send push notification to all customers who have FCM tokens.

#### **Request Body:**
```json
{
    "title": "string (required, max:255)",
    "body": "string (required, max:1000)",
    "data": {
        "action": "string (optional)",
        "device_id": "string (optional)",
        "custom_field": "value (optional)"
    }
}
```

#### **Response:**
```json
{
    "success": true,
    "message": "Notification sent successfully",
    "data": {
        "sent_count": 15,
        "failure_count": 0,
        "total_customers": 15
    }
}
```

#### **cURL Example:**
```bash
curl -X POST "https://laravel.wizzyweb.com/api/admin/notifications/send-all" \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "System Maintenance",
    "body": "The IoT system will be under maintenance from 2:00 AM to 4:00 AM today.",
    "data": {
        "action": "maintenance_alert",
        "scheduled_time": "2025-10-05T02:00:00Z"
    }
  }'
```

---

### **2. Send to Specific Customer**
**Endpoint:** `POST /api/admin/notifications/send-customer/{customerId}`  
**Authentication:** Required (Admin)  
**Description:** Send push notification to a specific customer by their ID.

#### **Request Body:**
```json
{
    "title": "string (required, max:255)",
    "body": "string (required, max:1000)",
    "data": {
        "action": "string (optional)",
        "device_id": "string (optional)"
    }
}
```

#### **Response:**
```json
{
    "success": true,
    "message": "Notification sent successfully",
    "data": {
        "customer_id": 123,
        "customer_name": "John Doe"
    }
}
```

#### **cURL Example:**
```bash
curl -X POST "https://laravel.wizzyweb.com/api/admin/notifications/send-customer/123" \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Device Alert",
    "body": "Your water pump device has been running for 2 hours. Please check.",
    "data": {
        "action": "device_alert",
        "device_id": "DEVICE_001",
        "alert_type": "long_running"
    }
  }'
```

---

### **3. Send by Phone Numbers**
**Endpoint:** `POST /api/admin/notifications/send-by-phone`  
**Authentication:** Required (Admin)  
**Description:** Send push notification to customers by their phone numbers.

#### **Request Body:**
```json
{
    "phone_numbers": [
        "+1234567890",
        "+0987654321"
    ],
    "title": "string (required, max:255)",
    "body": "string (required, max:1000)",
    "data": {
        "action": "string (optional)"
    }
}
```

#### **Response:**
```json
{
    "success": true,
    "message": "Notification sent successfully",
    "data": {
        "sent_count": 2,
        "failure_count": 0,
        "customers_found": 2,
        "phone_numbers": ["+1234567890", "+0987654321"]
    }
}
```

#### **cURL Example:**
```bash
curl -X POST "https://laravel.wizzyweb.com/api/admin/notifications/send-by-phone" \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "phone_numbers": ["+1234567890", "+0987654321"],
    "title": "Emergency Alert",
    "body": "High water level detected in your area. Please take necessary precautions.",
    "data": {
        "action": "emergency_alert",
        "alert_level": "high"
    }
  }'
```

---

### **4. Get Customers with FCM Tokens**
**Endpoint:** `GET /api/admin/notifications/customers`  
**Authentication:** Required (Admin)  
**Description:** Get list of customers who have FCM tokens registered.

#### **Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 123,
            "name": "John Doe",
            "phone_number": "+1234567890",
            "email": "john@example.com",
            "fcm_token": "fcm_token_here"
        }
    ],
    "count": 15
}
```

#### **cURL Example:**
```bash
curl -X GET "https://laravel.wizzyweb.com/api/admin/notifications/customers" \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json"
```

---

## **📱 Mobile App Integration**

### **Android Integration**

#### **1. Add Firebase to Android Project**
```gradle
// build.gradle (Project level)
buildscript {
    dependencies {
        classpath 'com.google.gms:google-services:4.3.15'
    }
}

// build.gradle (App level)
apply plugin: 'com.google.gms.google-services'

dependencies {
    implementation 'com.google.firebase:firebase-messaging:23.1.2'
    implementation 'com.google.firebase:firebase-analytics:21.2.0'
}
```

#### **2. Firebase Service Class**
```kotlin
class MyFirebaseMessagingService : FirebaseMessagingService() {
    
    override fun onMessageReceived(remoteMessage: RemoteMessage) {
        super.onMessageReceived(remoteMessage)
        
        val notification = remoteMessage.notification
        if (notification != null) {
            showNotification(
                notification.title ?: "IoT Admin",
                notification.body ?: "New notification"
            )
        }
    }
    
    private fun showNotification(title: String, body: String) {
        val intent = Intent(this, MainActivity::class.java)
        val pendingIntent = PendingIntent.getActivity(
            this, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT
        )
        
        val notification = NotificationCompat.Builder(this, "default")
            .setContentTitle(title)
            .setContentText(body)
            .setSmallIcon(R.drawable.ic_notification)
            .setContentIntent(pendingIntent)
            .setAutoCancel(true)
            .build()
        
        val notificationManager = getSystemService(Context.NOTIFICATION_SERVICE) as NotificationManager
        notificationManager.notify(0, notification)
    }
}
```

#### **3. Register FCM Token**
```kotlin
// In your MainActivity or Application class
FirebaseMessaging.getInstance().token.addOnCompleteListener { task ->
    if (!task.isSuccessful) {
        Log.w("FCM", "Fetching FCM registration token failed", task.exception)
        return@addOnCompleteListener
    }
    
    val token = task.result
    Log.d("FCM", "FCM Registration Token: $token")
    
    // Send token to your server
    sendTokenToServer(token)
}

private fun sendTokenToServer(token: String) {
    val apiService = ApiService()
    apiService.updateFCMToken(token)
}
```

#### **4. API Service Method**
```kotlin
class ApiService {
    fun updateFCMToken(token: String) {
        val request = Request.Builder()
            .url("https://laravel.wizzyweb.com/api/customer/fcm-token")
            .post(RequestBody.create(
                MediaType.parse("application/json"),
                JSONObject().put("fcm_token", token).toString()
            ))
            .addHeader("Authorization", "Bearer $authToken")
            .addHeader("Content-Type", "application/json")
            .build()
        
        // Execute request...
    }
}
```

---

## **🎯 Notification Types & Use Cases**

### **1. System Notifications**
```json
{
    "title": "System Update",
    "body": "New features have been added to your IoT dashboard.",
    "data": {
        "action": "system_update",
        "version": "2.1.0"
    }
}
```

### **2. Device Alerts**
```json
{
    "title": "Device Alert",
    "body": "Water pump has been running for 2 hours. Please check.",
    "data": {
        "action": "device_alert",
        "device_id": "PUMP_001",
        "alert_type": "long_running"
    }
}
```

### **3. Maintenance Notifications**
```json
{
    "title": "Scheduled Maintenance",
    "body": "System maintenance scheduled for tonight 2:00 AM - 4:00 AM.",
    "data": {
        "action": "maintenance",
        "scheduled_time": "2025-10-05T02:00:00Z"
    }
}
```

### **4. Emergency Alerts**
```json
{
    "title": "Emergency Alert",
    "body": "High water level detected. Please take precautions.",
    "data": {
        "action": "emergency",
        "alert_level": "high",
        "location": "Zone A"
    }
}
```

---

## **🔧 Error Handling**

### **Common Error Responses**

#### **401 Unauthorized**
```json
{
    "success": false,
    "message": "Unauthorized"
}
```

#### **422 Validation Error**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "title": ["The title field is required."],
        "body": ["The body field is required."]
    }
}
```

#### **404 Customer Not Found**
```json
{
    "success": false,
    "message": "Customer not found or no FCM token"
}
```

#### **500 Server Error**
```json
{
    "success": false,
    "message": "Failed to send notification",
    "error": "Firebase service unavailable"
}
```

---

## **📊 Testing Notifications**

### **Test with cURL**
```bash
# Test sending to all customers
curl -X POST "https://laravel.wizzyweb.com/api/admin/notifications/send-all" \
  -H "Authorization: Bearer {admin_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Notification",
    "body": "This is a test notification from the admin panel."
  }'
```

### **Test with Postman**
1. Import the API collection
2. Set up authentication headers
3. Test each endpoint with sample data

---

## **🚀 Production Deployment**

### **Environment Variables**
Make sure these are set in your production environment:
```env
FIREBASE_PROJECT_ID=ampush-iot
FIREBASE_PRIVATE_KEY_ID=769a86c714ee7f9ec631f74ba626acda7c18adab
FIREBASE_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n"
FIREBASE_CLIENT_EMAIL=firebase-adminsdk-fbsvc@ampush-iot.iam.gserviceaccount.com
FIREBASE_CLIENT_ID=108047555236117283705
FIREBASE_AUTH_URI=https://accounts.google.com/o/oauth2/auth
FIREBASE_TOKEN_URI=https://oauth2.googleapis.com/token
```

### **Database Migration**
```bash
php artisan migrate
```

### **Clear Cache**
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## **✅ Complete Setup Checklist**

- [x] Firebase project created
- [x] Firebase Admin SDK installed
- [x] Database migrations run
- [x] API endpoints created
- [x] Admin panel form created
- [x] Customer FCM token update endpoint
- [x] Mobile app integration guide
- [x] API documentation created

**🎉 Your Firebase push notification system is ready to use!**
