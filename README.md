# MasterLink Backend

The backend API for **MasterLink Technology & Digital Marketing**, built with **Laravel 13, PHP 8.3, and MySQL**.

MasterLink Backend provides a secure, scalable RESTful API for the company's website and administration platform. It is responsible for authentication, authorization, business logic, data management, media handling, validation, database relationships, and administrative operations.

## Technology Stack

* Laravel 13
* PHP 8.3
* MySQL
* Laravel Sanctum
* Eloquent ORM
* RESTful API
* Laravel Policies & Middleware
* Form Requests
* API Resources
* Factories & Seeders
* Pest / PHPUnit
* Laravel Storage

## Core Features

### Authentication & Security

* Admin authentication using Laravel Sanctum Personal Access Tokens
* Login, Logout, Current Admin (`/me`)
* Secure password change
* Token invalidation
* Active admin verification
* Role-based access control
* Authorization through Laravel Policies
* Protected administrative API routes
* JSON responses for authentication, authorization, validation, and server errors

### Roles

The system supports four administrative roles:

* `super_admin`
* `admin`
* `content_manager`
* `marketing`

Each role has controlled access to specific administrative resources.

### Administrative Resources

The API provides management endpoints for:

* Admins
* Services
* Service Media
* Projects
* Project Categories
* Project Media
* Project Services
* Posts
* Media
* Client Logos
* Testimonials
* Consultations
* Site Settings

### CRUD & Data Management

* Create, read, update, and delete operations
* Filtering
* Searching
* Sorting
* Pagination
* Validation
* Relationship management
* Resource transformations
* Consistent JSON API responses

### Media Management

The media system supports:

* Image, video, and document files
* File metadata
* MIME type validation
* File size handling
* Storage management
* Media-to-service relationships
* Media-to-project relationships
* Media references from posts, testimonials, and client logos

### Database Architecture

The backend uses relational MySQL design with dedicated tables for core business entities and pivot tables for many-to-many relationships.

Key relationships include:

```text
Services ↔ Media
Projects ↔ Media
Projects ↔ Services
Projects → Project Categories
Posts → Admin / Media
Client Logos → Media
Testimonials → Media
Consultations → Services
Media → Admin
```

Foreign-key behavior is designed according to business requirements using:

* `CASCADE`
* `SET NULL`
* `RESTRICT`

### Permanent Hard Delete

MasterLink intentionally uses **Permanent Hard Delete** instead of a Trash/Restore system.

Deletion behavior is designed to preserve meaningful business records while cleaning association and dependent records where appropriate.

Examples:

```text
Delete Project
→ project_media CASCADE
→ project_services CASCADE

Delete Service
→ service_media CASCADE
→ project_services CASCADE
→ consultations preserved with service_id = NULL

Delete Media
→ pivot records CASCADE
→ optional references SET NULL
```

### Testing

The backend includes integration coverage for critical deletion and relationship behavior.

The Hard Delete integration suite verifies:

* Permanent deletion
* Foreign-key behavior
* Cascade relationships
* `SET NULL`
* `RESTRICT`
* Authentication
* Authorization
* Sanctum token cleanup
* Physical media deletion

Current Hard Delete integration result:

```text
11 tests passed
87 assertions
```

## Architecture

```text
React Frontend
      ↓
REST API
      ↓
Laravel 13
      ↓
Eloquent ORM
      ↓
MySQL
```

The backend is designed to serve as the **single source of truth** for application data and business operations.

## Project Status

The MasterLink backend is in an advanced development stage, with the core API architecture, authentication, authorization, CRUD resources, relationships, validation, media management, and hard-delete behavior implemented and tested.
