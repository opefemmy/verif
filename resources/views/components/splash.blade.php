<div id="splash-screen" class="fixed inset-0 z-[9999] flex items-center justify-center bg-white transition-opacity duration-700">
    <div class="text-center">
        @if($institutionSettings && $institutionSettings->logo)
            <img src="{{ Storage::url($institutionSettings->logo) }}" class="h-32 w-auto animate-pulse" alt="Logo">
        @else
            <span class="text-6xl animate-pulse">🏛️</span>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const splash = document.getElementById('splash-screen');
            if (splash) {
                splash.classList.add('opacity-0');
                setTimeout(function() {
                    splash.style.display = 'none';
                }, 700);
            }
        }, 5000);
    });
</script>
