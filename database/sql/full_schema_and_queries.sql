-- ==============================================================================
-- 🎓 COACHING & CLASS MANAGEMENT SYSTEM — PRODUCTION DATABASE SCHEMA & QUERIES
-- Target RDBMS: MySQL 8.0+ / MariaDB 10.6+
-- Engine: InnoDB | Character Set: utf8mb4 | Collation: utf8mb4_unicode_ci
-- ==============================================================================
-- EXECUTION ORDER:
-- 1. Database Creation & Context Switch
-- 2. Drop existing foreign keys and tables in reverse dependency order
-- 3. Create core authentication, roles, permissions, and system navigation tables
-- 4. Create academic entities (Staff, Courses, Batches, Students, Student_Batch)
-- 5. Create financial tables (Fee Plans, Fee Payments, Refunds, Expenses)
-- 6. Create academic operations (Attendance, Leaves, Tests, Test Results)
-- 7. Create communication & system tables (Notifications, Announcements, Settings, Audit Logs, Holidays)
-- 8. Create composite performance indexes on high-throughput lookup columns
-- 9. Insert foundational seeds (Super Admin, Roles, 40+ Spatie Permissions, System Menus, Settings, Templates)
-- 10. Comprehensive operational & reporting SQL queries (with Recursive Menu CTE & Financial Aggregates)
-- ==============================================================================

-- 1. DATABASE CREATION
CREATE DATABASE IF NOT EXISTS `coaching_management`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `coaching_management`;

-- ------------------------------------------------------------------------------
-- 2. REVERSE CLEANUP (DROP IN REVERSE FK ORDER)
-- ------------------------------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `audit_logs`;
DROP TABLE IF EXISTS `holidays`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `notification_templates`;
DROP TABLE IF EXISTS `expenses`;
DROP TABLE IF EXISTS `test_results`;
DROP TABLE IF EXISTS `tests`;
DROP TABLE IF EXISTS `leave_applications`;
DROP TABLE IF EXISTS `attendance`;
DROP TABLE IF EXISTS `refunds`;
DROP TABLE IF EXISTS `fee_payments`;
DROP TABLE IF EXISTS `fee_plans`;
DROP TABLE IF EXISTS `student_batch`;
DROP TABLE IF EXISTS `students`;
DROP TABLE IF EXISTS `batches`;
DROP TABLE IF EXISTS `courses`;
DROP TABLE IF EXISTS `staff`;
DROP TABLE IF EXISTS `system_menus`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `role_has_permissions`;
DROP TABLE IF EXISTS `model_has_roles`;
DROP TABLE IF EXISTS `model_has_permissions`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache`;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------------------------
-- 3. CORE AUTHENTICATION & SPATIE PERMISSIONS TABLES
-- ------------------------------------------------------------------------------

CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(191) NOT NULL,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `phone` VARCHAR(32) NULL UNIQUE,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `user_type` ENUM('admin', 'teacher', 'accountant', 'receptionist', 'student', 'parent') NOT NULL DEFAULT 'admin',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE `roles` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(125) NOT NULL,
    `guard_name` VARCHAR(125) NOT NULL DEFAULT 'web',
    `description` VARCHAR(255) NULL,
    `is_default` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `roles_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB;

CREATE TABLE `permissions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(125) NOT NULL,
    `guard_name` VARCHAR(125) NOT NULL DEFAULT 'web',
    `module` VARCHAR(64) NOT NULL DEFAULT 'general',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `permissions_name_guard_name_unique` (`name`, `guard_name`)
) ENGINE=InnoDB;

CREATE TABLE `model_has_roles` (
    `role_id` BIGINT UNSIGNED NOT NULL,
    `model_type` VARCHAR(191) NOT NULL,
    `model_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`role_id`, `model_id`, `model_type`),
    INDEX `model_has_roles_model_id_model_type_index` (`model_id`, `model_type`),
    CONSTRAINT `fk_model_has_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `role_has_permissions` (
    `permission_id` BIGINT UNSIGNED NOT NULL,
    `role_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`permission_id`, `role_id`),
    INDEX `role_has_permissions_role_id_index` (`role_id`),
    CONSTRAINT `fk_rhp_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_rhp_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------------------------
-- 4. SYSTEM NAVIGATION / DYNAMIC SIDEBAR
-- ------------------------------------------------------------------------------

CREATE TABLE `system_menus` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `parent_id` BIGINT UNSIGNED NULL,
    `name` VARCHAR(100) NOT NULL,
    `icon` VARCHAR(60) NOT NULL DEFAULT 'bi-circle',
    `route_name` VARCHAR(150) NULL,
    `url` VARCHAR(255) NOT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_visible` TINYINT(1) NOT NULL DEFAULT 1,
    `permission_code` VARCHAR(125) NULL,
    `created_by` BIGINT UNSIGNED NULL,
    `updated_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_menu_parent` (`parent_id`),
    INDEX `idx_menu_sort` (`sort_order`),
    INDEX `idx_menu_perm` (`permission_code`),
    CONSTRAINT `fk_system_menus_parent` FOREIGN KEY (`parent_id`) REFERENCES `system_menus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------------------------
-- 5. ACADEMIC & STAFF ENTITIES
-- ------------------------------------------------------------------------------

CREATE TABLE `staff` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `employee_code` VARCHAR(50) NOT NULL UNIQUE,
    `designation` VARCHAR(100) NOT NULL,
    `qualification` VARCHAR(150) NULL,
    `subjects` JSON NULL, -- Array of subjects taught
    `joining_date` DATE NOT NULL,
    `salary` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `photo_path` VARCHAR(255) NULL,
    `emergency_contact` VARCHAR(32) NULL,
    `status` ENUM('active', 'suspended', 'resigned') NOT NULL DEFAULT 'active',
    `notes` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX `idx_staff_status` (`status`),
    INDEX `idx_staff_code` (`employee_code`),
    CONSTRAINT `fk_staff_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `courses` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL UNIQUE,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `category` ENUM('Class 8-10', 'Class 11-12', 'JEE (Main+Adv)', 'NEET Medical', 'Foundation') NOT NULL,
    `duration_months` TINYINT UNSIGNED NOT NULL DEFAULT 12,
    `total_fee` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `admission_fee` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `installments_allowed` TINYINT UNSIGNED NOT NULL DEFAULT 3,
    `status` ENUM('active', 'archived') NOT NULL DEFAULT 'active',
    `description` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX `idx_courses_category` (`category`),
    INDEX `idx_courses_status` (`status`)
) ENGINE=InnoDB;

