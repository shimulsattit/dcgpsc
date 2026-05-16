{{-- Message Card Design 2: Background Image (No Overlay) --}}
@php
    // Use Uploaded Image or Google Drive URL for background image
    $uploadedUrl = $settings['message_card_bg_image'] ?? null;
    $gdriveUrl = $settings['message_card_bg_image_gdrive'] ?? null;

    $finalBgUrl = null;

    if ($uploadedUrl) {
        $finalBgUrl = \Illuminate\Support\Facades\Storage::disk('r2')->url($uploadedUrl);
    } elseif ($gdriveUrl) {
        // Convert Google Drive URL to direct image URL if needed
        if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $gdriveUrl, $matches)) {
            $fileId = $matches[1];
            $finalBgUrl = "https://drive.google.com/thumbnail?id={$fileId}&sz=w1920";
        } elseif (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $gdriveUrl, $matches)) {
            $fileId = $matches[1];
            $finalBgUrl = "https://drive.google.com/thumbnail?id={$fileId}&sz=w1920";
        } else {
            $finalBgUrl = $gdriveUrl;
        }
    }

    $bgStyle = $finalBgUrl
        ? "background: url('{$finalBgUrl}') center/cover;"
        : "background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));";
@endphp

<div class="col-md-4">
    <div class="card h-100 shadow-lg border-0" style="overflow: hidden;">
        <div class="card-header py-2 text-center"
            style="background-color: {{ $settings['card_header_color'] ?? 'var(--primary-color)' }}; color: white; border-bottom: 2px solid var(--accent-color);">
            <h6 class="mb-0 fw-bold">{{ $title }}</h6>
        </div>
        <div class="card-body text-center position-relative"
            style="{{ $bgStyle }} color: white; padding: 1.5rem !important; display: flex; flex-direction: column; align-items: center; justify-content: center;">
            @if($message->image_url)
                <div class="message-card-img-container">
                    <img src="{{ $message->image_url }}" alt="{{ $message->name }}" class="message-card-img"
                        style="border: 3px solid {{ $settings['card_image_border_color'] ?? 'white' }};"
                        referrerpolicy="no-referrer">
                </div>
            @endif
            <h6 class="mb-1 fw-bold"
                style="color: {{ $settings['card_name_text_color'] ?? 'var(--accent-color)' }}; font-size: 1.1rem;">
                {{ $message->name }}
            </h6>
            <small class="d-block mb-3"
                style="color: {{ $settings['card_designation_text_color'] ?? '#f0f0f0' }}; font-size: 0.875rem;">{{ $message->designation }}</small>
            @if($message->slug)
                <a href="{{ route('message.show', $message->slug) }}" class="btn btn-sm rounded-pill px-4 fw-bold"
                    style="background-color: {{ $settings['card_button_bg_color'] ?? 'var(--secondary-color)' }}; color: {{ $settings['card_button_text_color'] ?? 'white' }}; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">Read
                    Message</a>
            @endif
        </div>
    </div>
</div>