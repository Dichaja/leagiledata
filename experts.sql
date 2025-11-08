-- Experts Management System Database Schema

-- Experts table
CREATE TABLE IF NOT EXISTS experts (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    title VARCHAR(100) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    bio TEXT,
    education TEXT,
    experience_years INT DEFAULT 0,
    hourly_rate DECIMAL(10,2) DEFAULT 0.00,
    profile_image VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected', 'suspended') DEFAULT 'pending',
    rating DECIMAL(3,2) DEFAULT 0.00,
    total_reviews INT DEFAULT 0,
    total_consultations INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Expert specialties table
CREATE TABLE IF NOT EXISTS expert_specialties (
    id VARCHAR(36) PRIMARY KEY,
    expert_id VARCHAR(36) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expert_id) REFERENCES experts(id) ON DELETE CASCADE
);

-- Expert categories table
CREATE TABLE IF NOT EXISTS expert_categories (
    id VARCHAR(36) PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Expert category assignments
CREATE TABLE IF NOT EXISTS expert_category_assignments (
    id VARCHAR(36) PRIMARY KEY,
    expert_id VARCHAR(36) NOT NULL,
    category_id VARCHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expert_id) REFERENCES experts(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES expert_categories(id) ON DELETE CASCADE
);

-- Consultation requests table
CREATE TABLE IF NOT EXISTS consultation_requests (
    id VARCHAR(36) PRIMARY KEY,
    user_id VARCHAR(36) NOT NULL,
    expert_id VARCHAR(36) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    preferred_date DATE,
    preferred_time TIME,
    duration_hours DECIMAL(3,1) DEFAULT 1.0,
    budget DECIMAL(10,2),
    status ENUM('pending', 'accepted', 'rejected', 'completed', 'cancelled') DEFAULT 'pending',
    expert_response TEXT,
    scheduled_date DATETIME,
    meeting_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (expert_id) REFERENCES experts(id) ON DELETE CASCADE
);

-- Expert reviews table
CREATE TABLE IF NOT EXISTS expert_reviews (
    id VARCHAR(36) PRIMARY KEY,
    expert_id VARCHAR(36) NOT NULL,
    user_id VARCHAR(36) NOT NULL,
    consultation_id VARCHAR(36),
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expert_id) REFERENCES experts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (consultation_id) REFERENCES consultation_requests(id) ON DELETE SET NULL
);

-- Expert availability table
CREATE TABLE IF NOT EXISTS expert_availability (
    id VARCHAR(36) PRIMARY KEY,
    expert_id VARCHAR(36) NOT NULL,
    day_of_week ENUM('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (expert_id) REFERENCES experts(id) ON DELETE CASCADE
);

-- Insert default categories
INSERT IGNORE INTO expert_categories (id, name, description) VALUES
('cat-1', 'Business & Finance', 'Business strategy, financial analysis, market research'),
('cat-2', 'Technology', 'Software development, AI, data science, cybersecurity'),
('cat-3', 'Science & Medicine', 'Research, healthcare, pharmaceuticals, biotechnology'),
('cat-4', 'Marketing & Sales', 'Digital marketing, sales strategy, brand management'),
('cat-5', 'Legal & Compliance', 'Legal advice, regulatory compliance, intellectual property'),
('cat-6', 'Education & Training', 'Academic research, curriculum development, training programs');