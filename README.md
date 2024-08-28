
# Personal Finance Tracker

## Overview

The **Personal Finance Tracker** is a web application built with Laravel and Blade templates, designed to help users manage and track their personal finances. The application supports user authentication (using Laravel Breeze) and integrates with a MySQL database for persistent storage of expense data.

**[Live Demo](https://personal-finance-tracker-azure.vercel.app)** - This demo is built with HTML, CSS, and JavaScript but shares the same styling as the full application.

## Features

1. **User Authentication:**
   - Secure user registration, login, and logout functionalities.
   - Built with Laravel Breeze for simple and customizable authentication.

2. **Expense Logging:**
   - Users can input their expenses, including the amount, place of spend, category, and date.
   - Supports dynamic category management.

3. **Expense Summary:**
   - Displays the total amount spent over various time periods (e.g., last 30 days, current month, last 90 days, current year, or a custom date range).
   - Provides insights into the highest expense category, number of transactions, and average daily spending.

4. **Spending Trends:**
   - Compares daily spending against the previous month and provides visual indicators of changes.

5. **Profile Management:**
   - Users can view their profile information and manage their account settings.

6. **Database Integration:**
   - All expense data is stored in a MySQL database, ensuring persistence across sessions.

## Technologies Used

- **Laravel:** PHP framework used for routing, authentication, and database management.
- **Blade Templates:** For building reusable and modular views.
- **JavaScript:** For dynamic client-side functionalities and interaction.
- **Font Awesome:** For icons throughout the application.
- **Laravel Breeze:** Simple authentication scaffolding with Laravel.
- **MySQL:** Used for persistent data storage.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/mertacun/personal-finance-tracker-php.git
   ```
2. Navigate to the project directory:
   ```bash
   cd personal-finance-tracker-php
   ```
3. Install the dependencies:
   ```bash
   composer install
   npm install
   npm run dev
   ```
4. Set up the environment:
   - Copy `.env.example` to `.env`:
     ```bash
     cp .env.example .env
     ```
   - Update your database configuration in the `.env` file with your own database credentials:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_database_name
     DB_USERNAME=your_database_username
     DB_PASSWORD=your_database_password
     ```

5. Generate the application key:
   ```bash
   php artisan key:generate
   ```

6. Run the database migrations:
   ```bash
   php artisan migrate
   ```

7. Start the development server:
   ```bash
   php artisan serve
   ```

## Usage

1. **Register and Login:**
   - Users can create an account and log in to manage their personal finances.

2. **Logging Expenses:**
   - Enter the expense details, including amount, place, category, and date.
   - The expense will be saved to the database and reflected in the summaries.

3. **Viewing Summaries:**
   - Use the tabs to switch between time periods (e.g., last 30 days, current month).
   - View the breakdown of expenses and spending trends.

4. **Profile Management:**
   - Users can manage their profile settings and view their account details.

## Customization

- **Categories:** Users can add and manage custom categories for their expenses.
- **Blade Templates:** The design can be customized by modifying the Blade templates.
- **Database Configuration:** You can change the database connection and configuration in the `.env` file.

## Future Enhancements

- **Budgeting:** Implement a feature to allow users to set budgets and receive notifications when nearing or exceeding them.
- **Reports:** Generate detailed reports for users based on their spending data.
- **Mobile App:** Create a mobile version of the app for better accessibility on the go.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

Feel free to contribute to this project by submitting issues or pull requests!
