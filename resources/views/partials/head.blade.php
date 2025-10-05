<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Theme initialization - must run before Flux appearance -->
<script>
    // Immediate theme initialization to prevent flash
    (function() {
        const html = document.documentElement;
        const savedTheme = localStorage.getItem('theme');
        
        console.log('Saved theme from localStorage:', savedTheme);
        
        // If no saved theme, default to dark
        const theme = savedTheme || 'dark';
        
        console.log('Applying theme:', theme);
        
        if (theme === 'dark') {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        
        console.log('HTML classes after theme init:', html.className);
    })();
</script>

@fluxAppearance
