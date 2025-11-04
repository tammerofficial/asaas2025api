<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Central Dashboard V1 - Vue.js</title>
    
    @vite(['resources/css/central-v1.css', 'resources/js/central/central-v1.js'])
</head>
<body>
    <div id="central-v1-app">
        <!-- Vue.js App will mount here -->
        <div style="display: flex; align-items: center; justify-content: center; height: 100vh; background: #f5f7fa;">
            <div style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 20px;">⏳</div>
                <h2 style="color: #64748b; font-weight: 500;">Loading Vue.js Dashboard V1...</h2>
            </div>
        </div>
    </div>
    
    <script>
        // Set API base URL to Web Routes (not /api/)
        window.API_BASE_URL = '{{ url('/admin-home/v1/api') }}';
        
        // Set CSRF token for axios
        window.csrfToken = '{{ csrf_token() }}';
    </script>
</body>
</html>

