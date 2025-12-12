# Account Deletion API Documentation

## DELETE /api/customer/account

Delete customer account permanently. This endpoint requires authentication and password verification.

### Authentication
- **Required:** Yes (Bearer Token via Sanctum)
- **Middleware:** `auth:sanctum`, `customer`

### Request Body

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `password` | string | Yes | Customer's current password |
| `confirmation` | string | Yes | Must be exactly: "DELETE MY ACCOUNT" |

### Request Example

```json
{
  "password": "your_password_here",
  "confirmation": "DELETE MY ACCOUNT"
}
```

### Success Response (200)

```json
{
  "success": true,
  "message": "Account deleted successfully"
}
```

### Validation Error Response (422)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "password": ["The password field is required."],
    "confirmation": ["The confirmation must be DELETE MY ACCOUNT."]
  }
}
```

### Invalid Password Response (401)

```json
{
  "success": false,
  "message": "Invalid password"
}
```

### Server Error Response (500)

```json
{
  "success": false,
  "message": "Failed to delete account",
  "error": "Error message details"
}
```

### What Gets Deleted

When an account is deleted, the following actions are performed:

1. **All API tokens** are revoked (all devices logged out)
2. **Profile photo** is deleted from storage (if exists)
3. **All devices** are unassigned (user_id set to null)
4. **All notifications** related to the user are deleted
5. **User account** is permanently deleted from database

### Important Notes

- ⚠️ **This action is irreversible**
- ⚠️ All data associated with the account will be permanently deleted
- ⚠️ Devices will remain in the system but will be unassigned
- ⚠️ Motor logs will remain in the system (for historical data)
- After successful deletion, the user should be redirected to login screen

### Mobile App Implementation

After successful account deletion:

1. Clear all local data (tokens, user info, etc.)
2. Navigate to login screen
3. Show success message: "Account successfully deleted"

### Example cURL Request

```bash
curl -X DELETE https://your-domain.com/api/customer/account \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "password": "user_password",
    "confirmation": "DELETE MY ACCOUNT"
  }'
```

### Example JavaScript/Fetch

```javascript
const deleteAccount = async (password) => {
  const token = localStorage.getItem('auth_token');
  
  try {
    const response = await fetch('/api/customer/account', {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        password: password,
        confirmation: 'DELETE MY ACCOUNT'
      })
    });
    
    const data = await response.json();
    
    if (data.success) {
      // Clear local storage
      localStorage.clear();
      // Navigate to login
      window.location.href = '/login';
    } else {
      console.error('Error:', data.message);
    }
  } catch (error) {
    console.error('Request failed:', error);
  }
};
```

---

## Web Frontend

### Delete Account Form

**URL:** `/customer/delete-account`  
**Method:** GET (form) / POST (submit)

### Form Fields

1. **Phone Number** (required)
   - Input type: text
   - Placeholder: "Phone Number"

2. **Password** (required)
   - Input type: password
   - Placeholder: "Password"

3. **Confirmation** (required)
   - Input type: text
   - Placeholder: "Type: DELETE MY ACCOUNT"
   - Must match exactly: "DELETE MY ACCOUNT"

### Success Page

**URL:** `/customer/account-deleted`  
**Method:** GET

After successful deletion, users are redirected to this page which shows:
- Success message: "Account Successfully Deleted"
- Confirmation that all data has been removed
- Link to login page

### Web Form Example

```html
<form method="POST" action="/customer/delete-account">
    @csrf
    <input type="text" name="phone_number" placeholder="Phone Number" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="confirmation" placeholder="Type: DELETE MY ACCOUNT" required>
    <button type="submit">Delete My Account</button>
</form>
```

---

## Play Store Compliance

This account deletion feature meets Play Store requirements:

✅ Users can delete their accounts  
✅ Clear confirmation process (password + text confirmation)  
✅ Permanent deletion of user data  
✅ Success confirmation after deletion  
✅ Redirect to login after deletion  

---

## Security Considerations

- Password verification required
- Explicit confirmation text required
- All tokens revoked immediately
- Profile photos deleted from storage
- Devices unassigned (not deleted, preserving device history)
- Notifications deleted
- User account permanently removed

