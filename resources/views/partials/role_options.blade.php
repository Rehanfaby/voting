@php
    if (!function_exists('roleDisplayLabel')) {
        function roleDisplayLabel($role) {
            $id = is_object($role) ? ($role->id ?? null) : null;
            $name = strtolower(is_object($role) ? ($role->name ?? '') : (string) $role);
            if ($id == 2 || in_array($name, ['contestant', 'musician', 'employee'], true)) {
                return trans('file.Contestant');
            }
            if ($id == 3 || $name === 'voter') {
                return trans('file.Voter');
            }
            if ($id == 1 || $name === 'admin') {
                return trans('file.Admin');
            }
            return ucfirst($role->name ?? $name);
        }
    }
@endphp
@foreach($lims_role_list as $role)
    <option value="{{ $role->id }}">{{ roleDisplayLabel($role) }}</option>
@endforeach
