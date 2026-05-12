-- Sample Households
INSERT INTO households (household_no, purok_sitio, address) VALUES
('HH-2024-001', 'Purok 1 - Sampaguita', 'Block 5, Lot 12, Brgy. Sample, Cabanatuan City'),
('HH-2024-002', 'Purok 2 - Rosal', '123 Rizal St., Brgy. Sample, Cabanatuan City'),
('HH-2024-003', 'Purok 3 - Jasmin', '456 Mabini Ave., Brgy. Sample, Cabanatuan City');

-- Sample Residents
INSERT INTO residents (household_id, last_name, first_name, middle_name, date_of_birth, sex, civil_status, nationality, religion, occupation, educational_attainment, voter_status, contact_number, pwd_flag, senior_citizen_flag, solo_parent_flag)
VALUES
(1, 'DELA CRUZ', 'JUAN', 'SANTOS', '1985-03-15', 'Male', 'Married', 'Filipino', 'Roman Catholic', 'Farmer', 'College Graduate', TRUE, '09171234567', FALSE, FALSE, FALSE),
(1, 'DELA CRUZ', 'MARIA', 'REYES', '1988-07-22', 'Female', 'Married', 'Filipino', 'Roman Catholic', 'Housewife', 'College Graduate', TRUE, '09179876543', FALSE, FALSE, FALSE),
(2, 'SANTOS', 'PEDRO', 'GARCIA', '1960-11-05', 'Male', 'Widowed', 'Filipino', 'Roman Catholic', 'Retired', 'High School Graduate', TRUE, '09181112233', FALSE, TRUE, FALSE),
(2, 'SANTOS', 'ANA', 'LOZANO', '1995-01-30', 'Female', 'Single', 'Filipino', 'Roman Catholic', 'Teacher', 'College Graduate', TRUE, '09182223344', FALSE, FALSE, TRUE),
(3, 'REYES', 'CARLOS', 'MENDOZA', '2000-06-18', 'Male', 'Single', 'Filipino', 'Iglesia ni Cristo', 'Student', 'College Level', FALSE, '09193334455', TRUE, FALSE, FALSE);
