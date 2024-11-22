-- Create database for techno_tv 
CREATE DATABASE IF NOT EXISTS techno_tv;

-- Use the right database incase you got more databases 
USE techno_tv;

-- Table with 1 H1, 1 text field, and 1 image:

-- Table with 1 H1, 2 text fields, and 1 image:
CREATE TABLE IF NOT EXISTS Nieuwsflash (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255), -- H1
    flashdesc1 TEXT, -- First text field
    flashimage1 VARCHAR(255), -- Second text field
    flashimage2 VARCHAR(255) -- Image path
    deleted BOOLEAN
);

-- Table with 1 H1 and 3 text fields: 

-- Table with 1 H1 and 5-10 images:
CREATE TABLE IF NOT EXISTS Gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255), -- H1
    image1_path VARCHAR(255), -- First image
    image2_path VARCHAR(255), -- Second image
    image3_path VARCHAR(255), -- Third image
    image4_path VARCHAR(255), -- Fourth image
    image5_path VARCHAR(255), -- Fifth image,
    image6_path VARCHAR(255), -- Optional sixth image
    image7_path VARCHAR(255), -- Optional seventh image
    image8_path VARCHAR(255), -- Optional eighth image
    image9_path VARCHAR(255), -- Optional ninth image
    image10_path VARCHAR(255) -- Optional tenth image
    deleted BOOLEAN
);

CREATE TABLE IF NOT EXISTS Story (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255), -- H1
    storydesc1 TEXT, -- First text field
    storydesc2 TEXT
);
CREATE TABLE IF NOT EXISTS WeeklySchedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    day VARCHAR(255) NOT NULL,
    slot VARCHAR(255) NOT NULL,
    time TIME NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    week VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted BOOLEAN
);