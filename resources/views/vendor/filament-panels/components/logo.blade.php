@php
    use App\Models\SettingWeb;
    $settings = SettingWeb::first();
    $logoName = $settings->logo_1;

    // Construct the complete asset URL
    $logoUrl = asset(Storage::url('public/' . $logoName));
@endphp

<img src="{{ $logoUrl }}" alt="Logo" class="h-10" style="scale: 2;">
