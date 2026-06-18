<?php

namespace App\Services\Admin;

use App\DTO\Admin\AdminData;
use App\Repositories\AdminRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AdminService
{
    public function __construct(private readonly AdminRepository $admins)
    {
    }

    public function create(AdminData $data): Model
    {
        return $this->admins->create(array_merge($data->toArray(), [
            'password' => Hash::make((string) $data->password()),
        ]));
    }

    public function update(AdminData $data): bool
    {
        $attributes = $data->toArray();

        if ($data->password() !== null) {
            $attributes['password'] = Hash::make($data->password());
        }

        return $this->admins->update($data->id(), $attributes);
    }

    public function deleteExceptCurrent(int $id, int $currentUserId): bool
    {
        if ($id === $currentUserId) {
            return false;
        }

        return $this->admins->delete($id);
    }
}
