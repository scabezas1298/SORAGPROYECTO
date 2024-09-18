<script src="../../public/js/ciudadano/ciudadano.js"></script>
<script>
     function setActiveLink() {
        const links = document.querySelectorAll('.nav-bar a.nav-link');
        let currentPage = localStorage.getItem('activePage');

        // Si no hay una página activa en localStorage, establecer 'ciudadano.php' como predeterminada
        if (!currentPage) {
            currentPage = 'ciudadano.php';
            localStorage.setItem('activePage', currentPage);
        }

        links.forEach(link => {
            if (link.getAttribute('data-page') === currentPage) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }

            link.addEventListener('click', function() {
                localStorage.setItem('activePage', this.getAttribute('data-page'));
                links.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }

    // Establecer el enlace activo al cargar la página
    setActiveLink();
</script>
</body>
</html>