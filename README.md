# 🌊 Next Wave - Digital Agency & Service Marketplace

[![PHP Version](https://img.shields.io/badge/PHP-8.x-blue.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/Database-MySQL-orange.svg)](https://www.mysql.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Next Wave is a comprehensive, full-stack digital agency platform designed to bridge the gap between creative services and professional project management. It features a stunning public-facing website, a robust client portal, and a powerful administrative dashboard to manage orders, services, and team interactions.

---

## 🚀 Key Features

### 🏢 Public Front-End
- **Modern & Responsive UI**: Sleek, high-performance design using modern CSS.
- **Service Showcases**: Detailed pages for Web Development, App Development, UI/UX, and Digital Marketing.
- **Dynamic Portfolio**: Showcase past projects with elegant filtering.
- **Contact & Inquiry**: Integrated forms for lead generation.
- **Pricing Tables**: Transparent service packages for potential clients.

### 👤 User (Client) Dashboard
- **Service Ordering**: Browse a marketplace of services and place orders directly.
- **Order Tracking**: Real-time progress bars and status updates for active projects.
- **Secure Payments**: Integrated payment processing for orders.
- **Real-time Chat**: Direct communication channel with the agency team.
- **Profile Management**: Secure account settings and order history.

### 🛡️ Admin Dashboard
- **Order Management**: Oversee all client orders, update payment statuses, and track project milestones.
- **User Management**: Manage registered clients and access levels.
- **Service Control**: Add, edit, or remove services from the marketplace.
- **Team Management**: Coordinate internal team members.
- **Site Settings**: Centralized configuration for the entire platform.

### ⚙️ Developer Features
- **Auto-Migrating Schema**: The system automatically detects missing database tables/columns and creates them on-the-fly, ensuring seamless updates without manual SQL execution.
- **Modular Config**: Environment-aware configuration for easy transition between local development and production.

---

## 🛠️ Technology Stack

| Layer | Technology |
| :--- | :--- |
| **Frontend** | HTML5, CSS3 (Vanilla), JavaScript (ES6+), Google Fonts, FontAwesome |
| **Backend** | PHP 8.x (Modular Architecture) |
| **Database** | MySQL (Relational Schema) |
| **Server** | XAMPP / Apache / InfinityFree |

---

## 📂 Project Structure

```text
digital-agency/
├── admin/            # Admin Panel logic & templates
├── user/             # Client Dashboard logic & templates
├── includes/         # Core config, DB connection, and shared utilities
├── database/         # SQL schema exports
├── assets/           # CSS, JS, and UI media files
├── uploads/          # User-uploaded files (profile pics, project files)
├── index.php         # Homepage
├── services.php      # Public services listing
└── contact.php       # Lead generation page
```

---

## ⚙️ Installation & Setup

### Prerequisites
- **XAMPP** or any local server environment with PHP 8.0+ and MySQL.
- **Git** (optional).

### Steps
1. **Clone the Project**:
   ```bash
   git clone https://github.com/WebCoderAli/Next-Wave-Digital-agency.git
   ```
2. **Move to Web Root**:
   Copy the `digital-agency` folder to your `htdocs` directory.

3. **Database Setup**:
   - Open **phpMyAdmin**.
   - Create a new database named `digital_agency`.
   - Import the SQL file located at `database/digital_agency.sql`.

4. **Configuration**:
   - Navigate to `includes/config.php`.
   - Update your database credentials if different from the default (`root` / `no password`).
   ```php
   define("DB_HOST", "localhost");
   define("DB_USER", "root");
   define("DB_PASS", "");
   define("DB_NAME", "digital_agency");
   ```

5. **Access the App**:
   - Open your browser and go to `http://localhost/digital-agency`.

---

## 📸 Screenshots

*(Coming Soon - Add your project screenshots here to wow your viewers!)*

---

## 🤝 Contributing
Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

---

## 📄 License
Distributed under the MIT License. See `LICENSE` for more information.

---
**Next Wave Digital Agency** - *Empowering your digital future.*
