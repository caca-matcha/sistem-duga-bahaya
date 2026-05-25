# ⚠️ SIDUBA - Sistem Duga Bahaya (Hazard Report System)

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Status](https://img.shields.io/badge/Status-Production--Ready-success?style=for-the-badge)

Welcome to **SIDUBA (Sistem Duga Bahaya)**. This is a comprehensive, digital Health, Safety, and Environment (HSE/K3) platform designed to streamline the reporting, tracking, and resolution of workplace hazards in real-time, ensuring a safer work environment for everyone.

---

## ✨ Key Features

### 🚀 Smart Hazard Reporting (Karyawan)
Experience a seamless reporting flow directly from the field:
- **Instant Reporting**: Employees can report hazards with specific details, risk categories, and priority levels.
- **Photo Evidence**: Upload *Before* photos as clear evidence of the hazard.
- **Precision Mapping**: Select specific building locations, areas, and grid cells.

### 🛡️ SHE Control Dashboard (Admin)
A high-end administrative hub for the Safety, Health, and Environment (SHE) Team:
- **Real-time Analytics**: Interactive statistics monitoring open, processing, and completed hazards.
- **Gatekeeping & Validation**: SHE admins can approve, reject, or assign reports directly to PICs.
- **Export to Excel**: Generate comprehensive hazard recap reports with a single click.

### 👥 Follow-up & Resolution Workflow
- **PIC Assignment**: Direct assignment to responsible leaders or departments.
- **Flexible Due Dates**: PICs can set realistic target completion dates (target_penyelesaian) based on field conditions.
- **Proof of Resolution**: PICs must submit *After* photos and action logs to request case closure.

### 🗺️ Master Data & Hazard Mapping
- **Location Mapping**: Upload blueprints/maps and configure interactive grid cells (Grid Editor).
- **Dynamic Master Data**: Built-in CMS for managing Users, Locations, and HSE Company Parameters.
- **Bulk Import**: Seamlessly import User and Location data via Excel templates.

---

## 🛠️ Technology Stack

| Layer | Technology |
| :--- | :--- |
| **Framework** | [Laravel 11](https://laravel.com) |
| **UI Engine** | [Tailwind CSS](https://tailwindcss.com) (Custom Corporate Theme) |
| **Database** | MySQL / MariaDB |
| **Assets Bundler**| Vite / PostCSS |
| **Testing** | Pest PHP |

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL / MariaDB

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/caca-matcha/sistem-duga-bahaya.git
   cd sistem-duga-bahaya
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Configure your database credentials in the `.env` file.

5. **Run Migrations**
   ```bash
   php artisan migrate --seed
   ```

6. **Serve the Application**
   ```bash
   npm run dev
   # In another terminal:
   php artisan serve
   ```

---

## 📸 Visuals
> [!NOTE]
> SIDUBA incorporates a clean, responsive, and intuitive User Interface tailored for quick mobile reporting on the field and high-density data management on desktop dashboards.

---

## 🛡️ License
This project is proprietary and developed as a comprehensive safety management solution. All rights reserved.

---
Developed with ❤️ for a Safer Workplace.
