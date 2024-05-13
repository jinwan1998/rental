CREATE DATABASE IF NOT EXISTS res;

USE res;

CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('System Administrator', 'Real Estate Agent', 'Buyer', 'Seller') NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE UserProfiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    address TEXT,
    profile_picture VARCHAR(255),
    bio TEXT,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

CREATE TABLE PropertyListings (
    listing_id INT AUTO_INCREMENT PRIMARY KEY,
    agent_id INT,
    title VARCHAR(255),
    description TEXT,
    property_type VARCHAR(100),
    price DECIMAL(12, 2),
    location VARCHAR(255),
    status ENUM('Active', 'Sold', 'Expired') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (agent_id) REFERENCES Users(user_id) ON DELETE SET NULL
);

CREATE TABLE PropertyInteractions (
    interaction_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    listing_id INT,
    interaction_type VARCHAR(50),
    interaction_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES PropertyListings(listing_id) ON DELETE CASCADE
);

CREATE TABLE Reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    agent_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comments TEXT,
    review_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES Users(user_id) ON DELETE SET NULL
);

CREATE TABLE SavedListings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT,
    listing_id INT,
    saved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES PropertyListings(listing_id) ON DELETE CASCADE
);

CREATE TABLE Transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(12, 2),
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

INSERT INTO Users (username, password, role, email, phone)
VALUES
    ('admin1', 'password123', 'System Administrator', 'admin1@example.com', '1234567890'),
    ('agent1', 'agentpass', 'Real Estate Agent', 'agent1@example.com', '9876543210'),
    ('buyer1', 'buyerpass', 'Buyer', 'buyer1@example.com', '5551234567'),
    ('seller1', 'sellerpass', 'Seller', 'seller1@example.com', '9998765432');

INSERT INTO UserProfiles (user_id, first_name, last_name, address, profile_picture, bio)
VALUES
    (1, 'Admin', 'One', '123 Admin St, Adminville', 'admin.jpg', 'I am the system administrator.'),
    (2, 'Agent', 'Smith', '456 Agent Rd, Agent City', 'agent.jpg', 'Experienced real estate agent in Singapore.'),
    (3, 'John', 'Doe', '789 Buyer Ave, Buyer Town', 'buyer.jpg', 'Looking to buy a new home in Singapore.'),
    (4, 'Jane', 'Doe', '321 Seller Blvd, Seller Springs', 'seller.jpg', 'Selling properties in Singapore.');

INSERT INTO PropertyListings (agent_id, title, description, property_type, price, location)
VALUES
    (2, 'Luxury Condo with Marina View', 'Spacious waterfront condo with stunning views.', 'Condo', 1500000, 'Marina Bay'),
    (2, 'Family-Friendly HDB Apartment', 'Renovated 4-room HDB flat in central location.', 'HDB', 600000, 'Tiong Bahru'),
    (2, 'Prime Office Space', 'Commercial office space in CBD area.', 'Office', 2500000, 'Raffles Place'),
    (4, 'Bungalow with Garden', 'Exclusive bungalow with lush garden.', 'House', 5000000, 'Sentosa Cove');

INSERT INTO PropertyInteractions (user_id, listing_id, interaction_type)
VALUES
    (3, 1, 'View'),
    (3, 2, 'Save'),
    (4, 1, 'View'),
    (4, 2, 'Rate');

INSERT INTO Reviews (user_id, agent_id, rating, comments)
VALUES
    (3, 2, 4, 'Agent was very helpful and knowledgeable about properties in Singapore.'),
    (4, 2, 5, 'Excellent service from the agent. Highly recommended for property transactions in Singapore.');

INSERT INTO SavedListings (buyer_id, listing_id)
VALUES
    (3, 1),
    (3, 2),
    (4, 1);
