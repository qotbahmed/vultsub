-- Create players table with updated structure
CREATE TABLE IF NOT EXISTS `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `id_number` varchar(50) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `e_phone` varchar(20) DEFAULT NULL,
  `address` text,
  `notes` text,
  `image_path` varchar(255) DEFAULT NULL,
  `image_base_url` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `paid` decimal(10,2) DEFAULT 0.00,
  `academy_id` int(11) DEFAULT NULL,
  `subscription_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `sport` varchar(100) DEFAULT NULL,
  `attendance_rate` decimal(5,2) DEFAULT 0.00,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_academy_id` (`academy_id`),
  KEY `idx_status` (`status`),
  KEY `idx_sport` (`sport`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO `players` (`name`, `nationality`, `id_number`, `phone`, `address`, `dob`, `academy_id`, `sport`, `attendance_rate`, `status`, `paid`) VALUES
('أحمد محمد العلي', 'سعودي', '1234567890', '+966501234567', 'الرياض، المملكة العربية السعودية', '2008-05-15', 1, 'كرة القدم', 85.00, 'active', 299.00),
('سارة أحمد السعيد', 'سعودي', '0987654321', '+966507654321', 'جدة، المملكة العربية السعودية', '2010-03-22', 1, 'كرة السلة', 92.00, 'active', 799.00),
('محمد عبدالله القحطاني', 'سعودي', '1122334455', '+966503456789', 'الدمام، المملكة العربية السعودية', '2007-08-10', 1, 'السباحة', 60.00, 'suspended', 199.00),
('فاطمة علي المحمود', 'سعودي', '5566778899', '+966504567890', 'الرياض، المملكة العربية السعودية', '2009-12-03', 1, 'التنس', 78.00, 'active', 399.00),
('عبدالرحمن سعد العتيبي', 'سعودي', '9988776655', '+966505678901', 'الرياض، المملكة العربية السعودية', '2006-07-18', 1, 'كرة القدم', 95.00, 'active', 299.00);
