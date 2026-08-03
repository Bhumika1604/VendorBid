<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;

    protected $allowedFields = [
        'role',
        'name',
        'email',
        'password',
        'company_name',
        'phone',
        'address',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[150]',
        'email'    => 'required|valid_email|max_length[150]|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[admin,contractor]',
        'phone'    => 'permit_empty|min_length[7]|max_length[20]',
    ];

    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email address is already registered.',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $beforeUpdate   = ['hashPassword'];

    /**
     * Hash the plain text password before it is written to the database.
     * Skips hashing if the password field was not supplied in this call
     * (e.g. updating a profile without changing the password) or if it
     * already looks like a bcrypt hash.
     *
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    protected function hashPassword(array $data): array
    {
        if (! isset($data['data']['password']) || $data['data']['password'] === '') {
            return $data;
        }

        // Avoid double-hashing an already hashed value
        if (password_get_info($data['data']['password'])['algo'] !== null) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        return $data;
    }

    /**
     * Find an active user by email + role. Used for login lookups.
     *
     * @return array<string, mixed>|null
     */
    public function findByEmailAndRole(string $email, string $role): ?array
    {
        return $this->where('email', $email)
            ->where('role', $role)
            ->first();
    }

    /**
     * Total count of contractor accounts (used by Admin dashboard).
     */
    public function totalContractors(): int
    {
        return $this->where('role', 'contractor')->countAllResults();
    }
}
