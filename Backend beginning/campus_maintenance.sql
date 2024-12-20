-- Drop the database if it already exists to ensure a clean setup
DROP DATABASE IF EXISTS campus_maintenance;

-- Create the new database
CREATE DATABASE campus_maintenance;

-- Select the database to use
USE campus_maintenance;

-- Create the User table
CREATE TABLE `User` (
    `userID` INT PRIMARY KEY AUTO_INCREMENT,
    `userName` VARCHAR(255) NOT NULL,
    `userContact` VARCHAR(20),
    `userEmail` VARCHAR(255) UNIQUE NOT NULL, -- Email is unique
    `userpassword` VARCHAR(255) NOT NULL, -- Password for user authentication
    `userRole` ENUM('Regular', 'Admin') DEFAULT 'Regular' -- Role to distinguish between regular users and admin
);

-- Create the MaintenanceType table
CREATE TABLE `MaintenanceType` (
    `maintenanceTypeID` INT PRIMARY KEY AUTO_INCREMENT,
    `typeName` VARCHAR(255) NOT NULL
);

-- Create the Status table
CREATE TABLE `Status` (
    `statusID` INT PRIMARY KEY AUTO_INCREMENT,
    `statusName` VARCHAR(50) NOT NULL
);

-- Create the Report table to store maintenance requests (with reportID as unique string or auto-incremented)
CREATE TABLE `report` (
    `reportID` VARCHAR(255) PRIMARY KEY, -- Unique report ID, stored as VARCHAR to accommodate auto-generated and user-entered IDs
    `userID` INT, -- Foreign key to the user who submitted the request
    `maintenanceTypeID` INT, -- Foreign key to the type of maintenance
    `statusID` INT, -- Foreign key to the status of the request
    `description` TEXT NOT NULL, -- Description of the issue
    `location` VARCHAR(255), -- Location of the issue
    `submissionDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Date and time the request was submitted
    `completionDate` DATE, -- Date the maintenance request was completed
    FOREIGN KEY (`userID`) REFERENCES `User`(`userID`) ON DELETE SET NULL, -- If user is deleted, set the request's userID to NULL
    FOREIGN KEY (`maintenanceTypeID`) REFERENCES `MaintenanceType`(`maintenanceTypeID`),
    FOREIGN KEY (`statusID`) REFERENCES `Status`(`statusID`)
);

-- Insert initial data into the MaintenanceType table
INSERT INTO `MaintenanceType` (`typeName`) VALUES 
    ('Electrical'), 
    ('Plumbing'), 
    ('HVAC'), 
    ('General Repairs'), 
    ('Cleaning');

-- Insert initial data into the Status table
INSERT INTO `Status` (`statusName`) VALUES 
    ('Pending'), 
    ('In Progress'), 
    ('Completed'), 
    ('Cancelled');

-- Sample data for the User table (Admin and Regular users)
INSERT INTO `User` (`userName`, `userContact`, `userEmail`, `userpassword`, `userRole`) VALUES 
    ('John Doe', '123456789', 'ndalabuzangu@gmail.com', 'Ndala1950@@', 'Admin'), -- Admin
    ('Jane Smith', '987654321', 'janesmith@example.com', 'hashed_password_2', 'Regular'); -- Regular user

