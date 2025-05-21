<?php

namespace App\Services;

use App\Models\LogAplikasi;
use Illuminate\Support\Facades\Auth;

class LogService
{
    /**
     * Record application log
     *
     * @param string $aktivitas - Type of activity ('create', 'update', 'delete', 'login', 'logout', etc.)
     * @param string $tabel - Table name
     * @param int|null $id_data - Related record ID
     * @param string $deskripsi - Description of the activity
     * @param int|null $user_id - User ID (if null, will use authenticated user)
     * @return LogAplikasi
     */
    public static function record(
        string $aktivitas, 
        string $tabel, 
        ?int $id_data = null, 
        string $deskripsi = '',
        ?int $user_id = null
    ): LogAplikasi {
        return LogAplikasi::create([
            'user_id' => $user_id ?? (Auth::check() ? Auth::id() : null),
            'aktivitas' => $aktivitas,
            'tabel' => $tabel,
            'id_data' => $id_data,
            'deskripsi' => $deskripsi,
        ]);
    }
    
    /**
     * Record a create activity
     *
     * @param string $tabel - Table name
     * @param int $id_data - Created record ID
     * @param string $deskripsi - Description of the activity
     * @return LogAplikasi
     */
    public static function create(string $tabel, int $id_data, string $deskripsi): LogAplikasi
    {
        return self::record('create', $tabel, $id_data, $deskripsi);
    }
    
    /**
     * Record an update activity
     *
     * @param string $tabel - Table name
     * @param int $id_data - Updated record ID
     * @param string $deskripsi - Description of the activity
     * @return LogAplikasi
     */
    public static function update(string $tabel, int $id_data, string $deskripsi): LogAplikasi
    {
        return self::record('update', $tabel, $id_data, $deskripsi);
    }
    
    /**
     * Record a delete activity
     *
     * @param string $tabel - Table name
     * @param int $id_data - Deleted record ID
     * @param string $deskripsi - Description of the activity
     * @return LogAplikasi
     */
    public static function delete(string $tabel, int $id_data, string $deskripsi): LogAplikasi
    {
        return self::record('delete', $tabel, $id_data, $deskripsi);
    }
    
    /**
     * Record an approve activity
     *
     * @param string $tabel - Table name
     * @param int $id_data - Approved record ID
     * @param string $deskripsi - Description of the activity
     * @return LogAplikasi
     */
    public static function approve(string $tabel, int $id_data, string $deskripsi): LogAplikasi
    {
        return self::record('approve', $tabel, $id_data, $deskripsi);
    }
    
    /**
     * Record a reject activity
     *
     * @param string $tabel - Table name
     * @param int $id_data - Rejected record ID
     * @param string $deskripsi - Description of the activity
     * @return LogAplikasi
     */
    public static function reject(string $tabel, int $id_data, string $deskripsi): LogAplikasi
    {
        return self::record('reject', $tabel, $id_data, $deskripsi);
    }
    
    /**
     * Record a login activity
     *
     * @param int $user_id - User ID
     * @param string $deskripsi - Additional description
     * @return LogAplikasi
     */
    public static function login(int $user_id, string $deskripsi = 'User login'): LogAplikasi
    {
        return self::record('login', 'users', $user_id, $deskripsi, $user_id);
    }
    
    /**
     * Record a logout activity
     *
     * @param int $user_id - User ID
     * @param string $deskripsi - Additional description
     * @return LogAplikasi
     */
    public static function logout(int $user_id, string $deskripsi = 'User logout'): LogAplikasi
    {
        return self::record('logout', 'users', $user_id, $deskripsi, $user_id);
    }
    
    /**
     * Record an export activity
     *
     * @param string $tabel - Table name
     * @param string $deskripsi - Description of the activity
     * @return LogAplikasi
     */
    public static function export(string $tabel, string $deskripsi): LogAplikasi
    {
        return self::record('export', $tabel, null, $deskripsi);
    }
}
