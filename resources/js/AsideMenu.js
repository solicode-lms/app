
export default class AsideMenu {
    static init(){

        // Récupération des valeurs depuis localStorage
        let sidebarState = localStorage.getItem('sidebarState') || 'collapsed';
        let activeMenuPackageItems = JSON.parse(localStorage.getItem('activeMenuPackageItems')) || [];
        // let activeMenuItem = localStorage.getItem('activeMenuItem') || '';
   
        let activeMenuItem =$('.nav-sidebar .nav-item:not(.has-treeview) .nav-link.active').parent().attr('id');
        localStorage.setItem('activeMenuItem', activeMenuItem || '');

    
        // Appliquer l'état initial de la sidebar et restaurer l'état des menus actifs
        if (sidebarState === 'collapsed') {
            $('body').addClass('sidebar-collapse');
            closeAllMenus();
        } else {
            restoreMenuState();
        }
    
        // Gérer le bouton toggle de la sidebar
        $('.nav-link[data-widget="pushmenu"]').on('click', function () {
            setTimeout(() => {
                if ($('body').hasClass('sidebar-collapse')) {
                    localStorage.setItem('sidebarState', 'collapsed');
                    closeAllMenus();
                } else {
                    localStorage.setItem('sidebarState', 'expanded');
                    restoreMenuState(); // Restaurer les menus actifs après l'expansion
                }
            }, 200);
        });
    
        // Ouvrir la sidebar au survol (hover) si elle est réduite
        $('.sidebar').hover(
            function () {
                if ($('body').hasClass('sidebar-collapse')) {
                    $('body').removeClass('sidebar-collapse').addClass('sidebar-open');
                    restoreMenuState(); // Restaurer les menus ouverts au survol
                }
            },
            function () {
                if ($('body').hasClass('sidebar-open')) {
                    $('body').removeClass('sidebar-open').addClass('sidebar-collapse');
                    closeAllMenus(); // Fermer les menus quand on quitte la sidebar
                }
            }
        );
    
        // Gestion des clics sur les éléments de menu
        $('.nav-sidebar .nav-link').on('click', function (e) {
            let menuItem = $(this).parent();
            let itemId = menuItem.attr('id');
    

            // Menu Package
            if(menuItem.hasClass('has-treeview')){
                closeOtherMenu(itemId);
                if (menuItem.hasClass('menu-open')) {
                  
                    activeMenuPackageItems = activeMenuPackageItems.filter(item => item !== itemId);
                } else {
                    activeMenuPackageItems = [itemId];
                } 
                localStorage.setItem('activeMenuPackageItems', JSON.stringify(activeMenuPackageItems));
            }
            // Enregistrer activeMenuItem
            // if(menuItem.hasClass('nav-treeview')){
            //     localStorage.setItem('activeMenuItem', itemId);                 
            // }

            // e.stopPropagation();
        });
    
        // Fonction pour fermer tous les menus
        function closeAllMenus() {
            $('.nav-sidebar .nav-item').removeClass('menu-open');
            $('.nav-sidebar .nav-item .nav-link').removeClass('active');
            $('.nav-sidebar .nav-item .nav-treeview').slideUp();
        }
        function closeOtherMenu(itemId) {
            $('.nav-sidebar .nav-item').each(function () {
                let menuItem = $(this);
                if (menuItem.attr('id') !== itemId) {
                    menuItem.removeClass('menu-open');
                    menuItem.children('.nav-link').removeClass('active');
                    menuItem.children('.nav-treeview').slideUp();
                }
            });
        }
        // Fonction pour restaurer l'état des menus actifs
        function restoreMenuState() {
            $('.nav-sidebar .nav-item').each(function () {
                let menuItem = $(this);
                let itemId = menuItem.attr('id');
    
                if (activeMenuPackageItems.includes(itemId)) {
                    menuItem.addClass('menu-open');
                    menuItem.children('.nav-link').addClass('active');
                    menuItem.children('.nav-treeview').show();
                }

                if (activeMenuItem == itemId) {
                    menuItem.children('.nav-link').addClass('active');
                    // menuItem.children('.nav-treeview').show();
                }
               
            });   

        }
    }
}

 