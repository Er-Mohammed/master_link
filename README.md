# MasterLink Backend

> RESTful API backend for the MasterLink corporate website and administration platform.

MasterLink Backend is a modern, scalable backend system built with **Laravel 13**, **PHP 8.3**, and **MySQL**.

It provides a complete API-driven architecture for managing website content, media, projects, services, client logos, consultations, testimonials, site settings, posts, and administrators.

The backend is designed to work with the MasterLink React frontend while keeping the database as the single source of truth.

---

## 🚀 Technology Stack

| Technology | Version |
|---|---|
| Laravel | 13.x |
| PHP | 8.3+ |
| MySQL | 8.x |
| Laravel Sanctum | Authentication |
| Composer | 2.x |
| REST API | JSON |
| Storage | Laravel Storage |
| Authorization | Roles & Policies |

---

## 🏗️ Architecture

The backend follows a clean API-oriented architecture:

```text
React Frontend
      │
      ▼
Laravel REST API
      │
      ├── Authentication
      ├── Authorization
      ├── Validation
      ├── Resources
      ├── Controllers
      ├── Policies
      └── Services
      │
      ▼
MySQL Database
      │
      ├── Admins
      ├── Services
      ├── Projects
      ├── Project Categories
      ├── Project Media
      ├── Media
      ├── Client Logos
      ├── Consultations
      ├── Testimonials
      ├── Posts
      └── Site Settings

      Features
🔐 Authentication

Admin authentication is implemented using Laravel Sanctum.

Supported operations:

Admin Login
Get Authenticated Admin
Logout
Token Invalidation
Change Password
Secure API Authentication

Authentication endpoints are protected using Sanctum middleware where required.

👥 Role-Based Access Control

The system supports four administrator roles:

super_admin
admin
content_manager
marketing
Role Overview
Role	Description
super_admin	Full system access and administrator management
admin	General administration and content management
content_manager	Content and media management
marketing	Projects, client logos and consultations

Authorization is enforced using Laravel middleware and Policies.

📦 Content Management

The backend provides complete CRUD APIs for the main MasterLink website resources.

Services

Manage:

Service title
Slug
Short description
Full description
Sort order
Active/inactive status
Service media
Projects

Manage:

Project title
Slug
Short description
Full description
Project category
Services
Project media
Sort order
Active/inactive status

Projects support multiple images/media files.

Project Categories

Manage project categories used to organize portfolio projects.

Client Logos

Manage client/company logos displayed on the public website.

Testimonials

Manage customer testimonials and their visibility.

Posts

Manage website/blog content including:

Title
Slug
Short description
Content
Featured status
Publication date
Media
Media

Centralized media management supporting:

Images
Videos
Documents
File metadata
Alt text
File size
MIME type
Media relationships
Site Settings

Manage global website settings such as:

Website logo
Website configuration
Global content
Other configurable site information
Consultations

Manage consultation requests submitted by website visitors.

Supported operations include:

View consultations
Search consultations
Filter consultations
Sort consultations
Update consultation status
Delete consultations

Public visitors can submit consultations without authentication.

🌐 Public API

The backend exposes public endpoints used by the MasterLink website.

Services
GET /api/services

Returns active services.

Projects
GET /api/projects

Returns active projects including categories, services and media.

Client Logos
GET /api/client-logos

Returns active client logos.

Consultations
POST /api/consultations

Allows website visitors to submit consultation requests.

No authentication is required for public consultation submission.

🔑 Admin API

Administrative endpoints are available under:

/api/admin

Examples:

POST   /api/admin/login

GET    /api/admin/me
POST   /api/admin/logout

GET    /api/admin/services
POST   /api/admin/services
GET    /api/admin/services/{id}
PUT    /api/admin/services/{id}
DELETE /api/admin/services/{id}

GET    /api/admin/projects
POST   /api/admin/projects
GET    /api/admin/projects/{id}
PUT    /api/admin/projects/{id}
DELETE /api/admin/projects/{id}

GET    /api/admin/consultations
GET    /api/admin/consultations/{id}
PUT    /api/admin/consultations/{id}
DELETE /api/admin/consultations/{id}

Additional endpoints are available for:

Project Categories
Media
Client Logos
Testimonials
Posts
Site Settings
Administrators
🗄️ Database Structure

The system uses MySQL as the primary database.

Main tables include:

admins
media
services
project_categories
projects
project_media
project_services
client_logos
consultations
testimonials
posts
site_settings
Relationships

Examples:

Project
 ├── belongsTo ProjectCategory
 ├── belongsToMany Services
 └── belongsToMany Media

Service
 ├── hasMany Media
 ├── belongsToMany Projects
 └── hasMany Consultations

ClientLogo
 └── belongsTo Media

Consultation
 └── belongsTo Service

Post
 └── belongsTo Admin
 └── belongsTo Media
🖼️ Media Management

MasterLink uses Laravel Storage for uploaded media.

The backend supports:

Images
├── JPG
├── JPEG
├── PNG
└── WEBP

Videos
├── MP4
├── WEBM
└── MOV

Documents
├── PDF
├── DOC
└── DOCX

After configuring storage, create the public storage link:

php artisan storage:link

Uploaded files are exposed through Laravel's public storage system.

🔍 API Capabilities

Admin listing endpoints support:

Pagination
Searching
Filtering
Sorting
Relationships
Resource transformation

Example:

GET /api/admin/services?page=1&per_page=10

Example search:

GET /api/admin/services?search=web

Example sorting:

GET /api/admin/services?sort=created_at&direction=desc
🛡️ Validation

The backend uses Laravel Form Requests for request validation.

Examples include:

StoreConsultationRequest
UpdateConsultationRequest
StoreServiceRequest
UpdateServiceRequest
...

Validation is performed server-side before database operations.

This prevents invalid data from reaching the database.

🔒 Authorization

Authorization is implemented using:

Sanctum
Role middleware
Laravel Policies
Controller-level authorization

Example:

Request
   ↓
Sanctum Authentication
   ↓
Role Authorization
   ↓
Policy Authorization
   ↓
Controller
   ↓
Database

This ensures that authenticated administrators can only perform operations allowed by their role and permissions.

⚙️ Installation
1. Clone the repository
git clone https://github.com/Er-Mohammed/master_link_backend.git
cd master_link_backend
2. Install PHP dependencies
composer install
3. Create environment file
cp .env.example .env

On Windows PowerShell:

Copy-Item .env.example .env
4. Generate application key
php artisan key:generate
5. Configure the database

Update .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=masterlink
DB_USERNAME=root
DB_PASSWORD=

Adjust the values according to your environment.

🗃️ Run Migrations

Run:

php artisan migrate

For development environments where seed data is required:

php artisan migrate:fresh --seed

Do not use migrate:fresh --seed on a production database unless you intentionally want to delete existing data.

👤 Creating Administrators

Administrators are stored in the admins table.

Available roles:

super_admin
admin
content_manager
marketing

For production deployments, administrator credentials should be created securely and never committed to Git.

🖥️ Run the Development Server

Start Laravel:

php artisan serve

The API will normally be available at:

http://127.0.0.1:8000
🔗 Frontend Integration

The MasterLink frontend is built with React.

The frontend communicates with this backend through REST APIs.

Example:

React
  ↓
HTTP Request
  ↓
Laravel API
  ↓
Controller
  ↓
Model
  ↓
MySQL

Public website data is retrieved from:

/api/services
/api/projects
/api/client-logos

Administrative operations use:

/api/admin/*
🌍 CORS

The backend is configured to allow communication with the MasterLink frontend.

For local development, the frontend typically runs on:

http://localhost:3000

while Laravel runs on:

http://127.0.0.1:8000

Make sure the frontend origin is correctly configured in the Laravel CORS configuration when deploying to another environment.

🚀 Production Configuration

For production, configure:

APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

Then optimize Laravel:

php artisan optimize

You can also cache configuration and routes:

php artisan config:cache
php artisan route:cache
php artisan event:cache
🧪 Testing

Run the Laravel test suite:

php artisan test

Check registered API routes:

php artisan route:list

Check only admin routes:

php artisan route:list --path=api/admin

Check public routes:

php artisan route:list --path=api
🧹 Useful Artisan Commands

Clear application caches:

php artisan optimize:clear

Rebuild optimization caches:

php artisan optimize

Create storage link:

php artisan storage:link

Check migration status:

php artisan migrate:status

Open Laravel Tinker:

php artisan tinker
📁 Project Structure
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   └── Website/
│   │
│   ├── Requests/
│   └── Resources/
│
├── Models/
├── Policies/
└── Services/

database/
├── factories/
├── migrations/
└── seeders/

routes/
├── api.php
├── admin.php
└── web.php

storage/
└── app/
    └── public/

config/
├── auth.php
├── cors.php
├── database.php
└── sanctum.php
🔐 Security Considerations

The backend follows several security practices:

Laravel Sanctum authentication
Role-based authorization
Policy-based access control
Server-side validation
Password hashing
Protected administrative routes
Controlled file uploads
Production debug mode disabled
Database constraints and relationships
Token invalidation on logout

Never commit:

.env
API tokens
Database passwords
Production credentials
Private keys
📈 Performance

The backend is designed to support:

Pagination
Efficient relationship loading
API Resources
Search and filtering
Sorting
Database relationships
Laravel caching
Production optimization

Performance optimization can be further expanded with:

Query optimization
Database indexes
HTTP caching
Redis
Queue workers
CDN-based media delivery
🔄 Data Flow
Public Website
Visitor
   ↓
React Frontend
   ↓
Laravel Public API
   ↓
Website Controller
   ↓
Eloquent Model
   ↓
MySQL
Admin Dashboard
Administrator
   ↓
React Admin Dashboard
   ↓
Sanctum Authentication
   ↓
Role / Policy Authorization
   ↓
Laravel Admin API
   ↓
Eloquent Models
   ↓
MySQL
📋 Current Backend Modules
Module	Status
Authentication	✅ Complete
Sanctum	✅ Complete
RBAC	✅ Complete
Policies	✅ Complete
Services	✅ Complete
Projects	✅ Complete
Project Categories	✅ Complete
Project Media	✅ Complete
Client Logos	✅ Complete
Consultations	✅ Complete
Media Management	✅ Complete
Testimonials	✅ Complete
Posts	✅ Complete
Site Settings	✅ Complete
Admin Management	✅ Complete
Pagination	✅ Complete
Searching	✅ Complete
Filtering	✅ Complete
Sorting	✅ Complete
API Resources	✅ Complete
Form Validation	✅ Complete
🤝 Frontend Repository

The backend is designed to work with the MasterLink React frontend.

Frontend repository:

https://github.com/Er-Mohammed/master_link_frontend
📌 Development Notes

This project uses Laravel as the authoritative data source.

The React frontend should not treat hardcoded or mock data as the primary source for production content.

The intended production data flow is:

MySQL
  ↓
Laravel API
  ↓
React
  ↓
Public Website / Admin Dashboard

This architecture ensures that content created or modified through the Admin Dashboard is reflected consistently across the public website.

👨‍💻 Author

Mohammed

MasterLink Technology & Digital Marketing

📄 License

This project is proprietary software developed for MasterLink.

Unauthorized copying, redistribution, or commercial use is not permitted without permission from the project owner.
