<?php

namespace App\Services\Admin;

use App\DTO\Admin\AdminData;
use App\Repositories\AdminRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    public function __construct(private readonly AdminRepository $admins) {}

    /**
     * Creates an administrator and hashes the password.
     *
     * @param AdminData $data
     * @return Model
     */
    public function create(AdminData $data): Model
    {
        return $this->admins->create(array_merge($data->toArray(), [
            'password' => Hash::make((string) $data->password()),
        ]));
    }

    /**
     * Updates the administrator and changes the password only when a new value is provided.
     *
     * @param AdminData $data
     * @return bool
     */
    public function update(AdminData $data): bool
    {
        $attributes = $data->toArray();

        if ($data->password() !== null) {
            $attributes['password'] = Hash::make($data->password());
        }

        return $this->admins->update($data->id(), $attributes);
    }

    /**
     * Deletes an administrator unless it is the current authenticated user.
     *
     * @param int $id
     * @param int $currentUserId
     * @return bool
     */
    public function deleteExceptCurrent(int $id, int $currentUserId): bool
    {
        if ($id === $currentUserId) {
            return false;
        }

        return $this->admins->delete($id);
    }
}
