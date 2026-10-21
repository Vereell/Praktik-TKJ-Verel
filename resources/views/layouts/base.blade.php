<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', config('yoghurtq.name'))</title>
<link rel="icon" href="{{ asset('images/logo.png') }}">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = { theme: { extend: { colors: {
  cream: { DEFAULT: '#F4EFE4', dark: '#EDE5D4' },
  teal: { DEFAULT: '#1B4D4D', light: '#2A6B6B', dark: '#133838' },
  gold: { DEFAULT: '#C4984A', light: '#D4AE6A', dark: '#A07A2E' },
  charcoal: '#1C1C1C', muted: '#7A7060'
} } } }
</script>
<link rel="stylesheet" href="{{ asset('css/yoghurtq.css') }}">
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('head')
</head>
<body>
@yield('body')
@stack('scripts')
</body>
</html>
