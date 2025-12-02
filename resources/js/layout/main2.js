 document.addEventListener('DOMContentLoaded', function() {
     const mobileMenuButton = document.getElementById('mobile-menu-button');
     const mobileMenu = document.getElementById('mobile-menu');

     // Toggle Mobile Menu
     mobileMenuButton.addEventListener('click', function() {
         mobileMenu.classList.toggle('hidden');
     });

     // Close mobile menu when a link is clicked (for smooth scrolling UX)
     const mobileLinks = mobileMenu.querySelectorAll('a');
     mobileLinks.forEach(link => {
         link.addEventListener('click', () => {
             if (!mobileMenu.classList.contains('hidden')) {
                 mobileMenu.classList.add('hidden');
             }
         });
     });

     // Optional: Header background change on scroll (for sticky header effect)
     const navbar = document.getElementById('clx_navbar');
     window.addEventListener('scroll', function() {
         if (window.scrollY > 50) {
             navbar.classList.remove('clx_header_main');
             navbar.classList.add('shadow-chronos-glow', 'bg-chronos-dark/95');
         } else {
             navbar.classList.add('clx_header_main');
             navbar.classList.remove('shadow-chronos-glow', 'bg-chronos-dark/95');
         }
     });
 });