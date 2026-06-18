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
     * Создает администратора и хеширует его пароль.
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
     * Обновляет администратора и меняет пароль только при передаче нового значения.
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
     * Удаляет администратора, если это не текущий авторизованный пользователь.
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
