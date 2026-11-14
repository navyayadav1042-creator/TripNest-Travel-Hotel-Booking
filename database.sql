CREATE DATABASE IF NOT EXISTS tripnest_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tripnest_db;

CREATE TABLE users (
 user_id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(150) UNIQUE NOT NULL,
 password VARCHAR(255) NOT NULL,
 phone VARCHAR(15),
 role ENUM('user','admin') DEFAULT 'user',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE destinations (
 destination_id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 country VARCHAR(100) NOT NULL,
 description TEXT,
 image VARCHAR(255)
);
CREATE TABLE hotels (
 hotel_id INT AUTO_INCREMENT PRIMARY KEY,
 destination_id INT NOT NULL,
 hotel_name VARCHAR(150) NOT NULL,
 address VARCHAR(255),
 description TEXT,
 rating DECIMAL(2,1) DEFAULT 0,
 image VARCHAR(255),
 FOREIGN KEY (destination_id) REFERENCES destinations(destination_id)
);
CREATE TABLE rooms (
 room_id INT AUTO_INCREMENT PRIMARY KEY,
 hotel_id INT NOT NULL,
 room_type VARCHAR(100) NOT NULL,
 price DECIMAL(10,2) NOT NULL,
 total_rooms INT NOT NULL DEFAULT 1,
 FOREIGN KEY (hotel_id) REFERENCES hotels(hotel_id)
);
CREATE TABLE bookings (
 booking_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 room_id INT NOT NULL,
 check_in DATE NOT NULL,
 check_out DATE NOT NULL,
 guests INT NOT NULL,
 number_of_rooms INT NOT NULL DEFAULT 1,
 total_amount DECIMAL(10,2) NOT NULL,
 status ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
 booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(user_id),
 FOREIGN KEY (room_id) REFERENCES rooms(room_id)
);
CREATE TABLE payments (
 payment_id INT AUTO_INCREMENT PRIMARY KEY,
 booking_id INT NOT NULL,
 amount DECIMAL(10,2) NOT NULL,
 payment_method VARCHAR(50),
 payment_status ENUM('pending','success','failed') DEFAULT 'pending',
 payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
);
CREATE TABLE reviews (
 review_id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 hotel_id INT NOT NULL,
 rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
 comment TEXT,
 review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(user_id),
 FOREIGN KEY (hotel_id) REFERENCES hotels(hotel_id)
);

INSERT INTO destinations (name,country,description,image) VALUES
('Goa','India','Sunsets, beaches and laid-back coastal escapes.','https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?w=1000'),
('Jaipur','India','A colourful city of palaces, forts and culture.','https://images.unsplash.com/photo-1599661046827-dacff0c0f09a?w=1000'),
('Munnar','India','Green hills, tea gardens and cool mountain air.','https://images.unsplash.com/photo-1562649846-6c9b7c3d2f1c?w=1000'),
('Hyderabad','India','A lively city with history, food and modern comforts.','https://images.unsplash.com/photo-1572445271230-a78b5944a659?w=1000'),
('Bengaluru','India','A vibrant city break with gardens and cafes.','https://images.unsplash.com/photo-1596176530529-78163a4f7af2?w=1000'),
('Ooty','India','A classic hill station surrounded by misty landscapes.','https://images.unsplash.com/photo-1582510003544-4d00b7f74220?w=1000');

INSERT INTO hotels (destination_id,hotel_name,address,description,rating,image) VALUES
(1,'Seabreeze Retreat','Candolim, Goa','A relaxed coastal stay close to the beach, with bright rooms and welcoming spaces.',4.6,'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=1000'),
(1,'Palm Grove Hotel','Calangute, Goa','Comfortable rooms for travellers looking to explore the coastline.',4.2,'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1000'),
(2,'The Pink City House','Civil Lines, Jaipur','A stylish base for exploring Jaipur’s historic sights and local markets.',4.7,'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=1000'),
(3,'Misty Hills Lodge','Chithirapuram, Munnar','A peaceful mountain retreat surrounded by greenery.',4.8,'https://images.unsplash.com/photo-1445019980597-93fa8acb246c?w=1000'),
(4,'Charminar Grand','Abids, Hyderabad','A city hotel with convenient access to food, shopping and heritage areas.',4.1,'https://images.unsplash.com/photo-1564501049412-61c2a3083791?w=1000'),
(5,'Garden City Suites','Indiranagar, Bengaluru','Modern rooms in a lively neighbourhood.',4.3,'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=1000');

INSERT INTO rooms (hotel_id,room_type,price,total_rooms) VALUES
(1,'Deluxe King Room',4200,8),(1,'Sea View Suite',6800,3),
(2,'Standard Double Room',2800,10),(2,'Family Room',3900,4),
(3,'Heritage Deluxe Room',5100,6),(3,'Royal Suite',8500,2),
(4,'Valley View Room',4600,7),(4,'Premium Cottage',7200,3),
(5,'Classic City Room',3200,9),(5,'Executive Room',4900,4),
(6,'Studio Room',3500,8),(6,'Business Suite',5600,3);
