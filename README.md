# machine_test

Secure File Upload System
Introduction: 
The secure file upload system is designed to ensure the safe and proper handling of file uploads, preventing potential threats and eliminating incorrect uploads. This documentation provides a step-by-step guide on how to use and test the system.  
Accessing the System:
•	Open your web browser and go to the login page: url/login.php.
User Authentication:
•	Log in using your credentials. If you do not have an account, proceed to the registration page at url/register.php to create one.
Navigating to File Upload:
•	After successful login, navigate to the file upload section.
File Selection:
•	Choose the file(s) you wish to upload.
•	Ensure that the selected file format is one of the supported formats: .jpg, .png, .pdf, .docx.
Security Measures:
The system incorporates security measures to ensure a safe environment for file uploads. If an attempt is made to upload a file without being logged in, the system will redirect to the login page. On every successful and failed upload, users' IP addresses are fetched and stored in the logs table. Filename sanitization mechanism is implemented to prevent potentially harmful symbols. Proper mechanism is included for preventing SQL injection. Security headers are provided for additional security.
Logout (Optional):
Once you have completed the file upload or other activities, consider logging out for added security. url/logout.php
Testing: 
 Store files inside the "test_image" folder and run the url/test.php. The test script  will start uploading all files, and provide a proper response.
Database:
For Database connection open conn.php file and rename with database credentials.

