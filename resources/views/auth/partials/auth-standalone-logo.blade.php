<div class="auth-logo">
    @if($general_setting->site_logo)
        <img src="{{ url('public/logo', $general_setting->site_logo) }}" alt="logo">
    @else
        <i class="fa fa-shield" style="font-size:40px;color:#1d4ed8;"></i>
    @endif
</div>
