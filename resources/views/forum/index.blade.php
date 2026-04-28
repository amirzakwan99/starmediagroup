@extends('layouts.admin')

@push('styles')
<style>
.tox-tinymce-content {
    font-family: Helvetica, Arial, sans-serif;
    font-size: 14px;
    line-height: 1.6;
    padding: 15px;
}

.tox-tinymce-content h1,
.tox-tinymce-content h2,
.tox-tinymce-content h3 {
    margin-top: 20px;
    margin-bottom: 10px;
}

.tox-tinymce-content p {
    margin-bottom: 10px;
}

.tox-tinymce-content ul,
.tox-tinymce-content ol {
    margin-left: 20px;
}
</style>
@endpush

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Forum') }}</h1>

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card shadow mb-4">

                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="tox-tinymce-content">
                                {!! $content !!}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Social Share Buttons -->
                    <div class="d-flex justify-content-center mt-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-primary btn-sm mx-1 share-button" data-platform="facebook" title="Share on Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode('Check out this forum post') }}" target="_blank" class="btn btn-info btn-sm mx-1 share-button" data-platform="twitter" title="Share on X (Twitter)">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode('Check out this forum post: ' . url()->current()) }}" target="_blank" class="btn btn-success btn-sm mx-1 share-button" data-platform="whatsapp" title="Share on WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="https://t.me/share/url?url={{ urlencode(url()->current()) }}&text={{ urlencode('Check out this forum post') }}" target="_blank" class="btn btn-primary btn-sm mx-1 share-button" data-platform="telegram" title="Share on Telegram">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                        <a href="mailto:?subject={{ urlencode('Forum Post') }}&body={{ urlencode('Check out this forum post: ' . url()->current()) }}" class="btn btn-secondary btn-sm mx-1 share-button" data-platform="email" title="Share via Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shareButtons = document.querySelectorAll('.share-button');
        const forumId = {{ isset($forumId) ? $forumId : 1 }}; // Assuming forum_id is 1 if not passed
        const platforms = @json($platforms);

        shareButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                const platformName = this.getAttribute('data-platform');
                const url = window.location.href;

                const platform = platforms.find(p => p.name.toLowerCase() === platformName);
                if (!platform) {
                    console.error('Platform not found:', platformName);
                    return;
                }

                // Send the share data to the backend
                fetch('/shares', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        forum_id: forumId,
                        platform_id: platform.id,
                        url: url,
                    }),
                })
                .catch(error => {
                    console.error('Error recording share:', error);
                    // Don't prevent the share even if tracking fails
                });

                // Allow the link to open normally
            });
        });
    });
</script>
@endpush
