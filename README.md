# 📌 Sketchit API

A RESTful API built with Laravel for managing users, posts, comments, likes, follows, and notifications.

**Sketchit** was a social platform built for sharing urban sketching artwork — a space where artists could post, discover, and engage with city-inspired drawings from around the world.

The application focused on simplicity, community interaction, and visual storytelling.

---

## 🌐 Base URL

`/api`

---

## 🔐 Authentication

This API uses token-based authentication via `auth:api`.

For protected routes, include:

```
Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json
```

---

# 🔑 Authentication Endpoints

## Register

`POST /api/register`

Create a new user account.

### Body

```json
{
  "name": "John Doe",
  "email": "john@email.com",
  "password": "password",
  "password_confirmation": "password"
}
```

---

## Login

`POST /api/login`

### Body

```json
{
  "email": "john@email.com",
  "password": "password"
}
```

Returns authentication token.

---

## Logout

`POST /api/logout`  
🔒 Requires Authentication

---

# 📝 Posts

## Get All Posts

`GET /api/posts`

## Get Single Post

`GET /api/post/{post}`

## Get Post Comments

`GET /api/post/{post}/comments`

## Create Post

`POST /api/post`  
🔒 Requires Authentication

### Body

```json
{
  "title": "Post title",
  "body": "Post content"
}
```

## Edit Post

`PATCH /api/post/{post}`  
🔒 Requires Authentication

## Delete Post

`DELETE /api/post/{post}`  
🔒 Requires Authentication

## Like Post

`POST /api/post/{post}/like`  
🔒 Requires Authentication

## Unlike Post

`POST /api/post/{post}/unlike`  
🔒 Requires Authentication

## Get Post Likes

`GET /api/post/{post}/likes`  
🔒 Requires Authentication

## Leave Comment on Post

`POST /api/post/{post}/comment`  
🔒 Requires Authentication

### Body

```json
{
  "body": "Nice post!"
}
```

---

# 💬 Comments

## Get All Comments

`GET /api/comments`  
🔒 Requires Authentication

## Get Single Comment

`GET /api/comment/{comment}`

## Get Comment Replies

`GET /api/comment/{comment}/replies`

## Edit Comment

`PATCH /api/comment/{comment}`  
🔒 Requires Authentication

## Delete Comment

`DELETE /api/comment/{comment}`  
🔒 Requires Authentication

## Like Comment

`POST /api/comment/{comment}/like`  
🔒 Requires Authentication

## Unlike Comment

`POST /api/comment/{comment}/unlike`  
🔒 Requires Authentication

## Reply to Comment

`POST /api/comment/{comment}/reply`  
🔒 Requires Authentication

### Body

```json
{
  "body": "Reply text"
}
```

---

# 👤 Users

## Get Authenticated User

`GET /api/user`  
🔒 Requires Authentication

## Get All Users

`GET /api/users`  
🔒 Requires Authentication

## Get User by ID

`GET /api/user/{user}`

## Get User by Username

`GET /api/user/by/username/{user_name}`

## Get User Posts

`GET /api/user/{user}/posts`

## Get User Comments

`GET /api/user/{user}/comments`  
🔒 Requires Authentication

## Follow User

`POST /api/user/{user}/follow`  
🔒 Requires Authentication

## Unfollow User

`DELETE /api/user/{user}/unfollow`  
🔒 Requires Authentication

## Get User Follows

`GET /api/user/{user}/follows`  
🔒 Requires Authentication

## Get User Followers

`GET /api/user/{user}/followers`  
🔒 Requires Authentication

## Get Common Follows

`GET /api/user/{user}/common_follows`  
🔒 Requires Authentication

---

# 🔔 Notifications

## Get Notifications

`GET /api/notifications`  
🔒 Requires Authentication

## Mark All Notifications as Read

`POST /api/mark_all_notifications_as_read`  
🔒 Requires Authentication

## Mark Single Notification as Read

`POST /api/mark_notification_as_read/{notification}`  
🔒 Requires Authentication

---

# 📂 Categories

## Get All Categories

`GET /api/categories`

---

# 🛠 Tech Stack

- PHP
- Laravel
- RESTful API
- Token-based Authentication
