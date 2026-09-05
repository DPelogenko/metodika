document.addEventListener('DOMContentLoaded', function() {
    const burger = document.querySelector('.burger_menu');
    const mobileNav = document.querySelector('.header_nav_mobile');

    if (burger && mobileNav) {
        burger.addEventListener('click', function() {
            this.classList.toggle('active');
            mobileNav.classList.toggle('active');
        });
    }

    // Подменю по клику (для обоих меню)
    document.querySelectorAll('.menu_link_has_children').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.closest('.menu_item_has_children');
            if (parent) {
                const isActive = parent.classList.contains('active');
                // Закрываем все открытые подменю
                document.querySelectorAll('.menu_item_has_children.active').forEach(function(el) {
                    el.classList.remove('active');
                });
                // Если текущее не было активно - открываем
                if (!isActive) {
                    parent.classList.add('active');
                }
            }
        });
    });

    // Закрыть при клике вне
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.header') && !e.target.closest('.burger_menu')) {
            document.querySelectorAll('.menu_item_has_children.active').forEach(function(el) {
                el.classList.remove('active');
            });
            if (mobileNav) mobileNav.classList.remove('active');
            if (burger) burger.classList.remove('active');
        }
    });
});