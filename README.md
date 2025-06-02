# Room Reservation System

## Description

This is a simple web-based room reservation system developed using PHP, HTML, CSS, and MySQL.
It allows users to register, login, view available rooms, and make reservations.

## Technologies Used

*   **Backend:** PHP
*   **Database:** MySQL
*   **Frontend:** HTML, CSS

## Setup and Installation

This project is designed to run on a web server environment with PHP and MySQL support, such as XAMPP or WAMP.

1.  **Install XAMPP (or WAMP):** Download and install XAMPP from [apachefriends.org](https://www.apachefriends.org/index.html).

2.  **Place Project Files:** Copy the `reservation` project folder into the `htdocs` directory of your XAMPP installation.
    The path should look something like `C:\xampp\htdocs\reservation`.

3.  **Database Setup:**
    *   Start Apache and MySQL from the XAMPP Control Panel.
    *   Open your web browser and go to `http://localhost/phpmyadmin`.
    *   Create a new database. You can name it `reservation_db` or similar.
    *   You will need to create a `users` table and a `salles` (rooms) table, and a `reservations` table. Based on the code, the structure should be similar to this:
        *   `utilisateurs` table: `id` (INT, PK, AUTO_INCREMENT), `nom` (VARCHAR), `email` (VARCHAR, UNIQUE), `mot_de_passe` (VARCHAR)
        *   `salles` table: `id` (INT, PK, AUTO_INCREMENT), `nom` (VARCHAR), `description` (TEXT), `equipements` (TEXT)
        *   `reservations` table: `id` (INT, PK, AUTO_INCREMENT), `salle_id` (INT, FK to `salles.id`), `utilisateur_id` (INT, FK to `utilisateurs.id`), `date_reservation` (DATE), `heure_debut` (TIME), `heure_fin` (TIME)
    *   You might need to adjust the database connection details in `includes/db.php` if your database name, username, or password are different from the defaults (usually `root` with no password for MySQL in XAMPP).

4.  **Run the Application:** Open your web browser and go to `http://localhost/reservation`.

## Usage

*   **Register:** Create a new user account.
*   **Login:** Log in with your registered account.
*   **View Rooms:** See the list of available rooms.
*   **Reserve a Room:** Select a room and choose a date and time slot to make a reservation.
*   **Dashboard:** View your upcoming reservations.

## File Structure

```
reservation/
├── css/
│   └── style.css
├── includes/
│   ├── auth.php
│   └── db.php
├── pages/
│   ├── dashboard.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   └── reservation.php
└── index.php
``` 