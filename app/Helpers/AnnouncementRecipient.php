<?php

namespace App\Helpers;

use App\Ambassador;
use App\Announcement;
use App\Employee;
use App\Judge;
use App\User;
use Illuminate\Support\Collection;

class AnnouncementRecipient
{
    public static function categories(): array
    {
        return [
            'everyone' => 'Everyone',
            'contestants' => 'Contestants',
            'voters' => 'Voters',
            'users' => 'Users',
            'judges' => 'Judges',
            'ambassadors' => 'Ambassadors',
            'csv' => 'CSV upload',
        ];
    }

    /** @return array<int, array{type:string,id:int|string,name:string,phone:string,email:string,label:string}> */
    public static function listForCategory(string $category, ?string $query = null): array
    {
        $category = strtolower(trim($category));
        if ($category === 'everyone') {
            return self::dedupe(self::mergeAllCategories($query));
        }

        return self::dedupe(self::categoryRows($category, $query));
    }

    public static function resolveForAnnouncement(Announcement $announcement): array
    {
        $stored = self::decodeJson($announcement->recipients_json);
        if (!empty($stored)) {
            return self::dedupe($stored);
        }

        return self::resolveLegacy($announcement);
    }

    public static function storePayload(array $recipients): string
    {
        return json_encode(self::dedupe($recipients));
    }

    public static function parseSlots(?string $json): array
    {
        $rows = self::decodeJson($json);
        $out = [];
        foreach ($rows as $row) {
            $at = trim((string) ($row['at'] ?? $row['scheduled_at'] ?? ''));
            if ($at === '') {
                continue;
            }
            $out[] = [
                'at' => $at,
                'status' => $row['status'] ?? 'pending',
                'sent_at' => $row['sent_at'] ?? null,
            ];
        }

        return $out;
    }

    public static function normalizeSlots(array $times): array
    {
        $out = [];
        foreach ($times as $time) {
            $time = trim((string) $time);
            if ($time === '') {
                continue;
            }
            $out[] = ['at' => $time, 'status' => 'pending', 'sent_at' => null];
        }

        return $out;
    }

    public static function recipientKey(array $row): string
    {
        $type = $row['type'] ?? 'user';
        $id = $row['id'] ?? '';

        return $type . ':' . $id;
    }

    public static function toRecipientObject(array $row): \stdClass
    {
        $obj = new \stdClass();
        $obj->name = $row['name'] ?? 'Recipient';
        $obj->phone = $row['phone'] ?? '';
        $obj->email = $row['email'] ?? '';
        $obj->type = $row['type'] ?? 'user';
        $obj->id = $row['id'] ?? null;

        return $obj;
    }

    /** @return array<int, array{type:string,id:int|string,name:string,phone:string,email:string,label:string}> */
    private static function mergeAllCategories(?string $query): array
    {
        $all = [];
        foreach (['contestants', 'voters', 'users', 'judges', 'ambassadors'] as $cat) {
            $all = array_merge($all, self::categoryRows($cat, $query));
        }

        return $all;
    }

    /** @return array<int, array{type:string,id:int|string,name:string,phone:string,email:string,label:string}> */
    private static function categoryRows(string $category, ?string $query): array
    {
        switch ($category) {
            case 'contestants':
                return self::fromContestants($query);
            case 'voters':
                return self::fromUsersByRoles([3], 'voter', $query);
            case 'users':
                return self::fromUsersByRoles([1, 4, 5, 6, 9, 10, 11, 12, 13, 52, 53, 55], 'user', $query);
            case 'judges':
                return self::fromJudges($query);
            case 'ambassadors':
                return self::fromAmbassadors($query);
            default:
                return [];
        }
    }

    private static function fromContestants(?string $query): array
    {
        $rows = [];
        $q = Employee::where('is_active', true)->where('is_approve', true);
        if ($query) {
            $q->where(function ($b) use ($query) {
                $b->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('phone_number', 'like', '%' . $query . '%');
            });
        }
        foreach ($q->orderBy('name')->get() as $e) {
            $rows[] = self::row('contestant', $e->id, $e->name, $e->phone_number, $e->email, 'Contestant');
        }

        $userQ = User::where('is_active', true)->where('is_deleted', false)->where('role_id', 2);
        if ($query) {
            $userQ->where(function ($b) use ($query) {
                $b->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('phone', 'like', '%' . $query . '%');
            });
        }
        foreach ($userQ->orderBy('name')->get() as $u) {
            $rows[] = self::row('contestant', 'u' . $u->id, $u->name, $u->phone ?? $u->whatsapp_number, $u->email, 'Contestant (user)');
        }

        return $rows;
    }

