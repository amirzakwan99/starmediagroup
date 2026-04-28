@extends('layouts.admin')

@section('main-content')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">{{ __('Update Forum') }}</h1>

<div class="row justify-content-center">

    <div class="col-lg-6">

        <div class="card shadow mb-4">
            <form method="POST" action="{{ route('forum.update', 1) }}">
                @csrf
                @method('PUT')
                <textarea id="mytextarea" name="content">{{ $content }}</textarea>
                <div class="col text-center" style="margin:5px;">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    tinymce.init({
    selector: 'textarea',
    promotion: false,
    onboarding: false,
    branding: false
  });
</script>
@endpush