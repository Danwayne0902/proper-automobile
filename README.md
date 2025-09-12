# Automobile Dealer Management System

A comprehensive automobile dealership management application built with Laravel 11, Inertia.js, and Vue 2. This system provides role-based access control for administrators, dealers, and customers with complete automobile inventory management, booking system, and transaction processing.

## 🚗 Features

### Role-Based System
- **Administrators**: Complete system oversight with analytics and user management
- **Dealers**: Automobile inventory management and sales tracking
- **Customers**: Browse cars, book test drives, and manage transactions

### Core Functionality
- **Automobile Management**: Complete CRUD operations with image upload support
- **Booking System**: Test drives, reservations, and inspection scheduling
- **Transaction Processing**: Payment handling with status tracking and invoice generation
- **Analytics Dashboard**: Role-specific dashboards with charts and metrics
- **Search & Filtering**: Advanced automobile search with multiple filters

## 🛠 Technology Stack

- **Backend**: Laravel 11.x (PHP 8.1+)
- **Frontend**: Vue 2.7.x with Options API
- **Bridge**: Inertia.js 1.x
- **Styling**: TailwindCSS
- **Database**: MySQL 8.0+ / PostgreSQL 13+
- **Build Tool**: Vite
- **Authentication**: Laravel Breeze

## 📋 System Requirements

- PHP 8.1 or higher
- Node.js 14.18.1 or higher
- Composer 2.x
- MySQL 8.0+ or PostgreSQL 13+
- 2GB RAM minimum
- 10GB disk space (for images and uploads)

## 🚀 Quick Start

### 1. Clone & Install
```bash
git clone <repository-url>
cd proper-automobile
composer install
npm install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Configuration
```bash
# Configure your database settings in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=automobile_dealer
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Create storage link
php artisan storage:link
```

### 4. Build Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### 5. Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` to access the application.

## 👥 Default User Roles

The system includes three distinct user roles:

### Administrator
- Full system access and user management
- Comprehensive analytics and reporting
- Oversight of all automobiles, bookings, and transactions

### Dealer
- Manage automobile inventory (add, edit, delete)
- Process customer bookings and transactions
- View sales performance and analytics

### Customer
- Browse available automobiles
- Book test drives and reservations
- Manage payment transactions
- View booking and transaction history

## 📚 Application Structure

### Backend (Laravel)
```
app/
├── Http/Controllers/
│   ├── AutomobileController.php    # Automobile CRUD operations
│   ├── BookingController.php       # Booking management
│   ├── TransactionController.php   # Payment processing
│   └── DashboardController.php     # Analytics dashboards
├── Models/
│   ├── User.php                   # User model with roles
│   ├── Automobile.php             # Automobile model
│   ├── Booking.php                # Booking model
│   └── Transaction.php            # Transaction model
└── database/
    ├── migrations/                # Database schema
    └── factories/                 # Test data generators
```

### Frontend (Vue 2 + Inertia)
```
resources/js/
├── Components/
│   └── Layout/                    # Reusable layout components
├── Pages/
│   ├── Automobiles/              # Car management pages
│   ├── Bookings/                 # Booking system pages
│   ├── Transactions/             # Payment pages
│   └── Dashboard/                # Role-specific dashboards
└── app.js                        # Main application entry
```

## 🔐 Security Features

- **Role-based Access Control**: Strict permission system
- **CSRF Protection**: Built-in Laravel protection
- **Input Validation**: Comprehensive form validation
- **File Upload Security**: Secure image handling
- **Authentication**: Laravel Breeze integration

## 🧪 Testing

The application includes comprehensive test coverage:

```bash
# Run all tests
php artisan test

# Run specific test types
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit

# Generate test coverage report
php artisan test --coverage
```

### Test Coverage Areas
- **Feature Tests**: HTTP requests, authentication, business logic
- **Unit Tests**: Model methods, relationships, validation
- **Factory Support**: Realistic test data generation

## 📊 Database Schema

### Core Tables
- **users**: User accounts with role management
- **automobiles**: Vehicle inventory with dealer relationships
- **bookings**: Test drive and reservation scheduling
- **transactions**: Payment processing and status tracking

### Key Relationships
- Users → Automobiles (Dealer ownership)
- Users → Bookings (Customer bookings)
- Users → Transactions (Customer payments)
- Automobiles → Bookings (Vehicle bookings)
- Automobiles → Transactions (Vehicle transactions)

## 🎨 UI/UX Features

- **Responsive Design**: Mobile-friendly interface
- **Role-specific Navigation**: Customized menus for each user type
- **Real-time Updates**: Dynamic content with Inertia.js
- **Image Gallery**: Automobile photo management
- **Advanced Filtering**: Multi-criteria search system
- **Dashboard Analytics**: Visual data representation

## 🔧 Configuration

### Environment Variables
```bash
# Application
APP_NAME="Automobile Dealer"
APP_ENV=local
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_DATABASE=automobile_dealer

# File Storage
FILESYSTEM_DISK=public

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
```

### Customization Options
- **Automobile Categories**: Configurable vehicle types
- **Booking Types**: Customizable appointment types
- **Payment Methods**: Multiple payment options
- **User Roles**: Extensible role system

## 📈 Performance Considerations

- **Database Indexing**: Optimized queries for large datasets
- **Image Optimization**: Efficient file storage and serving
- **Caching Strategy**: Laravel cache for improved performance
- **Asset Optimization**: Minified CSS/JS for production

## 🚀 Deployment

Detailed deployment instructions are available in [DEPLOYMENT.md](DEPLOYMENT.md), including:

- Server requirements and setup
- Docker deployment options
- Security best practices
- Performance optimization
- Monitoring and maintenance

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards for PHP
- Use Vue 2 Options API consistently
- Write tests for new features
- Update documentation for significant changes

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

### Documentation
- [Laravel Documentation](https://laravel.com/docs)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Vue 2 Documentation](https://v2.vuejs.org/)
- [TailwindCSS Documentation](https://tailwindcss.com/docs)

### Getting Help
- Check the application logs in `storage/logs/`
- Review the troubleshooting section in DEPLOYMENT.md
- Create an issue for bugs or feature requests

## 🏗 Project Status

**Current Version**: 1.0.0  
**Status**: Production Ready  
**Last Updated**: 2024

### Recent Updates
- ✅ Complete CRUD operations for all entities
- ✅ Role-based dashboard implementation
- ✅ Comprehensive test suite
- ✅ Production deployment documentation
- ✅ Security hardening and validation

### Roadmap
- 🔄 Advanced analytics and reporting
- 🔄 Email notification system
- 🔄 API development for mobile apps
- 🔄 Advanced search with Elasticsearch
- 🔄 Real-time chat system

---

**Built with ❤️ using Laravel, Inertia.js, and Vue 2**
