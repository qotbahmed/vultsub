-- Create academy_requests table
CREATE TABLE IF NOT EXISTS `academy_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `academy_name` varchar(255) NOT NULL,
  `manager_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text,
  `city` varchar(100),
  `branches_count` int(11) DEFAULT 1,
  `sports` text,
  `description` text,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `requested_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `approved_at` timestamp NULL,
  `rejected_at` timestamp NULL,
  `notes` text,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_requested_at` (`requested_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO `academy_requests` (`academy_name`, `manager_name`, `email`, `phone`, `address`, `city`, `branches_count`, `sports`, `description`, `status`, `requested_at`) VALUES
('أكاديمية النجوم الرياضية', 'أحمد محمد العلي', 'ahmed@stars-sports.com', '+966501234567', 'شارع الملك فهد، الرياض', 'الرياض', 3, 'كرة القدم,كرة السلة,السباحة', 'أكاديمية متخصصة في تدريب الشباب على الرياضات المختلفة', 'pending', '2024-09-15 10:30:00'),
('نادي الأبطال الرياضي', 'فاطمة أحمد السعيد', 'fatima@champions-club.com', '+966507654321', 'شارع التحلية، جدة', 'جدة', 2, 'كرة القدم,الجمباز', 'نادي رياضي يهدف لتنمية المواهب الشابة', 'pending', '2024-09-14 14:20:00'),
('مركز التميز الرياضي', 'محمد عبدالله القحطاني', 'mohammed@excellence-sports.com', '+966503456789', 'شارع الكورنيش، الدمام', 'الدمام', 5, 'كرة القدم,كرة السلة,التنس,السباحة', 'مركز متطور لتدريب الرياضيين المحترفين', 'approved', '2024-09-12 09:15:00'),
('أكاديمية النخبة الرياضية', 'سارة محمد الأحمد', 'sara@elite-sports.com', '+966504567890', 'شارع العليا، الرياض', 'الرياض', 1, 'التنس,الجمباز', 'أكاديمية متخصصة في الرياضات الفردية', 'rejected', '2024-09-10 16:45:00');
