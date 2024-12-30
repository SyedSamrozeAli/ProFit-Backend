# ProFit - Gym Management System 💪

![Contributors](https://img.shields.io/github/contributors/your_username/ProFit.svg?style=for-the-badge)
![Forks](https://img.shields.io/github/forks/your_username/ProFit.svg?style=for-the-badge)
![Stars](https://img.shields.io/github/stars/your_username/ProFit.svg?style=for-the-badge)
![Issues](https://img.shields.io/github/issues/your_username/ProFit.svg?style=for-the-badge)

<p align="center">
  <a href="https://github.com/SyedSamrozeAli/ProFit-Backend">
    <img src="images/logo.png" alt="ProFit Logo" width="100" height="100">
  </a>
</p>

## 📋 About The Project

ProFit is a gym management system designed for gym administrators to streamline operations. It provides functionalities to manage members, trainers, inventory, and finances, along with real-time analytics and insights.

![Screenshot](images/screenshot.png)

### 🛠️ Technologies Used

[![My Skills](https://skillicons.dev/icons?i=react,laravel,tailwind,mysql,php)](https://skillicons.dev)

### 🧑‍💻 Features

- Trainer Management (CRUD & Search)
- Member Management (CRUD & Search)
- Inventory Management (CRUD & Search)
- Finance Management with expense tracking and salary management
- Payment History for Members and Trainers
- Attendance Tracking for Members and Trainers
- Admin Dashboard with Real-time Analytics
- Visualizations for revenue, expenses, and attendance trends

---

## 🚀 Getting Started

### Prerequisites

- Composer
- Php
- MySQL

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your_username/ProFit.git

2. Navigate to Profit-Backend
    ```bash
    cd ProFit-Backend

3. Install dependencies
    ```bash
    composer install

4. Configure enviornment variables, copy the .env.example to .env file
    ```bash
    cp .env.example .env

5. Update .env file with your DB credentials
    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=profit_db
    DB_USERNAME=root
    DB_PASSWORD=your_password


6. Generate application key
   ```bash
   php artisan key:generate

7. Generate JWT secret key
   ```bash
   php artisan jwt:secret

8. Run database migrations
   ```bash
   php artisan migrate

9. Run Databse seeders
   ```bash
   php artisan db:seed

10. Start the Laravel server
    ```bash
    php artisan serve

11. The backend will be available at http://127.0.0.1:8000.


## 🌐 Frontend Repository
The frontend for this project is built using React and can be found <a href="https://github.com/SyedSamrozeAli/ProFit-Frontend">here</a>.
Follow the instructions in the frontend repository to set it up and connect it with the backend.
    
