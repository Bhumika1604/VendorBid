<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table            = 'notifications';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields = [
        'user_id',
        'type',
        'title',
        'message',
        'link',
        'is_read',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'user_id' => 'required|integer',
        'type'    => 'required|max_length[50]',
        'title'   => 'required|max_length[200]',
        'message' => 'required|max_length[500]',
    ];

    public const TYPE_BID_SUBMITTED = 'bid_submitted';
    public const TYPE_AWARDED       = 'project_awarded';
    public const TYPE_REJECTED      = 'bid_rejected';

    /**
     * All notifications for a user, newest first.
     *
     * @return array<int, array<string, mixed>>
     */
    public function forUser(int $userId, int $limit = 50): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll($limit);
    }

    /**
     * Count of unread notifications for a user (e.g. for a navbar badge).
     */
    public function unreadCount(int $userId): int
    {
        return $this->where('user_id', $userId)->where('is_read', 0)->countAllResults();
    }

    /**
     * Mark a single notification as read (only if it belongs to the user).
     */
    public function markRead(int $id, int $userId): bool
    {
        return (bool) $this->where('id', $id)->where('user_id', $userId)
            ->set(['is_read' => 1, 'updated_at' => date('Y-m-d H:i:s')])
            ->update();
    }

    /**
     * Mark every notification for a user as read.
     */
    public function markAllRead(int $userId): bool
    {
        return (bool) $this->where('user_id', $userId)->where('is_read', 0)
            ->set(['is_read' => 1, 'updated_at' => date('Y-m-d H:i:s')])
            ->update();
    }
}
