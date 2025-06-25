-- Testusers for the website
INSERT INTO [Users] (username, password, first_name, last_name, address, role)
VALUES
('TestK', '$2y$10$JxXqMy1jn5Y1Wfe4h8TR4.xRkGle4Z.SLkdjyPEISEZNOX3ZvzZBq', 'Test', 'Klant', 'Ruitenberglaan 26', 'Client'),
('TestM', '$2y$10$9CusBW5fdlKPqkOVhhzDm.kKAW3VEqR622kitcc0Dmns7cShU./Qa', 'Test', 'Medewerker', 'Ruitenberglaan 26', 'Medewerker');

-- Testuser credentials:

-- Test klant user
-- username= TestK
-- password= Test@123

-- Test medewerker user
-- username= TestM
-- password= Test@123