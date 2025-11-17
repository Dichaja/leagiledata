document.addEventListener('DOMContentLoaded', function() {
    // ----- Mobile menu -----
    const mobileMenuButton = document.getElementById('mobileMenuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuIcon = document.getElementById('menuIcon');
    const authToggleBtn = document.getElementById('authToggleBtn');
    const authDropdown = document.getElementById('authDropdown');
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    const carousel = document.getElementById('reportCarousel');

    // Header search functionality
  const headerSearchInput = document.getElementById('header-search-input');
  const headerSearchBtn = document.getElementById('header-search-btn');
  const searchCategory = document.getElementById('search-category');
  const mobileSearchInput = document.getElementById('mobile-search-input');
  const mobileSearchBtn = document.getElementById('mobile-search-btn');

  function performSearch(searchQuery, category = '') {
      const baseUrl = window.location.origin + window.location.pathname.replace(/[^/]*$/, '');
      let url = baseUrl + 'categories.php';
      const params = new URLSearchParams();
      
      if (searchQuery) params.append('search', searchQuery);
      if (category) params.append('category', category);
      
      if (params.toString()) {
          url += '?' + params.toString();
      }
      
      window.location.href = url;
  }

  // Desktop search
  if (headerSearchBtn && headerSearchInput) {
      headerSearchBtn.addEventListener('click', () => {
          const query = headerSearchInput.value.trim();
          const category = searchCategory ? searchCategory.value : '';
          if (query || category) {
              performSearch(query, category);
          }
      });

      headerSearchInput.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') {
              const query = headerSearchInput.value.trim();
              const category = searchCategory ? searchCategory.value : '';
              if (query || category) {
                  performSearch(query, category);
              }
          }
      });
  }

  // Mobile search
  if (mobileSearchBtn && mobileSearchInput) {
      mobileSearchBtn.addEventListener('click', () => {
          const query = mobileSearchInput.value.trim();
          if (query) {
              performSearch(query);
          }
      });

      mobileSearchInput.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') {
              const query = mobileSearchInput.value.trim();
              if (query) {
                  performSearch(query);
              }
          }
      });
  }


    if (mobileMenuButton && mobileMenu && menuIcon) {
        mobileMenuButton.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileMenu.classList.toggle('open');

            // Toggle between hamburger and close icon
            if (mobileMenu.classList.contains('open')) {
                menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
            } else {
                menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenu.contains(e.target) && e.target !== mobileMenuButton) {
                mobileMenu.classList.remove('open');
                menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
            }
        });

        // Prevent clicks inside menu from closing it
        mobileMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }


    if (userMenuBtn && userDropdown) {
        // Toggle dropdown on click
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userDropdown.contains(e.target) && e.target !== userMenuBtn) {
                userDropdown.classList.add('hidden');
            }
        });

        // Prevent clicks inside dropdown from closing it
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
});
