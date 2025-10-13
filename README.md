# 🚀 TechVerse - Your Universe of Technology

![TechVerse](https://img.shields.io/badge/TechVerse-E--Commerce-7C3AED?style=for-the-badge)
![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)
![Status](https://img.shields.io/badge/Status-In%20Development-orange?style=flat)

> **CS2TP 2025-26 E-Commerce Platform Project**  
> A modern, full-featured e-commerce platform for selling premium technology products.

---

## 📋 About

**TechVerse** is a comprehensive e-commerce platform built with Laravel, designed to provide students and professionals with access to cutting-edge technology products at competitive prices.

### 🎯 Our Vision
To democratize access to technology by offering an intuitive, secure, and feature-rich online shopping experience.

### 🎨 Business Identity
- **Name**: TechVerse  
- **Tagline**: "Your Universe of Technology"  
- **Target Audience**: University students, young professionals (18-35), tech enthusiasts  
- **Product Range**: 30+ products across 6 categories

---

## ✨ Key Features

### For Customers 🛒
- ✅ User registration & secure login
- ✅ Browse products by category
- ✅ Advanced search & filtering (name, category, price)
- ✅ Shopping basket management
- ✅ Secure checkout process
- ✅ Order history & tracking
- ✅ Product reviews & ratings
- ✅ Profile management
- ✅ Password security features
- ✅ Product return requests

### For Administrators 👨‍💼
- ✅ Comprehensive admin dashboard
- ✅ Real-time inventory management
- ✅ Automatic low-stock alerts
- ✅ Order processing & tracking
- ✅ Customer account management
- ✅ Sales & inventory reports
- ✅ Product management (add/edit/delete)
- ✅ Stock movement tracking

### Bonus Features 🎁
- 🤖 **AI-Powered Customer Chatbot** - Intelligent product assistance
- 📊 **Analytics Dashboard** - Real-time business insights
- ⭐ **Review System** - Customer feedback & ratings
- 🔐 **Advanced Security** - CSRF, XSS, SQL injection protection

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | Laravel 10.x (PHP 8.2+) |
| **Frontend** | Blade, Tailwind CSS 3.x, Alpine.js |
| **Database** | MySQL 8.0 |
| **Version Control** | Git & GitHub |
| **Package Manager** | Composer, NPM |

---

## 📦 Product Categories

1. **💻 Laptops & Computers** - High-performance devices
2. **📱 Smartphones & Tablets** - Latest mobile technology  
3. **🎧 Audio Equipment** - Premium sound devices
4. **🎮 Gaming & Accessories** - Gaming gear & peripherals
5. **⌚ Smart Home & Wearables** - IoT & fitness devices
6. **🖥️ Computer Accessories** - Monitors, keyboards, storage

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL 8.0+
- Git

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/saeed-moo/TechVerse.git
cd TechVerse

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env file
DB_DATABASE=techverse
DB_USERNAME=your_username
DB_PASSWORD=your_password

# 5. Create database
mysql -u root -p
CREATE DATABASE techverse;
exit;

# 6. Run migrations and seeders
php artisan migrate --seed

# 7. Link storage
php artisan storage:link

# 8. Build assets
npm run dev

# 9. Start development server
php artisan serve
```

Visit: `http://localhost:8000` 🎉

### Test Accounts

**Admin Access:**
```
Email: admin@techverse.com
Password: Admin123!
```

**Customer Access:**
```
Email: customer@test.com
Password: Customer123!
```

⚠️ **Change these passwords in production!**

---

## 👥 Team

| Name | Role | GitHub | Email |
|------|------|--------|-------|
| **Saeed Moosivand** | Project Lead & Full-Stack Developer | [@saeed-moo](https://github.com/saeed-moo) | 240169451@aston.ac.uk |
| **Ana Ciobanu** | Frontend Developer | [@aion-maria](https://github.com/aion-maria) | 220103129@aston.ac.uk |
| **Natalia Aghedo** | Backend Developer | [@NataliaAghedo-1](https://github.com/NataliaAghedo-1) | 240006237@aston.ac.uk |
| **Umm Salma Hamisu** | Database Specialist | [@UmmSalmaHamisu](https://github.com/UmmSalmaHamisu) | 240132950@aston.ac.uk |
| **Mandeep Singh** | QA Engineer & Tester | [@MandeepSingh](https://github.com/MandeepSingh) | 230107629@aston.ac.uk |
| **Isaac Yeboah** | DevOps & Deployment | [@Notisaac07](https://github.com/Notisaac07) | 240159991@aston.ac.uk |
| **Majdi Alkayed** | Feature Developer | [@Whitewolf2001](https://github.com/Whitewolf2001) | majdialkayed19@aston.ac.uk |

### Team Roles & Responsibilities

**Saeed Moosivand** - *Project Lead*
- Overall project architecture and design
- Code reviews and quality assurance
- Sprint planning and task coordination
- Final deployment and presentation

**Ana Ciobanu** - *Frontend Developer*
- UI/UX design and implementation
- Blade template development
- Tailwind CSS styling
- Responsive design for mobile

**Natalia Aghedo** - *Backend Developer*
- Controllers and routing
- Business logic implementation
- API development
- Middleware and authentication

**Umm Salma Hamisu** - *Database Specialist*
- Database schema design
- Migrations and seeders
- Query optimization
- Data integrity and relationships

**Mandeep Singh** - *QA Engineer & Tester*
- Writing automated tests
- Manual testing procedures
- Bug tracking and reporting
- Quality assurance standards

**Isaac Yeboah** - *DevOps & Deployment*
- Server setup and configuration
- Deployment automation
- Environment management
- Documentation maintenance

**Majdi Alkayed** - *Feature Developer*
- AI chatbot implementation
- Reviews and ratings system
- Advanced features
- Third-party integrations

---

## 📂 Project Structure

```
TechVerse/
├── app/
│   ├── Http/Controllers/    # Application controllers
│   ├── Models/              # Eloquent models
│   └── Services/            # Business logic
├── database/
│   ├── migrations/          # Database schema
│   └── seeders/             # Sample data
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Tailwind styles
│   └── js/                 # JavaScript
├── routes/
│   └── web.php             # Application routes
├── public/                 # Public assets
└── tests/                  # Automated tests
```

---

## 🔄 Development Workflow

### Git Branching Strategy
- `main` - Production code (protected)
- `develop` - Integration branch
- `feature/*` - New features
- `bugfix/*` - Bug fixes
- `hotfix/*` - Emergency fixes

### Creating a Feature

```bash
# 1. Start from develop
git checkout develop
git pull origin develop

# 2. Create feature branch
git checkout -b feature/your-feature-name

# 3. Make changes and commit
git add .
git commit -m "feat: add your feature description"

# 4. Push to GitHub
git push origin feature/your-feature-name

# 5. Create Pull Request on GitHub
```

### Commit Convention
```
feat: Add new feature
fix: Fix a bug
docs: Update documentation
style: Code formatting
refactor: Code refactoring
test: Add tests
chore: Maintenance tasks
```

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

---

## 📚 Documentation

- **[Installation Guide](docs/INSTALLATION.md)** - Detailed setup instructions
- **[Contributing Guidelines](CONTRIBUTING.md)** - How to contribute
- **[Team Setup](docs/TEAM_SETUP.md)** - Onboarding guide
- **[API Documentation](docs/API.md)** - API reference
- **[Deployment Guide](docs/DEPLOYMENT.md)** - Production deployment

---

## 🔐 Security

We implement multiple security layers:
- ✅ Password hashing (Bcrypt)
- ✅ CSRF protection
- ✅ SQL injection prevention
- ✅ XSS protection
- ✅ Input validation & sanitization
- ✅ Role-based access control

**Found a security issue?** Email: saeed.security@techverse.com  
(Please don't create public issues for security vulnerabilities)

---

## 📊 Current Status

### Sprint 1 (Weeks 1-3) ✅
- [x] Project setup & initialization
- [x] Database schema design
- [x] Basic authentication system
- [x] Team onboarding

### Sprint 2 (Weeks 4-6) 🔄
- [ ] Product catalog implementation
- [ ] Shopping basket functionality
- [ ] Order processing system
- [ ] Admin dashboard

### Sprint 3 (Weeks 7-9) ⏳
- [ ] AI chatbot integration
- [ ] Reviews & ratings system
- [ ] Advanced search features
- [ ] Testing & bug fixes

### Sprint 4 (Weeks 10-12) 📅
- [ ] Performance optimization
- [ ] Security audit
- [ ] Final testing
- [ ] Deployment & documentation

---

## 🤝 Contributing

We welcome contributions from all team members! Please read our [Contributing Guidelines](CONTRIBUTING.md) before getting started.

### Quick Contribution Steps:
1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

---

## 📞 Contact

**Project Lead**: Saeed  
📧 Email: saeed@university.ac.uk  
🐙 GitHub: [@saeed-moo](https://github.com/saeed-moo)

**Project Links:**
- 🔗 Repository: https://github.com/saeed-moo/TechVerse
- 📋 Trello Board: [Coming Soon]
- 💬 Team Chat: [Coming Soon]

---

## 📄 License

This project is developed for educational purposes as part of CS2TP coursework.

**Academic Integrity Statement:**  
This project was developed with minimal use of Generative AI tools, in compliance with university guidelines. All code represents original work by our team.

---

## 🙏 Acknowledgments

- **CS2TP Module** - Course structure and guidance
- **Laravel Community** - Excellent framework and documentation
- **Open Source Contributors** - Various packages and tools
- **Our Team** - Dedication and collaboration

---

## 🌟 Show Your Support

If you find this project helpful, please ⭐ star this repository!

---

<div align="center">

**Made with ❤️ by the TechVerse Team**

![Status](https://img.shields.io/badge/Status-Active%20Development-success?style=flat)
![Team](https://img.shields.io/badge/Team-4+%20Members-blue?style=flat)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=flat)

**🚀 Building the Future of Tech E-Commerce 🚀**

</div>

---

**Last Updated**: October 2025  
**Version**: 1.0.0  
**Status**: 🚧 In Development