CREATE TABLE `batches` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `course_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(120) NOT NULL,
    `teacher_id` BIGINT UNSIGNED NOT NULL,
    `co_teacher_id` BIGINT UNSIGNED NULL,
    `start_date` DATE NOT NULL,
    `end_date` DATE NOT NULL,
    `class_days` JSON NOT NULL, -- e.g. ["Mon", "Wed", "Fri"]
    `start_time` TIME NOT NULL,
    `end_time` TIME NOT NULL,
    `room_number` VARCHAR(50) NOT NULL,
    `max_capacity` INT UNSIGNED NOT NULL DEFAULT 40,
    `enrolled_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `status` ENUM('upcoming', 'ongoing', 'completed', 'cancelled') NOT NULL DEFAULT 'upcoming',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY `unique_batch_per_course` (`course_id`, `name`),
    INDEX `idx_batches_teacher` (`teacher_id`),
    INDEX `idx_batches_status` (`status`),
    INDEX `idx_batches_course` (`course_id`),
    CONSTRAINT `fk_batches_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_batches_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `staff` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_batches_co_teacher` FOREIGN KEY (`co_teacher_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `students` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `roll_number` VARCHAR(50) NOT NULL UNIQUE,
    `gender` ENUM('male', 'female', 'other') NOT NULL,
    `dob` DATE NOT NULL,
    `school_name` VARCHAR(150) NULL,
    `current_class` VARCHAR(50) NOT NULL,
    `parent_name` VARCHAR(150) NOT NULL,
    `parent_phone` VARCHAR(32) NOT NULL,
    `parent_email` VARCHAR(191) NULL,
    `address_line1` VARCHAR(255) NOT NULL,
    `address_line2` VARCHAR(255) NULL,
    `city` VARCHAR(100) NOT NULL,
    `state` VARCHAR(100) NOT NULL,
    `zip` VARCHAR(20) NOT NULL,
    `admission_date` DATE NOT NULL,
    `total_fee` DECIMAL(12,2) NOT NULL,
    `discount_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `discount_reason` ENUM('None', 'Sibling', 'Merit', 'Staff Ward', 'Economic Need', 'Other') NOT NULL DEFAULT 'None',
    `final_fee` DECIMAL(12,2) NOT NULL,
    `paid_fee` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `outstanding_fee` DECIMAL(12,2) NOT NULL,
    `payment_plan` ENUM('full', 'installment_2', 'installment_3', 'installment_4', 'monthly') NOT NULL DEFAULT 'installment_3',
    `photo_path` VARCHAR(255) NULL,
    `id_proof_path` VARCHAR(255) NULL,
    `status` ENUM('active', 'inactive', 'graduated', 'suspended') NOT NULL DEFAULT 'active',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    INDEX `idx_students_roll` (`roll_number`),
    INDEX `idx_students_parent_phone` (`parent_phone`),
    INDEX `idx_students_fee_status` (`outstanding_fee`),
    INDEX `idx_students_status` (`status`),
    CONSTRAINT `fk_students_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `student_batch` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `batch_id` BIGINT UNSIGNED NOT NULL,
    `assigned_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `transferred_at` TIMESTAMP NULL DEFAULT NULL,
    `is_primary` TINYINT(1) NOT NULL DEFAULT 1,
    `status` ENUM('active', 'transferred', 'dropped') NOT NULL DEFAULT 'active',
    INDEX `idx_sb_student` (`student_id`),
    INDEX `idx_sb_batch` (`batch_id`),
    UNIQUE KEY `unique_student_batch_active` (`student_id`, `batch_id`, `status`),
    CONSTRAINT `fk_sb_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_sb_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------------------------
-- 6. FINANCIAL MANAGEMENT & LEDGER
-- ------------------------------------------------------------------------------

CREATE TABLE `fee_plans` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `installment_number` TINYINT UNSIGNED NOT NULL,
    `due_date` DATE NOT NULL,
    `amount` DECIMAL(12,2) NOT NULL,
    `paid_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `balance_amount` DECIMAL(12,2) NOT NULL,
    `late_fee_per_day` DECIMAL(8,2) NOT NULL DEFAULT 20.00,
    `grace_days` TINYINT UNSIGNED NOT NULL DEFAULT 5,
    `status` ENUM('pending', 'partial', 'paid', 'overdue') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_fee_plans_due_status` (`due_date`, `status`),
    INDEX `idx_fee_plans_student` (`student_id`),
    CONSTRAINT `fk_fee_plans_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `fee_payments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `receipt_number` VARCHAR(60) NOT NULL UNIQUE,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `fee_plan_id` BIGINT UNSIGNED NULL,
    `amount_paid` DECIMAL(12,2) NOT NULL,
    `late_fee_charged` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `late_fee_waived` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    `waiver_reason` VARCHAR(255) NULL,
    `payment_method` ENUM('cash', 'upi', 'card', 'net_banking', 'cheque', 'dd') NOT NULL,
    `reference_number` VARCHAR(100) NULL,
    `payment_date` DATE NOT NULL,
    `collected_by` BIGINT UNSIGNED NOT NULL,
    `receipt_note` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_payments_date` (`payment_date`),
    INDEX `idx_payments_student` (`student_id`),
    INDEX `idx_payments_receipt` (`receipt_number`),
    CONSTRAINT `fk_fee_payments_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_fee_payments_plan` FOREIGN KEY (`fee_plan_id`) REFERENCES `fee_plans` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_fee_payments_collector` FOREIGN KEY (`collected_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `refunds` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `refund_number` VARCHAR(60) NOT NULL UNIQUE,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `total_fee_paid` DECIMAL(12,2) NOT NULL,
    `refund_amount` DECIMAL(12,2) NOT NULL,
    `refund_method` ENUM('original_method', 'cash', 'bank_transfer', 'cheque') NOT NULL,
    `reason` ENUM('batch_cancelled', 'student_withdrawal', 'duplicate_payment', 'medical_grounds', 'other') NOT NULL,
    `reason_notes` TEXT NULL,
    `approved_by` BIGINT UNSIGNED NOT NULL,
    `refund_date` DATE NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_refunds_student` (`student_id`),
    CONSTRAINT `fk_refunds_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_refunds_approver` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `expenses` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `expense_number` VARCHAR(60) NOT NULL UNIQUE,
    `category` ENUM('Salary', 'Printing & Stationery', 'Utilities', 'Rent & Maintenance', 'Hardware & Software', 'Marketing & Events', 'Refreshments', 'Misc') NOT NULL,
    `amount` DECIMAL(12,2) NOT NULL,
    `expense_date` DATE NOT NULL,
    `paid_to` VARCHAR(150) NOT NULL,
    `payment_method` ENUM('cash', 'bank_transfer', 'upi', 'card', 'cheque') NOT NULL,
    `reference_number` VARCHAR(100) NULL,
    `attachment_path` VARCHAR(255) NULL,
    `notes` TEXT NULL,
    `approved_by` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_expenses_category` (`category`),
    INDEX `idx_expenses_date` (`expense_date`),
    CONSTRAINT `fk_expenses_approver` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ------------------------------------------------------------------------------
-- 7. ATTENDANCE & LEAVE MANAGEMENT
-- ------------------------------------------------------------------------------

CREATE TABLE `attendance` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `batch_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `attendance_date` DATE NOT NULL,
    `session` ENUM('morning', 'evening') NOT NULL DEFAULT 'morning',
    `status` ENUM('present', 'absent', 'late', 'leave') NOT NULL,
    `late_minutes` SMALLINT UNSIGNED NULL DEFAULT 0,
    `remarks` VARCHAR(255) NULL,
    `marked_by` BIGINT UNSIGNED NOT NULL,
    `parent_notified` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_student_batch_session_attendance` (`batch_id`, `student_id`, `attendance_date`, `session`),
    INDEX `idx_attendance_batch_date` (`batch_id`, `attendance_date`),
    INDEX `idx_attendance_student_date` (`student_id`, `attendance_date`),
    INDEX `idx_attendance_status` (`status`),
    CONSTRAINT `fk_attendance_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_attendance_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_attendance_marker` FOREIGN KEY (`marked_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `leave_applications` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `from_date` DATE NOT NULL,
    `to_date` DATE NOT NULL,
    `reason` TEXT NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `reviewed_by` BIGINT UNSIGNED NULL,
    `review_remarks` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_leaves_student` (`student_id`),
    INDEX `idx_leaves_status` (`status`),
    CONSTRAINT `fk_leaves_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_leaves_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------------------------
-- 8. TESTS, EXAMINATIONS & MARKS
-- ------------------------------------------------------------------------------

CREATE TABLE `tests` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `type` ENUM('Unit Test', 'Mid-Term', 'Final', 'Practice', 'Mock Exam') NOT NULL,
    `course_id` BIGINT UNSIGNED NOT NULL,
    `subject` VARCHAR(100) NOT NULL,
    `test_date` DATE NOT NULL,
    `start_time` TIME NOT NULL,
    `duration_minutes` SMALLINT UNSIGNED NOT NULL DEFAULT 60,
    `total_marks` DECIMAL(6,2) NOT NULL,
    `passing_marks` DECIMAL(6,2) NOT NULL,
    `negative_marking` TINYINT(1) NOT NULL DEFAULT 0,
    `negative_mark_value` DECIMAL(4,2) NOT NULL DEFAULT 0.00,
    `syllabus` TEXT NULL,
    `question_paper_path` VARCHAR(255) NULL,
    `status` ENUM('scheduled', 'in_progress', 'completed', 'results_published', 'cancelled') NOT NULL DEFAULT 'scheduled',
    `created_by` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_tests_course` (`course_id`),
    INDEX `idx_tests_date` (`test_date`),
    INDEX `idx_tests_status` (`status`),
    CONSTRAINT `fk_tests_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_tests_creator` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `test_results` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `test_id` BIGINT UNSIGNED NOT NULL,
    `student_id` BIGINT UNSIGNED NOT NULL,
    `marks_obtained` DECIMAL(6,2) NOT NULL,
    `is_absent` TINYINT(1) NOT NULL DEFAULT 0,
    `rank` INT UNSIGNED NULL,
    `percentile` DECIMAL(5,2) NULL,
    `remarks` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_student_test` (`test_id`, `student_id`),
    INDEX `idx_tr_test` (`test_id`),
    INDEX `idx_tr_student` (`student_id`),
    CONSTRAINT `fk_tr_test` FOREIGN KEY (`test_id`) REFERENCES `tests` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_tr_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------------------------
-- 9. COMMUNICATION, NOTIFICATIONS & AUDIT
-- ------------------------------------------------------------------------------

CREATE TABLE `notification_templates` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `channel` ENUM('sms', 'email', 'whatsapp', 'push') NOT NULL,
    `trigger_event` ENUM('fee_due', 'fee_received', 'absent', 'test_result', 'admission', 'general') NOT NULL,
    `subject` VARCHAR(191) NULL,
    `body` TEXT NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `announcements` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `audience` ENUM('all', 'by_course', 'by_batch', 'fee_defaulters') NOT NULL DEFAULT 'all',
    `target_ids` JSON NULL, -- courses or batches array
    `channels` JSON NOT NULL, -- e.g. ["sms", "whatsapp", "email"]
    `message` TEXT NOT NULL,
    `scheduled_at` DATETIME NULL,
    `sent_at` DATETIME NULL,
    `sent_by` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_announcements_sent` (`sent_at`),
    CONSTRAINT `fk_announcements_sender` FOREIGN KEY (`sent_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE `settings` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT NULL,
    `group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE `audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(150) NOT NULL, -- e.g. "POST /admin/fees/collect"
    `entity` VARCHAR(100) NOT NULL, -- e.g. "FeePayment"
    `entity_id` BIGINT UNSIGNED NULL,
    `old_values` JSON NULL,
    `new_values` JSON NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_entity` (`entity`, `entity_id`),
    INDEX `idx_audit_created` (`created_at`)
) ENGINE=InnoDB;

CREATE TABLE `holidays` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL,
    `holiday_date` DATE NOT NULL UNIQUE,
    `description` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------------------------
-- 10. SEED DATA INSERTS
-- ------------------------------------------------------------------------------

-- Default Super Admin User (password: Password@123)
-- bcrypt hash for 'Password@123'
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `user_type`, `is_active`) VALUES
(1, 'System Super Administrator', 'admin@apexcoaching.edu', '+919876543210', '$2y$12$e80yqj6yKj6TjC8t.k8uO.n3sOeVvQf9C9u4WcTvhj9hD8r9v8cKm', 'admin', 1),
(2, 'Prof. Vikram Sharma', 'vikram.sharma@apexcoaching.edu', '+919876543211', '$2y$12$e80yqj6yKj6TjC8t.k8uO.n3sOeVvQf9C9u4WcTvhj9hD8r9v8cKm', 'teacher', 1),
(3, 'Dr. Neha Kapoor', 'neha.kapoor@apexcoaching.edu', '+919876543212', '$2y$12$e80yqj6yKj6TjC8t.k8uO.n3sOeVvQf9C9u4WcTvhj9hD8r9v8cKm', 'teacher', 1),
(4, 'Priya Mehta', 'priya.mehta@apexcoaching.edu', '+919876543213', '$2y$12$e80yqj6yKj6TjC8t.k8uO.n3sOeVvQf9C9u4WcTvhj9hD8r9v8cKm', 'accountant', 1),
(5, 'Rohit Verma', 'rohit.verma@apexcoaching.edu', '+919876543214', '$2y$12$e80yqj6yKj6TjC8t.k8uO.n3sOeVvQf9C9u4WcTvhj9hD8r9v8cKm', 'receptionist', 1),
(6, 'Aarav Patel', 'aarav.patel@student.apex.edu', '+919876543215', '$2y$12$e80yqj6yKj6TjC8t.k8uO.n3sOeVvQf9C9u4WcTvhj9hD8r9v8cKm', 'student', 1),
(7, 'Suresh Patel (Parent)', 'suresh.patel@parent.apex.edu', '+919876543216', '$2y$12$e80yqj6yKj6TjC8t.k8uO.n3sOeVvQf9C9u4WcTvhj9hD8r9v8cKm', 'parent', 1);

-- Roles
INSERT INTO `roles` (`id`, `name`, `guard_name`, `description`, `is_default`) VALUES
(1, 'super-admin', 'web', 'Full unhindered administrative access across all modules', 1),
(2, 'teacher', 'web', 'Manage classroom attendance, question papers, and student marks', 1),
(3, 'accountant', 'web', 'Fee collection, receipt issuance, expense voucher recording, and waivers', 1),
(4, 'receptionist', 'web', 'New student admissions, inquiries, batch schedules, and fee viewing', 1),
(5, 'student', 'web', 'View personalized timetable, test results, attendance logs, and fee history', 1),
(6, 'parent', 'web', 'Monitor ward academic progress, attendance alerts, and pending dues', 1);

-- Spatie Permission Codes
INSERT INTO `permissions` (`name`, `guard_name`, `module`) VALUES
('dashboard.view', 'web', 'dashboard'),
('staff.view', 'web', 'staff'),
('staff.create', 'web', 'staff'),
('staff.edit', 'web', 'staff'),
('staff.delete', 'web', 'staff'),
('course.view', 'web', 'courses'),
('course.create', 'web', 'courses'),
('course.edit', 'web', 'courses'),
('course.delete', 'web', 'courses'),
('batch.view', 'web', 'batches'),
('batch.create', 'web', 'batches'),
('batch.edit', 'web', 'batches'),
('batch.delete', 'web', 'batches'),
('batch.transfer', 'web', 'batches'),
('student.view', 'web', 'students'),
('student.create', 'web', 'students'),
('student.edit', 'web', 'students'),
('student.delete', 'web', 'students'),
('fee.view', 'web', 'fees'),
('fee.collect', 'web', 'fees'),
('fee.refund', 'web', 'fees'),
('fee.waive', 'web', 'fees'),
('fee.defaulters', 'web', 'fees'),
('attendance.view', 'web', 'attendance'),
('attendance.mark', 'web', 'attendance'),
('attendance.report', 'web', 'attendance'),
('attendance.leave.approve', 'web', 'attendance'),
('test.view', 'web', 'tests'),
('test.create', 'web', 'tests'),
('test.edit', 'web', 'tests'),
('test.delete', 'web', 'tests'),
('test.marks.entry', 'web', 'tests'),
('test.publish', 'web', 'tests'),
('expense.view', 'web', 'expenses'),
('expense.create', 'web', 'expenses'),
('expense.approve', 'web', 'expenses'),
('report.view', 'web', 'reports'),
('report.export', 'web', 'reports'),
('notification.view', 'web', 'notifications'),
('notification.send', 'web', 'notifications'),
('notification.template.manage', 'web', 'notifications'),
('role.view', 'web', 'roles'),
('role.create', 'web', 'roles'),
('role.edit', 'web', 'roles'),
('role.delete', 'web', 'roles'),
('menu.view', 'web', 'menus'),
('menu.create', 'web', 'menus'),
('menu.edit', 'web', 'menus'),
('menu.delete', 'web', 'menus'),
('setting.view', 'web', 'settings'),
('setting.edit', 'web', 'settings'),
('audit.view', 'web', 'audit');

-- Assign Super-Admin Role to User 1
INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(4, 'App\\Models\\User', 5),
(5, 'App\\Models\\User', 6),
(6, 'App\\Models\\User', 7);

-- Dynamic System Menus
INSERT INTO `system_menus` (`id`, `parent_id`, `name`, `icon`, `route_name`, `url`, `sort_order`, `is_visible`, `permission_code`) VALUES
(1, NULL, 'Dashboard', 'LayoutDashboard', 'admin.dashboard', '/admin/dashboard', 1, 1, 'dashboard.view'),
(2, NULL, 'Staff Management', 'Users', 'admin.staff.index', '/admin/staff', 2, 1, 'staff.view'),
(3, NULL, 'Courses', 'BookOpen', 'admin.courses.index', '/admin/courses', 3, 1, 'course.view'),
(4, NULL, 'Batches', 'Layers', 'admin.batches.index', '/admin/batches', 4, 1, 'batch.view'),
(5, NULL, 'Students', 'GraduationCap', 'admin.students.index', '/admin/students', 5, 1, 'student.view'),
(6, NULL, 'Fee & Finances', 'CreditCard', 'admin.fees.index', '/admin/fees', 6, 1, 'fee.view'),
(7, NULL, 'Attendance', 'CalendarCheck', 'admin.attendance.index', '/admin/attendance', 7, 1, 'attendance.view'),
(8, NULL, 'Tests & Exams', 'FileSpreadsheet', 'admin.tests.index', '/admin/tests', 8, 1, 'test.view'),
(9, NULL, 'Expenses', 'Receipt', 'admin.expenses.index', '/admin/expenses', 9, 1, 'expense.view'),
(10, NULL, 'Reports & Analytics', 'BarChart3', 'admin.reports.index', '/admin/reports', 10, 1, 'report.view'),
(11, NULL, 'Notifications', 'BellRing', 'admin.notifications.index', '/admin/notifications', 11, 1, 'notification.view'),
(12, NULL, 'Roles & Permissions', 'ShieldCheck', 'admin.roles.index', '/admin/roles', 12, 1, 'role.view'),
(13, NULL, 'Menu Builder', 'Menu', 'admin.menus.index', '/admin/menus', 13, 1, 'menu.view'),
(14, NULL, 'Institute Settings', 'Sliders', 'admin.settings.index', '/admin/settings', 14, 1, 'setting.view'),
(15, NULL, 'Audit Trail', 'History', 'admin.audit.index', '/admin/audit', 15, 1, 'audit.view');

-- Institute Settings Seed
INSERT INTO `settings` (`key`, `value`, `group`) VALUES
('institute_name', 'Apex Premier Academy for JEE & NEET', 'institute'),
('institute_code', 'APEX-EDU', 'institute'),
('contact_email', 'contact@apexcoaching.edu', 'institute'),
('contact_phone', '+91 98765 43210', 'institute'),
('address', 'Plot 42, Education Hub, Knowledge Park III, Greater Noida, UP - 201310', 'institute'),
('gst_number', '07AAAAA0000A1Z5', 'finance'),
('currency_symbol', '₹', 'finance'),
('receipt_prefix', 'APEX/REC/2026/', 'finance'),
('academic_year_start', '2026-04-01', 'academic'),
('academic_year_end', '2027-03-31', 'academic'),
('late_fee_per_day', '50.00', 'finance'),
('grace_days_allowed', '5', 'finance');

-- Notification Templates
INSERT INTO `notification_templates` (`name`, `channel`, `trigger_event`, `subject`, `body`, `is_active`) VALUES
('Fee Due Reminder', 'sms', 'fee_due', NULL, 'Dear Parent, fee installment of {{due_amount}} for {{student_name}} is due on {{due_date}}. Please clear online or visit cashier. - APEX', 1),
('Fee Receipt Acknowledgment', 'whatsapp', 'fee_received', NULL, 'Thank you! Received fee payment of {{amount_paid}} for {{student_name}} via {{payment_method}}. Receipt No: {{receipt_number}}. - APEX', 1),
('Student Absent Alert', 'sms', 'absent', NULL, 'Notice: {{student_name}} was marked ABSENT today ({{attendance_date}}) in {{batch}} session. Please contact reception if unexpected. - APEX', 1),
('Test Result Published', 'whatsapp', 'test_result', NULL, 'Results out! {{student_name}} scored {{marks_obtained}}/{{total_marks}} in {{test_name}} (Subject: {{subject}}). Check student portal for rank. - APEX', 1);

-- Staff Members
INSERT INTO `staff` (`id`, `user_id`, `employee_code`, `designation`, `qualification`, `subjects`, `joining_date`, `salary`, `status`) VALUES
(1, 2, 'STF-001', 'Head of Physics Department', 'M.Sc. Physics (IIT Delhi)', '["Physics", "Applied Mechanics"]', '2022-06-15', 85000.00, 'active'),
(2, 3, 'STF-002', 'Senior Chemistry Faculty', 'Ph.D. Organic Chemistry', '["Organic Chemistry", "Inorganic Chemistry"]', '2023-01-10', 80000.00, 'active'),
(3, 4, 'STF-003', 'Senior Chief Accountant', 'B.Com, CA Inter', '[]', '2021-08-01', 55000.00, 'active'),
(4, 5, 'STF-004', 'Front Desk & Admissions Lead', 'BBA in Hospitality', '[]', '2023-04-05', 38000.00, 'active');

-- Courses
INSERT INTO `courses` (`id`, `name`, `code`, `category`, `duration_months`, `total_fee`, `admission_fee`, `installments_allowed`, `status`, `description`) VALUES
(1, 'JEE Advanced Pinnacle (2-Year)', 'JEE-ADV-2Y', 'JEE (Main+Adv)', 24, 180000.00, 15000.00, 4, 'active', 'Intensive 2-year classroom foundation & advanced syllabus for Class 11-12 IIT JEE aspirants.'),
(2, 'NEET Medical Ranker (1-Year)', 'NEET-MED-1Y', 'NEET Medical', 12, 125000.00, 10000.00, 3, 'active', 'Complete coverage of Physics, Chemistry & Biology with NCERT line-by-line masterclasses.'),
(3, 'Foundation Olympiad Class 10', 'FOUND-OLYMP-10', 'Class 8-10', 10, 65000.00, 5000.00, 2, 'active', 'Building high aptitude in Mathematics & Science for NTSE, PRMO and Board excellence.');

-- Batches
INSERT INTO `batches` (`id`, `course_id`, `name`, `teacher_id`, `co_teacher_id`, `start_date`, `end_date`, `class_days`, `start_time`, `end_time`, `room_number`, `max_capacity`, `enrolled_count`, `status`) VALUES
(1, 1, 'JEE-2027-Alpha', 1, 2, '2026-04-15', '2027-03-31', '["Mon", "Wed", "Fri"]', '08:30:00', '12:30:00', 'Hall A-101', 35, 31, 'ongoing'),
(2, 2, 'NEET-2027-Medix', 2, 1, '2026-05-01', '2027-04-15', '["Tue", "Thu", "Sat"]', '09:00:00', '13:00:00', 'Hall B-204', 30, 28, 'ongoing'),
(3, 3, 'Olympiad-X-Excel', 1, NULL, '2026-06-01', '2027-02-28', '["Mon", "Wed", "Sat"]', '16:00:00', '18:30:00', 'Room C-302', 25, 20, 'ongoing');

-- Students
INSERT INTO `students` (`id`, `user_id`, `roll_number`, `gender`, `dob`, `school_name`, `current_class`, `parent_name`, `parent_phone`, `parent_email`, `address_line1`, `city`, `state`, `zip`, `admission_date`, `total_fee`, `discount_amount`, `discount_reason`, `final_fee`, `paid_fee`, `outstanding_fee`, `payment_plan`, `status`) VALUES
(1, 6, 'APEX-2026-001', 'male', '2009-08-14', 'Delhi Public School R.K. Puram', 'Class 11', 'Suresh Patel', '+919876543216', 'suresh.patel@parent.apex.edu', 'Tower 4, Flat 702, Emerald Heights', 'Noida', 'Uttar Pradesh', '201301', '2026-04-10', 180000.00, 10000.00, 'Merit', 170000.00, 100000.00, 70000.00, 'installment_3', 'active');

INSERT INTO `student_batch` (`student_id`, `batch_id`, `is_primary`, `status`) VALUES
(1, 1, 1, 'active');

-- Fee Plans for Student 1
INSERT INTO `fee_plans` (`id`, `student_id`, `installment_number`, `due_date`, `amount`, `paid_amount`, `balance_amount`, `status`) VALUES
(1, 1, 1, '2026-04-15', 60000.00, 60000.00, 0.00, 'paid'),
(2, 1, 2, '2026-08-15', 55000.00, 40000.00, 15000.00, 'partial'),
(3, 1, 3, '2026-12-15', 55000.00, 0.00, 55000.00, 'pending');

-- Fee Payments for Student 1
INSERT INTO `fee_payments` (`id`, `receipt_number`, `student_id`, `fee_plan_id`, `amount_paid`, `late_fee_charged`, `late_fee_waived`, `payment_method`, `reference_number`, `payment_date`, `collected_by`, `receipt_note`) VALUES
(1, 'APEX/REC/2026/0001', 1, 1, 60000.00, 0.00, 0.00, 'net_banking', 'HDFC982341209', '2026-04-12', 4, 'Full 1st installment clearance with admission kit'),
(2, 'APEX/REC/2026/0142', 1, 2, 40000.00, 500.00, 500.00, 'upi', 'UPI/261028/893271', '2026-08-18', 4, 'Part payment for 2nd installment; late fee waived by Director');

-- Sample Test & Results
INSERT INTO `tests` (`id`, `name`, `type`, `course_id`, `subject`, `test_date`, `start_time`, `duration_minutes`, `total_marks`, `passing_marks`, `negative_marking`, `negative_mark_value`, `status`, `created_by`) VALUES
(1, 'All India Minor Test #1 (Mechanics)', 'Unit Test', 1, 'Physics', '2026-06-20', '09:00:00', 90, 100.00, 40.00, 1, 1.00, 'results_published', 2);

INSERT INTO `test_results` (`test_id`, `student_id`, `marks_obtained`, `is_absent`, `rank`, `percentile`, `remarks`) VALUES
(1, 1, 88.50, 0, 3, 94.20, 'Strong conceptual grasp of Rotational Dynamics; watch calculation errors in friction.');

-- Sample Audit Log entry
INSERT INTO `audit_logs` (`user_id`, `action`, `entity`, `entity_id`, `old_values`, `new_values`, `ip_address`, `user_agent`) VALUES
(1, 'POST /admin/students', 'Student', 1, NULL, '{"roll_number": "APEX-2026-001", "name": "Aarav Patel", "course_id": 1}', '127.0.0.1', 'Mozilla/5.0 (Admin Session)');

-- ==============================================================================
-- 11. RECURSIVE MENU CTE & KEY REPORTING QUERIES
-- ==============================================================================

-- 11.1 Dynamic Sidebar Recursive Tree
-- Reconstructs hierarchical navigation from system_menus with depth and paths:
WITH RECURSIVE menu_tree AS (
    SELECT 
        id, parent_id, name, icon, route_name, url, sort_order, is_visible, permission_code,
        1 AS depth,
        CAST(sort_order AS CHAR(255)) AS sort_path
    FROM system_menus
    WHERE parent_id IS NULL AND is_visible = 1

    UNION ALL

    SELECT 
        child.id, child.parent_id, child.name, child.icon, child.route_name, child.url, child.sort_order, child.is_visible, child.permission_code,
        parent.depth + 1,
        CONCAT(parent.sort_path, '.', LPAD(child.sort_order, 4, '0'))
    FROM system_menus child
    INNER JOIN menu_tree parent ON child.parent_id = parent.id
    WHERE child.is_visible = 1
)
SELECT * FROM menu_tree ORDER BY sort_path;

-- 11.2 Today's Fee Collection Aggregates
SELECT 
    COUNT(id) AS total_receipts_today,
    COALESCE(SUM(amount_paid), 0.00) AS total_revenue_today,
    COALESCE(SUM(late_fee_charged), 0.00) AS total_late_fees_collected,
    COALESCE(SUM(late_fee_waived), 0.00) AS total_late_fees_waived
FROM fee_payments
WHERE payment_date = CURRENT_DATE();

-- 11.3 Top Fee Defaulters List
SELECT 
    s.id,
    s.roll_number,
    u.name AS student_name,
    s.parent_name,
    s.parent_phone,
    c.name AS course_name,
    b.name AS batch_name,
    s.outstanding_fee,
    MIN(fp.due_date) AS earliest_overdue_date,
    DATEDIFF(CURRENT_DATE(), MIN(fp.due_date)) AS days_overdue
FROM students s
INNER JOIN users u ON s.user_id = u.id
INNER JOIN student_batch sb ON s.id = sb.student_id AND sb.status = 'active'
INNER JOIN batches b ON sb.batch_id = b.id
INNER JOIN courses c ON b.course_id = c.id
INNER JOIN fee_plans fp ON s.id = fp.student_id AND fp.status IN ('pending', 'partial', 'overdue')
WHERE fp.due_date < CURRENT_DATE()
GROUP BY s.id, s.roll_number, u.name, s.parent_name, s.parent_phone, c.name, b.name, s.outstanding_fee
ORDER BY s.outstanding_fee DESC, days_overdue DESC
LIMIT 25;

-- 11.4 Batch Capacity Utilization
SELECT 
    b.id,
    b.name AS batch_name,
    c.name AS course_name,
    u.name AS teacher_name,
    b.max_capacity,
    COUNT(sb.student_id) AS current_enrolled,
    (b.max_capacity - COUNT(sb.student_id)) AS remaining_seats,
    ROUND((COUNT(sb.student_id) / b.max_capacity) * 100, 1) AS occupancy_percentage
FROM batches b
INNER JOIN courses c ON b.course_id = c.id
INNER JOIN staff st ON b.teacher_id = st.id
INNER JOIN users u ON st.user_id = u.id
LEFT JOIN student_batch sb ON b.id = sb.batch_id AND sb.status = 'active'
GROUP BY b.id, b.name, c.name, u.name, b.max_capacity
ORDER BY occupancy_percentage DESC;

-- 11.5 Batch Attendance Percentage Today
SELECT 
    b.id AS batch_id,
    b.name AS batch_name,
    COUNT(a.id) AS total_marked,
    SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) AS present_count,
    SUM(CASE WHEN a.status = 'absent' THEN 1 ELSE 0 END) AS absent_count,
    SUM(CASE WHEN a.status = 'late' THEN 1 ELSE 0 END) AS late_count,
    ROUND((SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) / NULLIF(COUNT(a.id), 0)) * 100, 1) AS attendance_rate
FROM batches b
LEFT JOIN attendance a ON b.id = a.batch_id AND a.attendance_date = CURRENT_DATE()
GROUP BY b.id, b.name;

-- 11.6 Append-Only Audit Trail Inspection
SELECT 
    a.id,
    a.created_at AS timestamp,
    COALESCE(u.name, 'System') AS performed_by,
    u.email,
    a.action,
    a.entity,
    a.entity_id,
    a.ip_address,
    a.old_values,
    a.new_values
FROM audit_logs a
LEFT JOIN users u ON a.user_id = u.id
ORDER BY a.created_at DESC
LIMIT 50;
