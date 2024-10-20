-- Create a database named 'dora'
CREATE DATABASE IF NOT EXISTS dora;

-- Switch to the 'dora' database
USE dora;

-- User Profiles
CREATE TABLE UserProfiles (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    UserType ENUM('Admin', 'CounterStaff', 'KitchenStaff', 'Customer') NOT NULL,
    Email VARCHAR(255),
    PhoneNumber VARCHAR(15)
);

-- Menu Items
CREATE TABLE MenuItems (
    ItemID INT PRIMARY KEY AUTO_INCREMENT,
    ItemName VARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    Description TEXT,
    PreparationTime INT,
    ImageURL VARCHAR(255),
    VideoURL VARCHAR(255),
    cuisine VARCHAR(30),
    category VARCHAR(80)
);

-- Item Images
-- CREATE TABLE ItemImages (
--     ImageID INT PRIMARY KEY AUTO_INCREMENT,
--     ItemID INT,
--     ImageURL VARCHAR(255),
--     FOREIGN KEY (ItemID) REFERENCES MenuItems(ItemID)
-- );

-- Customer Orders
CREATE TABLE CustomerOrders (
    OrderID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT,
    OrderStatus ENUM('Placed', 'InProgress', 'Completed') NOT NULL,
    OrderTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    TotalAmount DECIMAL(10, 2) NOT NULL,
    TableNumber INT,
    PaymentStatus ENUM('Paid','Pending') NOT NULL DEFAULT 'Pending',
    FOREIGN KEY (UserID) REFERENCES UserProfiles(UserID)
);

-- Online Payments
CREATE TABLE OnlinePayments (
    PaymentID INT PRIMARY KEY AUTO_INCREMENT,
    OrderID INT,
    PaymentAmount DECIMAL(10, 2) NOT NULL,
    PaymentStatus ENUM('Pending', 'Completed') NOT NULL,
    TransactionDateTime DATETIME NOT NULL,
    PaymentMethod ENUM('Online', 'Counter') NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES CustomerOrders(OrderID)
);
-- Order Items
CREATE TABLE OrderItems (
    OrderItemID INT PRIMARY KEY AUTO_INCREMENT,
    OrderID INT,
    ItemID INT,
    Quantity INT NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES CustomerOrders(OrderID),
    FOREIGN KEY (ItemID) REFERENCES MenuItems(ItemID)
);

-- Carts
CREATE TABLE Carts (
    CartID INT PRIMARY KEY AUTO_INCREMENT,
    UserID INT,
    FOREIGN KEY (UserID) REFERENCES UserProfiles(UserID)
);

-- Cart Items
CREATE TABLE CartItems (
    CartItemID INT PRIMARY KEY AUTO_INCREMENT,
    CartID INT,
    ItemID INT,
    Quantity INT NOT NULL,
    FOREIGN KEY (CartID) REFERENCES Carts(CartID),
    FOREIGN KEY (ItemID) REFERENCES MenuItems(ItemID)
);

-- Timers
CREATE TABLE Timers (
    TimerID INT PRIMARY KEY AUTO_INCREMENT,
    OrderID INT,
    StartTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    EndTime TIMESTAMP NULL,
    FOREIGN KEY (OrderID) REFERENCES CustomerOrders(OrderID)
);

-- Mac Addresses
-- CREATE TABLE MacAddresses (
--     UserID INT,
--     MacAddress VARCHAR(17) PRIMARY KEY,
--     FOREIGN KEY (UserID) REFERENCES UserProfiles(UserID)
-- );
