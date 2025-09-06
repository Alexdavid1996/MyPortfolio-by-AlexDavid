@php
    $title = $meta['title'] ?? ($settings->site_name ?? config('app.name'));
    $description = $meta['description'] ?? null;
    $canonical = $meta['canonical'] ?? url()->current();
    $url = $meta['url'] ?? $canonical;
    $type = $meta['type'] ?? 'website';
    $image = $meta['image'] ?? asset('favicon.ico');
@endphp
<title>{{ $title }}</title>
@if($description)
<meta name="description" content="{{ strip_tags($description) }}">
@endif
<link rel="canonical" href="{{ $canonical }}">
@if(isset($meta['robots']))
<meta name="robots" content="{{ $meta['robots'] }}">
@endif
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}">
@if($description)
<meta property="og:description" content="{{ strip_tags($description) }}">
@endif
<meta property="og:image" content="{{ $image }}">
<meta property="og:url" content="{{ $url }}">
@if(isset($meta['published_time']))
<meta property="article:published_time" content="{{ $meta['published_time'] }}">
@endif
@if(isset($meta['modified_time']))
<meta property="article:modified_time" content="{{ $meta['modified_time'] }}">
@endif
@if(isset($meta['section']))
<meta property="article:section" content="{{ $meta['section'] }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
@if($description)
<meta name="twitter:description" content="{{ strip_tags($description) }}">
@endif
<meta name="twitter:image" content="{{ $image }}">
