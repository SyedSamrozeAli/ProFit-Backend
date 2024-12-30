# ProFit - Gym Management System 💪

![Contributors](https://img.shields.io/github/contributors/your_username/ProFit.svg?style=for-the-badge)
![Forks](https://img.shields.io/github/forks/your_username/ProFit.svg?style=for-the-badge)
![Stars](https://img.shields.io/github/stars/your_username/ProFit.svg?style=for-the-badge)
![Issues](https://img.shields.io/github/issues/your_username/ProFit.svg?style=for-the-badge)

<p align="center">
  <a href="https://github.com/SyedSamrozeAli/ProFit-Backend">
    <img src="public/images/profit-logo.png" alt="ProFit Logo" width="400" height="250">
  </a>
</p>

## 📋 About The Project

ProFit is a gym management system designed for gym administrators to streamline operations. It provides functionalities to manage members, trainers, inventory, and finances, along with real-time analytics and insights.


![image](https://github.com/user-attachments/assets/f367dfde-54a1-41f8-a0a9-d2a27b39e140)
![image](https://github.com/user-attachments/assets/d2202700-7cd5-4f65-b94f-aa6175b97a4e)
![image](https://github.com/user-attachments/assets/98a240a5-5d55-43c6-8acd-a26964ad78cf)
![image](https://github.com/user-attachments/assets/4eab3cbe-2ec1-44b8-b2bd-a83bf0388e9b)
![image](https://github.com/user-attachments/assets/502eeb37-288f-4906-8a57-860ba796c387)
![image](https://github.com/user-attachments/assets/b4610c8f-fca6-4c8b-a086-87f9cf8b1145)
![image](https://github.com/user-attachments/assets/ca69da00-3370-4c2c-ae01-5417358de012)






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
   git clone https://github.com/SyedSamrozeAli/ProFit-Backend.git

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

## Contributors ✨

| Name          | GitHub Profile                       | Role                |
|---------------|--------------------------------------|---------------------|
| Syed Samroze Ali      | [@syedsamrozeali](https://github.com/SyedSamrozeAli) | Backend Developer  |
| Shaheer Beig   | [@shaheerbeig](https://github.com/shaheerbeig) | Frontend Developer   |
| Shaheer Mumtaz   | [@shaheermumtaz](https://github.com/Shaheer2003) | UI/UX Designer      |
    
