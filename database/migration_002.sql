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

CREATE INDEX IF NOT EXISTS idx_audit_trail_user ON audit_trail(user_id);
CREATE INDEX IF NOT EXISTS idx_audit_trail_entity ON audit_trail(entity_type, entity_id);
CREATE INDEX IF NOT EXISTS idx_blotter_summons_blotter ON blotter_summons(blotter_id);
