@extends('layouts.app')

@section('title', $page->meta_title ?? $page->title)

@section('meta_description', $page->meta_description)

@section('content')
    <div class="container my-5">
        <!-- Page Header Section -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header text-white py-3" style="background-color: var(--primary-color);">
                <h3 class="mb-0 fw-bold" style="font-size: 1.2rem;">
                    <i class="fas fa-file-alt me-2"></i>
                    {{ $page->title }}
                </h3>
            </div>
        </div>

        <!-- Page Content -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                @if($page->image_url)
                    <div class="mb-4 text-center overflow-hidden rounded shadow-sm">
                        <img src="{{ \App\Helpers\GoogleDriveHelper::getDirectUrl($page->image_url) }}" 
                             alt="{{ $page->title }}" 
                             class="img-fluid w-100" 
                             style="max-height: 600px; object-fit: cover; object-position: center;">
                    </div>
                @endif

                @if($page->content)
                    <div class="page-content" style="line-height: 1.8;">
                        {!! Str::markdown($page->content) !!}
                    </div>
                @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        This page has no content yet.
                    </div>
                @endif

                @if($page->html_content)
                    <div class="page-content mt-4" style="line-height: 1.8;">
                        {!! $page->html_content !!}
                    </div>
                @endif

                {{-- Display Custom Buttons --}}
                @if($page->buttons && count($page->buttons) > 0)
                    <div class="my-4">
                        @foreach($page->buttons as $button)
                            <div class="mb-2">
                                <a href="{{ $button['url'] }}" class="btn btn-{{ $button['color'] ?? 'primary' }} btn-lg w-100 text-start px-3"
                                    @if($button['open_new_tab'] ?? false) target="_blank" @endif>
                                    @if(!empty($button['icon']))
                                        <i class="fas {{ $button['icon'] }} me-3"></i>
                                    @endif
                                    {{ $button['text'] }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Display Bottom Content --}}
                @if($page->bottom_content)
                    <div class="page-content mt-4" style="line-height: 1.8;">
                        {!! Str::markdown($page->bottom_content) !!}
                    </div>
                @endif

                {{-- PDF File Display for Notices --}}
                @if(isset($page->notice) && $page->notice && $page->notice->file_url)
                    @php
                        $fileUrl = $page->notice->file;
                        $embedUrl = null;

                        // Convert Google Drive share link to embed URL
                        if (preg_match('/drive\.google\.com\/file\/d\/([^\/]+)/', $fileUrl, $matches)) {
                            $fileId = $matches[1];
                            // Use uc?export=view format for direct download
                            $downloadUrl = "https://drive.google.com/uc?export=download&id={$fileId}";
                            $previewUrl = "https://drive.google.com/file/d/{$fileId}/preview";
                        } else {
                            $downloadUrl = $fileUrl;
                            $previewUrl = $fileUrl;
                        }
                    @endphp
                    <div class="mt-4">
                        <h5 class="mb-3">
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            Attached Document
                        </h5>
                        <div class="ratio ratio-16x9" style="min-height: 600px;">
                            <iframe src="{{ $previewUrl }}" frameborder="0" allowfullscreen
                                style="border: 1px solid #ddd; border-radius: 8px;">
                            </iframe>
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ $previewUrl }}" target="_blank" class="btn btn-primary me-2">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Open in New Tab
                            </a>
                            <a href="{{ $downloadUrl }}" target="_blank" class="btn btn-success">
                                <i class="fas fa-download me-2"></i>
                                Download PDF
                            </a>
                        </div>
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>If PDF doesn't display above, please use "Open in New Tab" or "Download PDF" button.</small>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Page Content Styling */
        .page-content {
            color: var(--text-color);
        }

        .page-content h1,
        .page-content h2,
        .page-content h3,
        .page-content h4 {
            color: var(--primary-color);
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .page-content p {
            margin-bottom: 1rem;
        }

        .page-content ul,
        .page-content ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }

        .page-content a {
            color: var(--link-color);
            text-decoration: none;
        }

        .page-content a:hover {
            text-decoration: underline;
        }

        .page-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 1rem;
            margin: 1rem 0;
            font-style: italic;
            color: #666;
        }

        .page-content code {
            background-color: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }

        .page-content pre {
            background-color: #f4f4f4;
            padding: 1rem;
            border-radius: 5px;
            overflow-x: auto;
        }

        .page-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            background-color: white;
        }

        .page-content table th,
        .page-content table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .page-content table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
        }

        .page-content table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .page-content table tr:hover {
            background-color: #f5f5f5;
        }
    </style>
@endsection