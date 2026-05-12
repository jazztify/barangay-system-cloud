-- Enumerated Types
CREATE TYPE civil_status_enum AS ENUM ('Single', 'Married', 'Widowed', 'Separated', 'Divorced');
CREATE TYPE sex_enum AS ENUM ('Male', 'Female');
CREATE TYPE blotter_status_enum AS ENUM ('Unresolved', 'Resolved', 'Dismissed', 'Mediated');
CREATE TYPE user_role_enum AS ENUM ('admin', 'secretary', 'captain', 'kagawad');

-- Households Table
CREATE TABLE households (
    household_id SERIAL PRIMARY KEY,
    household_no VARCHAR(50) UNIQUE,
    purok_sitio VARCHAR(100) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Residents Table
CREATE TABLE residents (
    resident_id SERIAL PRIMARY KEY,
    household_id INT REFERENCES households(household_id) ON DELETE SET NULL,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    suffix VARCHAR(10),
    date_of_birth DATE,
    sex sex_enum,
    civil_status civil_status_enum,
    nationality VARCHAR(50) DEFAULT 'Filipino',
    religion VARCHAR(100),
    occupation VARCHAR(100),
    educational_attainment VARCHAR(100),
    voter_status BOOLEAN DEFAULT FALSE,
    philsys_card_no VARCHAR(20) UNIQUE,
    contact_number VARCHAR(20),
    pwd_flag BOOLEAN DEFAULT FALSE,
    senior_citizen_flag BOOLEAN DEFAULT FALSE,
    solo_parent_flag BOOLEAN DEFAULT FALSE,
    is_alive BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blotter Records Table
CREATE TABLE blotter_records (
    blotter_id SERIAL PRIMARY KEY,
    complainant_id INT REFERENCES residents(resident_id) ON DELETE CASCADE,
    respondent_id INT REFERENCES residents(resident_id) ON DELETE CASCADE,
    incident_type VARCHAR(100) NOT NULL,
    incident_date DATE NOT NULL,
    narrative TEXT NOT NULL,
    status blotter_status_enum DEFAULT 'Unresolved',
    resolution_notes TEXT,
    resolution_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users Table
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role user_role_enum DEFAULT 'secretary',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Issuances Table
CREATE TABLE issuances (
    issuance_id SERIAL PRIMARY KEY,
    resident_id INT REFERENCES residents(resident_id) ON DELETE CASCADE,
    issued_by INT REFERENCES users(user_id) ON DELETE SET NULL,
    document_type VARCHAR(100) NOT NULL,
    control_number VARCHAR(50) UNIQUE NOT NULL,
    or_number VARCHAR(50),
    purpose VARCHAR(255),
    additional_data TEXT,
    verification_token VARCHAR(255) UNIQUE,
    valid_until TIMESTAMP,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Audit Trail Table
CREATE TABLE IF NOT EXISTS audit_trail (
    audit_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id) ON DELETE SET NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blotter summons table for patawag tracking
CREATE TABLE IF NOT EXISTS blotter_summons (
    summons_id SERIAL PRIMARY KEY,
    blotter_id INT REFERENCES blotter_records(blotter_id) ON DELETE CASCADE,
    summons_date DATE NOT NULL,
    summons_type VARCHAR(50) DEFAULT 'Patawag',
    notes TEXT,
    appeared BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Indexes for Performance
CREATE INDEX idx_residents_household ON residents(household_id);
CREATE INDEX idx_residents_last_name ON residents(last_name);
CREATE INDEX idx_blotter_respondent ON blotter_records(respondent_id);
CREATE INDEX idx_blotter_status ON blotter_records(status);
CREATE INDEX idx_issuances_token ON issuances(verification_token);
CREATE INDEX idx_issuances_resident ON issuances(resident_id);
CREATE INDEX IF NOT EXISTS idx_audit_trail_user ON audit_trail(user_id);
CREATE INDEX IF NOT EXISTS idx_audit_trail_entity ON audit_trail(entity_type, entity_id);
CREATE INDEX IF NOT EXISTS idx_blotter_summons_blotter ON blotter_summons(blotter_id);

-- Seed an Admin User (password is 'admin123')
INSERT INTO users (username, password_hash, full_name, role) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin');
