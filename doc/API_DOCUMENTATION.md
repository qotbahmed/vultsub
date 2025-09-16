# API Documentation - Vult Subscription System

## Overview
This document provides comprehensive API documentation for the Vult Subscription System, including trial management, academy requests, and user management endpoints.

## Base URL
```
http://vult-sub.localhost/api
```

## Authentication
All API endpoints require authentication using Bearer tokens or API keys.

## Trial Management API

### Start Trial
**POST** `/trial/start`

Start a trial period for a user.

**Request Body:**
```json
{
    "user_id": 123,
    "days": 7,
    "academy_id": 456
}
```

**Response:**
```json
{
    "success": true,
    "message": "Trial started successfully",
    "data": {
        "trial_started_at": 1640995200,
        "trial_expires_at": 1641600000,
        "days_left": 7
    }
}
```

### Check Trial Status
**GET** `/trial/status/{user_id}`

Check the trial status for a specific user.

**Response:**
```json
{
    "success": true,
    "data": {
        "is_trial": true,
        "is_expired": false,
        "days_left": 5,
        "trial_started_at": 1640995200,
        "trial_expires_at": 1641600000
    }
}
```

### End Trial
**POST** `/trial/end`

End a trial period for a user.

**Request Body:**
```json
{
    "user_id": 123
}
```

**Response:**
```json
{
    "success": true,
    "message": "Trial ended successfully"
}
```

## Academy Requests API

### Create Academy Request
**POST** `/academy-requests`

Create a new academy request.

**Request Body:**
```json
{
    "academy_name": "Al-Nassr Academy",
    "manager_name": "Ahmed Al-Rashid",
    "email": "manager@alnassr.com",
    "phone": "+966501234567",
    "address": "Riyadh, Saudi Arabia",
    "city": "Riyadh",
    "branches_count": 3,
    "sports": "football,basketball",
    "description": "Professional sports academy"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Academy request created successfully",
    "data": {
        "id": 1,
        "status": "pending",
        "requested_at": 1640995200
    }
}
```

### Get Academy Requests
**GET** `/academy-requests`

Get all academy requests with optional filtering.

**Query Parameters:**
- `status` - Filter by status (pending, approved, rejected, expired)
- `page` - Page number for pagination
- `limit` - Number of items per page

**Response:**
```json
{
    "success": true,
    "data": {
        "requests": [
            {
                "id": 1,
                "academy_name": "Al-Nassr Academy",
                "manager_name": "Ahmed Al-Rashid",
                "email": "manager@alnassr.com",
                "phone": "+966501234567",
                "status": "pending",
                "requested_at": 1640995200
            }
        ],
        "pagination": {
            "page": 1,
            "limit": 10,
            "total": 1,
            "pages": 1
        }
    }
}
```

### Approve Academy Request
**PUT** `/academy-requests/{id}/approve`

Approve an academy request.

**Request Body:**
```json
{
    "notes": "Approved after review"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Academy request approved successfully",
    "data": {
        "id": 1,
        "status": "approved",
        "approved_at": 1640995200
    }
}
```

### Reject Academy Request
**PUT** `/academy-requests/{id}/reject`

Reject an academy request.

**Request Body:**
```json
{
    "notes": "Incomplete documentation"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Academy request rejected",
    "data": {
        "id": 1,
        "status": "rejected",
        "rejected_at": 1640995200
    }
}
```

## User Management API

### Get User by Email
**GET** `/users/email/{email}`

Get user information by email address.

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 123,
        "username": "ahmed_rashid",
        "email": "ahmed@example.com",
        "status": "active",
        "trial_started_at": 1640995200,
        "trial_expires_at": 1641600000,
        "academy_id": 456
    }
}
```

### Update User Trial
**PUT** `/users/{id}/trial`

Update user trial information.

**Request Body:**
```json
{
    "trial_started_at": 1640995200,
    "trial_expires_at": 1641600000,
    "academy_id": 456
}
```

**Response:**
```json
{
    "success": true,
    "message": "User trial updated successfully"
}
```

## Error Responses

All API endpoints return consistent error responses:

```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "Invalid email format",
        "details": {
            "field": "email",
            "value": "invalid-email"
        }
    }
}
```

## Status Codes

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

## Rate Limiting

API requests are rate limited to:
- 100 requests per minute per IP
- 1000 requests per hour per authenticated user

## Examples

### cURL Examples

**Start Trial:**
```bash
curl -X POST http://vult-sub.localhost/api/trial/start \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"user_id": 123, "days": 7, "academy_id": 456}'
```

**Create Academy Request:**
```bash
curl -X POST http://vult-sub.localhost/api/academy-requests \
  -H "Content-Type: application/json" \
  -d '{
    "academy_name": "Al-Nassr Academy",
    "manager_name": "Ahmed Al-Rashid",
    "email": "manager@alnassr.com",
    "phone": "+966501234567",
    "address": "Riyadh, Saudi Arabia",
    "city": "Riyadh",
    "branches_count": 3,
    "sports": "football,basketball",
    "description": "Professional sports academy"
  }'
```

## SDK Examples

### PHP
```php
$client = new VultApiClient('http://vult-sub.localhost/api', 'YOUR_TOKEN');

// Start trial
$response = $client->post('/trial/start', [
    'user_id' => 123,
    'days' => 7,
    'academy_id' => 456
]);

// Get trial status
$status = $client->get('/trial/status/123');
```

### JavaScript
```javascript
const api = new VultApiClient('http://vult-sub.localhost/api', 'YOUR_TOKEN');

// Start trial
const trial = await api.post('/trial/start', {
    user_id: 123,
    days: 7,
    academy_id: 456
});

// Get trial status
const status = await api.get('/trial/status/123');
```

---

**Last Updated:** 2024-01-15  
**Version:** 1.0  
**Maintainer:** Vult Development Team
