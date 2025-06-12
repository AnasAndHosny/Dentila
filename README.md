# Dental Clinic Automation System API – Laravel 12

## 👥 Backend Team
**Backend Development Team (Laravel 12):**  
- Anas Albalah  
- Mohammad Hosny Wees  

## 🏫 Academic Information
- Damascus University  
- Faculty of Informatics Engineering  
- Software Engineering Department  
- 4th Year – Project 2

## 📘 Project Overview
This project is a standalone academic system developed as part of a 4th-year graduation project at the Faculty of Informatics Engineering.  
It is **not based on a previous version** and **not part of a larger system**.

The system automates dental clinic operations such as patient management, appointment scheduling, medical records, billing, notifications, and role-based access for doctors, receptionists, managers, and patients.

The backend is built using Laravel 12 as a RESTful API, serving multiple frontend clients (mobile and web) through versioned and well-structured endpoints.

## 🧰 Technologies Used
- **Framework:** Laravel 12
- **Monitoring:** Laravel Telescope
- **Authentication:** Laravel Sanctum *(planned)*
- **Database:** MySQL
- **API Architecture:** RESTful
- **Versioning:** URI-based (`/api/v1/`)
- **Code Style:** Laravel Pint
- **Environment:** PHP 8.2+, Composer, Artisan CLI
- **Deployment Target:** Linux server or cloud

## 📦 Features (Planned)
- CRUD operations for patients, appointments, treatments, and payments
- Role-based access (doctor, receptionist, manager, patient)
- Appointment queue and scheduling system
- Digital dental chart and treatment planning
- Notifications
- Versioned API structure (`v1`, `v2`, etc.)
- Unified JSON response for all endpoints
- Admin-level system logs and statistics
- Arabic/English multi-language support
- Future integration with payment gateways and SMS

## 📄 Getting Started

```bash
# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Serve the project
php artisan serve
