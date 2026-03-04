📌 Social API

A RESTful API built with Laravel for managing users, posts, comments, likes, follows, and notifications.

Base URL
/api
Authentication

This API uses token-based authentication via auth:api.

For protected routes, include:

Authorization: Bearer YOUR_TOKEN_HERE
Accept: application/json
🔐 Authentication
Register

POST /register

Create a new user account.

Body
{
  "name": "John Doe",
  "email": "john@email.com",
  "password": "password",
  "password_confirmation": "password"
}
Login

POST /login

Body
{
  "email": "john@email.com",
  "password": "password"
}

Returns authentication token.

Logout

POST /logout
🔒 Requires Authentication

📝 Posts
Get All Posts

GET /posts

Get Single Post

GET /post/{post}

Get Post Comments

GET /post/{post}/comments

Create Post

POST /post
🔒 Requires Authentication

Body
{
  "title": "Post title",
  "body": "Post content"
}
Edit Post

PATCH /post/{post}
🔒 Requires Authentication

Delete Post

DELETE /post/{post}
🔒 Requires Authentication

Like Post

POST /post/{post}/like
🔒 Requires Authentication

Unlike Post

POST /post/{post}/unlike
🔒 Requires Authentication

Get Post Likes

GET /post/{post}/likes
🔒 Requires Authentication

Leave Comment on Post

POST /post/{post}/comment
🔒 Requires Authentication

Body
{
  "body": "Nice post!"
}
💬 Comments
Get All Comments

GET /comments
🔒 Requires Authentication

Get Single Comment

GET /comment/{comment}

Get Comment Replies

GET /comment/{comment}/replies

Edit Comment

PATCH /comment/{comment}
🔒 Requires Authentication

Delete Comment

DELETE /comment/{comment}
🔒 Requires Authentication

Like Comment

POST /comment/{comment}/like
🔒 Requires Authentication

Unlike Comment

POST /comment/{comment}/unlike
🔒 Requires Authentication

Reply to Comment

POST /comment/{comment}/reply
🔒 Requires Authentication

Body
{
  "body": "Reply text"
}
👤 Users
Get Authenticated User

GET /user
🔒 Requires Authentication

Get All Users

GET /users
🔒 Requires Authentication

Get User by ID

GET /user/{user}

Get User by Username

GET /user/by/username/{user_name}

Get User Posts

GET /user/{user}/posts

Get User Comments

GET /user/{user}/comments
🔒 Requires Authentication

Follow User

POST /user/{user}/follow
🔒 Requires Authentication

Unfollow User

DELETE /user/{user}/unfollow
🔒 Requires Authentication

Get User Follows

GET /user/{user}/follows
🔒 Requires Authentication

Get User Followers

GET /user/{user}/followers
🔒 Requires Authentication

Get Common Follows

GET /user/{user}/common_follows
🔒 Requires Authentication

🔔 Notifications
Get Notifications

GET /notifications
🔒 Requires Authentication

Mark All Notifications as Read

POST /mark_all_notifications_as_read
🔒 Requires Authentication

Mark Single Notification as Read

POST /mark_notification_as_read/{notification}
🔒 Requires Authentication

📂 Categories
Get All Categories

GET /categories

🛠 Tech Stack

PHP

Laravel

RESTful API

Token-based Authentication
