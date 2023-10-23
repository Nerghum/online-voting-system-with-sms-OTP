# online-voting-system-with-sms-OTP
Fully featured voting system with otp and id confirmation


## Project Description

The Online Voting System is a web-based application that allows users to participate in elections by casting their votes online. It includes features for both users and administrators to manage elections and ensure a secure and efficient voting process.

The project is implemented using the following technologies:

- PHP
- JavaScript
- Bootstrap
- jQuery

### Features

1. **User Authentication**:
   - Users can log in to the system during an active election period.
   - Authentication can be done using an ID or phone number.
   
2. **Admin Panel**:
   - Administrators have access to a dedicated admin panel.
   - Admins can create states, areas, candidates, and new elections.
   
3. **Multiple Elections**:
   - The system supports the creation of multiple elections, each with its own set of candidates and posts.
   - Multiple elections can be created for different areas.

4. **SMS OTP System**:
   - Users are required to verify their identity through SMS OTP (One-Time Password) during the voting process.
   - The SMS functionality is available in the `conn.php` file and can be configured by adding the required API.

5. **Election Reports**:
   - The system generates election reports in PDF format.
   - The PDF generation is handled using a third-party library called `html2pdf`.

### Project Structure

The important files and directories in the project are as follows:

- `admin/`: Contains the admin panel files.
- `admin/includes/conn.php`: Configuration file and the SMS functionality.
- `html2pdf/`: Contains the third-party library for PDF generation.
- Other project files and directories as needed.

## Getting Started

1. Clone the project repository to your local environment.

2. Configure your web server to serve the project directory.

3. Set up the database and import the necessary SQL tables (if provided).

4. Update the SMS API configuration in `admin/includes/conn.php`.

5. Access the admin panel to create elections, manage candidates, and view reports.

6. Users can log in during an active election and cast their votes.

## License

GNU GENERAL PUBLIC LICENSE
Version 3, 29 June 2007

Copyright (C) nerghum 2023

## Acknowledgments

- [html2pdf](https://github.com/eKoopmans/html2pdf) - Third-party library used for PDF generation.
- Other open-source libraries and tools that contributed to this project.

---
