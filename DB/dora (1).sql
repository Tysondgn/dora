
CREATE TABLE `countercheck` (
  `CounterID` int(11) NOT NULL,
  `CustomerName` varchar(50) NOT NULL,
  `CustomerPhone` varchar(10) NOT NULL,
  `TableNumber` varchar(15) NOT NULL,
  `MegaCart` varchar(1000) NOT NULL,
  `GrandTotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `customerorders` (
  `OrderID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `OrderStatus` enum('Placed','Rejected','InProgress','Completed') NOT NULL,
  `OrderTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalAmount` decimal(10,2) NOT NULL,
  `GrandTotal` int(11) NOT NULL,
  `TableNumber` varchar(15) DEFAULT NULL,
  `json_cart` varchar(1000) NOT NULL,
  `DishDescription` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `customerprofile` (
  `CustomerID` int(11) NOT NULL,
  `CustomerName` varchar(50) NOT NULL,
  `CustomerPhone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `menuitems` (
  `ItemID` int(11) NOT NULL,
  `ItemName` varchar(255) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Description` text DEFAULT NULL,
  `PreparationTime` int(11) DEFAULT NULL,
  `ImageURL` varchar(225) NOT NULL,
  `VideoURL` varchar(255) DEFAULT NULL,
  `cuisine` varchar(30) NOT NULL,
  `category` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `onlinepayments` (
  `PaymentID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `OrderID` varchar(100) NOT NULL,
  `MegaCart` varchar(1000) NOT NULL,
  `PaymentAmount` decimal(10,2) NOT NULL,
  `TableNumber` varchar(15) NOT NULL,
  `PaymentStatus` enum('Pending','Completed') NOT NULL,
  `TransactionDateTime` datetime NOT NULL,
  `PaymentMethod` enum('Online','Counter') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

CREATE TABLE `userprofiles` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `UserType` enum('Admin','CounterStaff','KitchenStaff','Customer') NOT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
