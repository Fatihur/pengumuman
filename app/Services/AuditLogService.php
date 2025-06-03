<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AuditLogService
{
    /**
     * Log user activity
     */
    public static function log(string $action, string $description, array $data = [], ?string $ipAddress = null): void
    {
        try {
            $user = Auth::user();
            $request = request();
            
            $logData = [
                'user_id' => $user ? $user->id : null,
                'user_email' => $user ? $user->email : null,
                'action' => $action,
                'description' => $description,
                'ip_address' => $ipAddress ?? $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'data' => json_encode($data),
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            // Store in database
            DB::table('audit_logs')->insert($logData);
            
            // Also log to Laravel log for immediate visibility
            Log::info('Audit Log', $logData);
            
        } catch (\Exception $e) {
            Log::error('Failed to create audit log', [
                'error' => $e->getMessage(),
                'action' => $action,
                'description' => $description
            ]);
        }
    }

    /**
     * Log student data changes
     */
    public static function logStudentChange(string $action, $student, array $changes = []): void
    {
        $description = match($action) {
            'created' => "Siswa baru ditambahkan: {$student->nama}",
            'updated' => "Data siswa diperbarui: {$student->nama}",
            'deleted' => "Siswa dihapus: {$student->nama}",
            'status_changed' => "Status kelulusan diubah: {$student->nama}",
            default => "Aksi pada siswa: {$student->nama}"
        };

        self::log("student.{$action}", $description, [
            'student_id' => $student->id,
            'student_name' => $student->nama,
            'student_nisn' => $student->nisn ?? $student->nis,
            'changes' => $changes
        ]);
    }

    /**
     * Log settings changes
     */
    public static function logSettingsChange(string $setting, $oldValue, $newValue): void
    {
        self::log('settings.updated', "Pengaturan diubah: {$setting}", [
            'setting' => $setting,
            'old_value' => $oldValue,
            'new_value' => $newValue
        ]);
    }

    /**
     * Log file operations
     */
    public static function logFileOperation(string $action, string $filename, string $path = ''): void
    {
        $description = match($action) {
            'uploaded' => "File diunggah: {$filename}",
            'downloaded' => "File diunduh: {$filename}",
            'deleted' => "File dihapus: {$filename}",
            default => "Operasi file: {$filename}"
        };

        self::log("file.{$action}", $description, [
            'filename' => $filename,
            'path' => $path,
            'file_size' => file_exists($path) ? filesize($path) : null
        ]);
    }

    /**
     * Log authentication events
     */
    public static function logAuth(string $event, ?string $email = null, array $data = []): void
    {
        $description = match($event) {
            'login' => "User berhasil login: {$email}",
            'logout' => "User logout: {$email}",
            'failed_login' => "Percobaan login gagal: {$email}",
            'password_reset' => "Reset password: {$email}",
            default => "Event autentikasi: {$event}"
        };

        self::log("auth.{$event}", $description, array_merge([
            'email' => $email
        ], $data));
    }

    /**
     * Log security events
     */
    public static function logSecurity(string $event, string $description, array $data = []): void
    {
        self::log("security.{$event}", $description, $data);
        
        // Also log to security-specific log file
        Log::channel('security')->warning($description, $data);
    }

    /**
     * Log bulk operations
     */
    public static function logBulkOperation(string $operation, int $affectedCount, array $data = []): void
    {
        $description = "Operasi massal {$operation}: {$affectedCount} record terpengaruh";
        
        self::log("bulk.{$operation}", $description, array_merge([
            'affected_count' => $affectedCount
        ], $data));
    }

    /**
     * Get recent audit logs
     */
    public static function getRecentLogs(int $limit = 50): array
    {
        return DB::table('audit_logs')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get logs by user
     */
    public static function getLogsByUser(int $userId, int $limit = 50): array
    {
        return DB::table('audit_logs')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get logs by action
     */
    public static function getLogsByAction(string $action, int $limit = 50): array
    {
        return DB::table('audit_logs')
            ->where('action', 'like', "{$action}%")
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Clean old logs (keep only last 6 months)
     */
    public static function cleanOldLogs(): int
    {
        $cutoffDate = now()->subMonths(6);
        
        return DB::table('audit_logs')
            ->where('created_at', '<', $cutoffDate)
            ->delete();
    }
}
