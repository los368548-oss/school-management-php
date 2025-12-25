<?php

class DashboardController {
    public function index() {
        // Get dashboard stats
        $stats = $this->getDashboardStats();
        require 'views/admin/layout.php';
    }

    private function getDashboardStats() {
        $db = Database::getInstance()->getConnection();
        
        $stats = [];
        
        // Total students
        $stmt = $db->query("SELECT COUNT(*) as total FROM students");
        $stats['total_students'] = $stmt->fetch()['total'];
        
        // Total classes
        $stmt = $db->query("SELECT COUNT(*) as total FROM classes");
        $stats['total_classes'] = $stmt->fetch()['total'];
        
        // Today's attendance
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM attendance WHERE date = CURDATE()");
        $stmt->execute();
        $stats['today_attendance'] = $stmt->fetch()['total'];
        
        // Pending fees
        $stmt = $db->query("SELECT SUM(amount) as total FROM fees f LEFT JOIN fee_payments fp ON f.id = fp.fee_id WHERE fp.id IS NULL");
        $stats['pending_fees'] = $stmt->fetch()['total'] ?? 0;
        
        return $stats;
    }
}