<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Select2 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- jQuery and Select2 JS -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        
        <script>
            // Initialize Select2 Elements
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Pilih...",
                    allowClear: true,
                    width: '100%'
                });
                
                // Initialize Select2 Ajax Elements
                $('.select2-ajax').each(function() {
                    let $element = $(this);
                    let url = $element.data('url');
                    
                    $element.select2({
                        placeholder: $element.data('placeholder') || "Pilih...",
                        allowClear: $element.data('allow-clear') !== false,
                        width: '100%',
                        ajax: {
                            url: url,
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    search: params.term, // search term
                                    page: params.page || 1
                                };
                            },
                            processResults: function (data, params) {
                                params.page = params.page || 1;
                                return data;
                            },
                            cache: true
                        }
                    });
                });
            });
        </script>
    </body>
</html>