    private static function fromUsersByRoles(array $roleIds, string $type, ?string $query): array
    {
        $q = User::where('is_active', true)->where('is_deleted', false)->whereIn('role_id', $roleIds);
        if ($query) {
            $q->where(function ($b) use ($query) {
                $b->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('phone', 'like', '%' . $query . '%');
            });
        }
        $rows = [];
        foreach ($q->orderBy('name')->get() as $u) {
            $rows[] = self::row($type, $u->id, $u->name, $u->phone ?? $u->whatsapp_number, $u->email, ucfirst($type));
        }

        return $rows;
    }

    private static function fromJudges(?string $query): array
    {
        $rows = [];
        $q = Judge::where('is_active', true);
        if ($query) {
            $q->where(function ($b) use ($query) {
                $b->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('phone_number', 'like', '%' . $query . '%');
            });
        }
        foreach ($q->orderBy('name')->get() as $j) {
            $rows[] = self::row('judge', $j->id, $j->name, $j->phone_number, $j->email, 'Judge');
        }

        $userQ = User::where('is_active', true)->where('is_deleted', false)->where('role_id', 54);
        if ($query) {
            $userQ->where(function ($b) use ($query) {
                $b->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('phone', 'like', '%' . $query . '%');
            });
        }
        foreach ($userQ->orderBy('name')->get() as $u) {
            $rows[] = self::row('judge', 'u' . $u->id, $u->name, $u->phone ?? $u->whatsapp_number, $u->email, 'Judge (user)');
        }

        return $rows;
    }

    private static function fromAmbassadors(?string $query): array
    {
        $rows = [];
        $q = Ambassador::where('is_active', true);
        if ($query) {
            $q->where(function ($b) use ($query) {
                $b->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('phone_number', 'like', '%' . $query . '%');
            });
        }
        foreach ($q->orderBy('name')->get() as $a) {
            $rows[] = self::row('ambassador', $a->id, $a->name, $a->phone_number, $a->email, 'Ambassador');
        }

        $userQ = User::where('is_active', true)->where('is_deleted', false)->where('role_id', 14);
        if ($query) {
            $userQ->where(function ($b) use ($query) {
                $b->where('name', 'like', '%' . $query . '%')
                    ->orWhere('email', 'like', '%' . $query . '%')
                    ->orWhere('phone', 'like', '%' . $query . '%');
            });
        }
        foreach ($userQ->orderBy('name')->get() as $u) {
            $rows[] = self::row('ambassador', 'u' . $u->id, $u->name, $u->phone ?? $u->whatsapp_number, $u->email, 'Ambassador (user)');
        }

        return $rows;
    }

    private static function row(string $type, $id, $name, $phone, $email, string $label): array
    {
        return [
            'type' => $type,
            'id' => $id,
            'name' => (string) $name,
            'phone' => trim((string) $phone),
            'email' => (string) $email,
            'label' => $label,
        ];
    }

    private static function decodeJson($value): array
    {
        if (is_array($value)) {
            return $value;
        }
        if (!is_string($value) || trim($value) === '') {
            return [];
        }
        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }

    /** @param array<int, array<string, mixed>> $rows */
    private static function dedupe(array $rows): array
    {
        $seen = [];
        $out = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }
            $phone = preg_replace('/\D+/', '', (string) ($row['phone'] ?? ''));
            $key = $phone !== '' ? 'p:' . $phone : self::recipientKey($row);
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $out[] = $row;
        }

        return $out;
    }

    private static function resolveLegacy(Announcement $announcement): array
    {
        if ($announcement->people_type === 'csv') {
            return self::fromCsv($announcement->to);
        }

        $ids = array_filter(array_map('trim', explode(',', (string) $announcement->to)));
        $rows = [];
        foreach ($ids as $id) {
            $user = User::find($id);
            if ($user) {
                $type = (int) $user->role_id === 3 ? 'voter' : ((int) $user->role_id === 2 ? 'contestant' : 'user');
                $rows[] = self::row($type, $user->id, $user->name, $user->phone ?? $user->whatsapp_number, $user->email, 'User');
            }
        }

        return self::dedupe($rows);
    }

    private static function fromCsv(string $filename): array
    {
        $path = public_path('announcement/csv/' . $filename);
        if (!is_file($path)) {
            return [];
        }
        $rows = [];
        $file = fopen($path, 'r');
        if ($file === false) {
            return [];
        }
        $first = true;
        while (($data = fgetcsv($file)) !== false) {
            if ($first) {
                $first = false;
                continue;
            }
            $rows[] = self::row('csv', md5(($data[1] ?? '') . ($data[0] ?? '')), $data[0] ?? 'Recipient', $data[1] ?? '', $data[2] ?? '', 'CSV');
        }
        fclose($file);

        return self::dedupe($rows);
    }
}
