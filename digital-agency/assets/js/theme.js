// Auto-execute inline to prevent FOUC (Flash of Unstyled Content)
(function() {
    // Check local storage for theme
    const savedTheme = localStorage.getItem('agency_theme');
    
    if (savedTheme === 'light') {
        document.documentElement.classList.add('light-mode');
        document.addEventListener('DOMContentLoaded', () => {
            document.body.classList.add('light-mode');
        });
    }

    // Bind event listener to the toggle button once the DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtns = document.querySelectorAll('.trigger-theme');
        
        function updateIcons(isLight) {
            toggleBtns.forEach(btn => {
                btn.innerHTML = isLight ? '🌙' : '☀️';
            });
        }

        if (toggleBtns.length > 0) {
            // Set initial icons
            const isLight = document.body.classList.contains('light-mode');
            updateIcons(isLight);

            toggleBtns.forEach(toggleBtn => {
                toggleBtn.addEventListener('click', () => {
                    document.body.classList.toggle('light-mode');
                    
                    if (document.body.classList.contains('light-mode')) {
                        localStorage.setItem('agency_theme', 'light');
                        updateIcons(true);
                    } else {
                        localStorage.setItem('agency_theme', 'dark');
                        updateIcons(false);
                    }
                });
            });
        }
    });
})();
