<?php

namespace App\Services;

use App\Ambassador;
use App\Helpers\PhoneHelper;
use App\Judge;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class JudgeAmbassadorAccountService
{
    /**
     * Ensure a login User exists for an Ambassador or Judge profile, with the
     * correct role_id, linked via user_id, and phones kept in sync.
     *
     * @param  Ambassador|Judge  $profile
     * @param  string  $roleName  "Ambassador" or "Judge"
     * @param  string|null  $plainPassword  when creating or resetting
     * @param  bool  $resetPassword  force password update on existing user
     * @return array{user: User, created: bool, password: string|null, linked: bool}
     */
    public function ensureForProfile(Model $profile, $roleName, $plainPassword = null, $resetPassword = false)
    {
        $role = $this->resolveRole($roleName);
        if (!$role) {
            throw new \RuntimeException("Role [{$roleName}] not found.");
        }

        $user = $this->findExistingUser($profile, (int) $role->id);
        $created = false;
        $passwordOut = null;

        if (!$user) {
            $passwordOut = $plainPassword ?: (string) random_int(100000, 999999);
            $user = User::create([
                'name' => $profile->name,
                'email' => $this->uniqueEmailFor($profile),
                'phone' => $this->bestPhone($profile, null),
                'password' => bcrypt($passwordOut),
                'role_id' => $role->id,
                'is_active' => true,
                'is_deleted' => false,
            ]);
            $created = true;
        } else {
            $dirty = false;
            if ((int) $user->role_id !== (int) $role->id && !$user->isAdmin()) {
                $user->role_id = $role->id;
                $dirty = true;
            }
            if (!(int) $user->is_active) {
                $user->is_active = true;
                $dirty = true;
            }
            if ((int) $user->is_deleted) {
                $user->is_deleted = false;
                $dirty = true;
            }

            $phone = $this->bestPhone($profile, $user);
            if ($phone && $user->phone !== $phone) {
                $user->phone = $phone;
                $dirty = true;
            }

            if ($resetPassword) {
                $passwordOut = $plainPassword ?: (string) random_int(100000, 999999);
                $user->password = bcrypt($passwordOut);
                $dirty = true;
            }

            if ($dirty) {
                $user->save();
            }
        }

        $linked = false;
        if ((int) $profile->user_id !== (int) $user->id) {
            $profile->user_id = $user->id;
            $linked = true;
        }

        $profilePhone = $this->bestPhone($profile, $user);
        if ($profilePhone && (string) $profile->phone_number !== (string) $profilePhone) {
            $profile->phone_number = $profilePhone;
            $linked = true;
        }

        if ($linked || $profile->isDirty()) {
            $profile->save();
        }

        return [
            'user' => $user->fresh(),
            'created' => $created,
            'password' => $passwordOut,
            'linked' => $linked || $created,
        ];
    }

    public function syncAllAmbassadors($resetPassword = false)
    {
        $results = [];
        Ambassador::where('is_active', true)->orderBy('id')->each(function (Ambassador $amb) use (&$results, $resetPassword) {
            if ($this->isPlaceholderName($amb->name)) {
                return;
            }
            $results[] = array_merge(
                ['type' => 'ambassador', 'profile_id' => $amb->id, 'name' => $amb->name],
                $this->ensureForProfile($amb, 'Ambassador', null, $resetPassword)
            );
        });

        return $results;
    }

    public function syncAllJudges($resetPassword = false)
    {
        $results = [];
        Judge::where('is_active', true)->orderBy('id')->each(function (Judge $judge) use (&$results, $resetPassword) {
            if ($this->shouldSkipJudgeProfile($judge)) {
                return;
            }
            $results[] = array_merge(
                ['type' => 'judge', 'profile_id' => $judge->id, 'name' => $judge->name],
                $this->ensureForProfile($judge, 'Judge', null, $resetPassword)
            );
        });

        return $results;
    }

    protected function resolveRole($roleName)
    {
        return Role::whereRaw('LOWER(name) = ?', [strtolower($roleName)])->first();
    }

    protected function findExistingUser(Model $profile, $roleId)
    {
        if (!empty($profile->user_id)) {
            $byId = User::where('id', $profile->user_id)->where('is_deleted', false)->first();
            if ($byId) {
                return $byId;
            }
        }

        $name = trim((string) $profile->name);
        if ($name !== '') {
            $exact = User::where('is_deleted', false)
                ->whereRaw('LOWER(name) = ?', [strtolower($name)])
                ->first();
            if ($exact) {
                return $exact;
            }

            // e.g. profile "George Joseph Moussio" ↔ user "Joseph Moussio"
            $fuzzy = User::where('is_deleted', false)
                ->where(function ($q) use ($name, $roleId) {
                    $q->where('role_id', $roleId)
                        ->where(function ($inner) use ($name) {
                            $inner->where('name', 'like', '%' . $name . '%')
                                ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [$name]);
                        });
                })
                ->orderByRaw('CASE WHEN role_id = ? THEN 0 ELSE 1 END', [$roleId])
                ->first();
            if ($fuzzy) {
                return $fuzzy;
            }
        }

        if (!empty($profile->email)) {
            $byEmail = User::where('is_deleted', false)
                ->whereRaw('LOWER(email) = ?', [strtolower($profile->email)])
                ->first();
            if ($byEmail) {
                return $byEmail;
            }
        }

        $phone = $this->normalizePhone($profile->phone_number ?? null);
        if ($phone) {
            $byPhone = $this->findUserByPhone($phone);
            if ($byPhone) {
                return $byPhone;
            }
        }

        return null;
    }

    public function findUserByPhone($phone)
    {
        $normalized = $this->normalizePhone($phone);
        if (!$normalized) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $normalized);
        $local = $digits;
        if (strpos($local, '237') === 0 && strlen($local) > 9) {
            $local = substr($local, 3);
        }

        return User::where('is_deleted', false)
            ->where(function ($q) use ($normalized, $digits, $local) {
                $q->where('phone', $normalized)
                    ->orWhere('phone', $digits)
                    ->orWhere('phone', 'like', '%' . $local)
                    ->orWhere('whatsapp_number', $normalized)
                    ->orWhere('whatsapp_number', 'like', '%' . $local);
            })
            ->orderByDesc('is_active')
            ->first();
    }

    protected function bestPhone(Model $profile, $user = null)
    {
        $candidates = [];
        if ($user && !empty($user->phone)) {
            $candidates[] = $user->phone;
        }
        if ($user && !empty($user->whatsapp_number)) {
            $candidates[] = $user->whatsapp_number;
        }
        if (!empty($profile->phone_number)) {
            $candidates[] = $profile->phone_number;
        }

        foreach ($candidates as $raw) {
            $e164 = $this->normalizePhone($raw);
            if (!$e164) {
                continue;
            }
            $digitCount = strlen(preg_replace('/\D/', '', $e164));
            // Reject obviously fake placeholder numbers stored on profiles.
            if ($digitCount < 10) {
                continue;
            }
            return $e164;
        }

        return null;
    }

    protected function normalizePhone($phone)
    {
        if ($phone === null || trim((string) $phone) === '') {
            return null;
        }

        return PhoneHelper::forUltraMsg($phone) ?: PhoneHelper::e164($phone);
    }

    protected function uniqueEmailFor(Model $profile)
    {
        $email = trim((string) ($profile->email ?? ''));
        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $exists = User::whereRaw('LOWER(email) = ?', [strtolower($email)])
                ->where('is_deleted', false)
                ->exists();
            if (!$exists) {
                return $email;
            }
        }

        $slug = Str::slug($profile->name ?: 'user', '.');
        if ($slug === '') {
            $slug = 'user';
        }
        $candidate = $slug . '@mulemagc.com';
        $i = 1;
        while (User::whereRaw('LOWER(email) = ?', [strtolower($candidate)])->where('is_deleted', false)->exists()) {
            $candidate = $slug . $i . '@mulemagc.com';
            $i++;
        }

        return $candidate;
    }

    protected function shouldSkipJudgeProfile(Judge $judge)
    {
        $name = trim((string) $judge->name);
        if ($name === '') {
            return true;
        }
        if (stripos($name, 'Ambassador ') === 0) {
            return true;
        }
        if (Ambassador::whereRaw('LOWER(name) = ?', [strtolower($name)])->exists()) {
            return true;
        }

        return false;
    }

    protected function isPlaceholderName($name)
    {
        $name = trim((string) $name);
        return $name === '' || (bool) preg_match('/^Ambassador\s+\d+$/i', $name);
    }
}
