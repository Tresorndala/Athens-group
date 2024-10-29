-- Drop the database if it already exists to ensure a clean setup
DROP DATABASE IF EXISTS campus_maintenance;
CREATE DATABASE campus_maintenance;
USE campus_maintenance;


-- Create Department table to store different departments
CREATE TABLE `Department` (
    `departmentID` INT PRIMARY KEY AUTO_INCREMENT,
    `departmentName` VARCHAR(255) NOT NULL
);

-- Create User table to store information on users submitting maintenance requests
CREATE TABLE `User` (
    `userID` INT PRIMARY KEY AUTO_INCREMENT,
    `userName` VARCHAR(255) NOT NULL,
    `userContact` VARCHAR(20),
    `userEmail` VARCHAR(255) UNIQUE,
    `departmentID` INT,
    FOREIGN KEY (`departmentID`) REFERENCES Department(`departmentID`) ON DELETE SET NULL
);

-- Create MaintenanceType table to categorize types of maintenance
CREATE TABLE `MaintenanceType` (
    `maintenanceTypeID` INT PRIMARY KEY AUTO_INCREMENT,
    `typeName` VARCHAR(255) NOT NULL
);

-- Create Status table to track the status of each maintenance request
CREATE TABLE `Status` (
    `statusID` INT PRIMARY KEY AUTO_INCREMENT,
    `statusName` VARCHAR(50) NOT NULL
);

-- Create MaintenanceRequest table to store maintenance requests
CREATE TABLE `MaintenanceRequest` (
    `requestID` INT PRIMARY KEY AUTO_INCREMENT,
    `userID` INT,
    `maintenanceTypeID` INT,
    `statusID` INT,
    `description` TEXT NOT NULL,
    `submissionDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `completionDate` DATE,
    FOREIGN KEY (`userID`) REFERENCES User(`userID`) ON DELETE SET NULL,
    FOREIGN KEY (`maintenanceTypeID`) REFERENCES MaintenanceType(`maintenanceTypeID`),
    FOREIGN KEY (`statusID`) REFERENCES Status(`statusID`)
);


-- Insert initial data into Department table
INSERT INTO `Department` (`departmentName`) VALUES 
    ('IT Department'), 
    ('Facilities Department'), 
    ('Administrative Services');

-- Insert initial data into MaintenanceType table
INSERT INTO `MaintenanceType` (`typeName`) VALUES 
    ('Electrical'), 
    ('Plumbing'), 
    ('HVAC'), 
    ('General Repairs'), 
    ('Cleaning');

-- Insert initial data into Status table
INSERT INTO `Status` (`statusName`) VALUES 
    ('Pending'), 
    ('In Progress'), 
    ('Completed'), 
    ('Cancelled');

-- Sample data for the User table (replace with real users as needed)
INSERT INTO `User` (`userName`, `userContact`, `userEmail`, `departmentID`) VALUES 
    ('John Doe', '123456789', 'johndoe@example.com', 1),
    ('Jane Smith', '987654321', 'janesmith@example.com', 2);

-- Sample data for MaintenanceRequest table
INSERT INTO `MaintenanceRequest` (`userID`, `maintenanceTypeID`, `statusID`, `description`) VALUES 
    (1, 1, 1, 'Flickering lights in the main hallway'),
    (2, 2, 2, 'Leaking pipe in the restroom');
