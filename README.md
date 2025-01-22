# Book Review Website

This project is a Book Review Website developed using **Laravel**, **PHP**, **Bootstrap**, and a **MySQL** database. The website allows users to add reviews and ratings for books, while also providing an admin role for managing books, reviews, and users.


## Features

### User Features:
- **Add Book Reviews:** Users can submit their reviews and ratings for books.
- **View Reviews:** Users can see all their reviews in a table.
- **Rate Books:** Users can rate books on a scale.
  
### Admin Features:
- **Add, Update, Delete Books:** Admins can manage books in the database.
- **Manage Reviews:** Admins can view and edit or delete reviews submitted by users.
- **User Management:** Admins can manage user accounts.


## Tech Stack

- **Backend**:
  - **Laravel** (PHP Framework) - For building the server-side logic and handling routes, controllers, and database operations.
  
- **Frontend**:
  - **Bootstrap** - For responsive design and user interface components.
  
- **Database**:
  - **MySQL** - For storing book data, user information, reviews, and ratings.

- **Authentication**:
  - **Laravel Authentication** - For user and admin authentication.


## Installation


Make sure you have the following installed on your machine:

- **PHP** (>= 7.4)
- **Composer** (for PHP dependencies)
- **MySQL** (or any compatible database)

### 1. Clone the Repository

First, clone the repository to your local machine using Git:

### 2. Navigate to the Project Directory

```bash
cd book-review-app
```

### 3. Run the following command to install the dependencies:

```bash
composer install
```

### 4. Run this command to update database, make sure mysql xampp is started

```bash
php artisan migrate
```

### 5. Run this for storage for accessing imags

```bash
php artisan storage:link
```

### 6. Start the Laravel server:

```bash
php artisan serve
```

# screeenshots 
![1](/assets/screenshots/2.png)
![1](/assets/screenshots/3.png)
![1](/assets/screenshots/1.png)
![1](/assets/screenshots/4.png)
![1](/assets/screenshots/5.png)
![1](/assets/screenshots/6.png)
![1](/assets/screenshots/7.png)
![1](/assets/screenshots/9.png)



# Video link
![Watch the video demo](/assets/video/video.mp4)


