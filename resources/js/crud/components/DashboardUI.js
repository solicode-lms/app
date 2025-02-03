import { SessionStateService } from './SessionStateService';
import EventUtil from '../utils/EventUtil';


// "primary":    $primary,
// "secondary":  $secondary,
// "success":    $success,
// "info":       $info,
// "warning":    $warning,
// "danger":     $danger,
// "light":      $light,
// "dark":       $dark


// $colors: () !default;
// $colors: map-merge((
//   "blue":       $blue,
//   "indigo":     $indigo,
//   "purple":     $purple,
//   "pink":       $pink,
//   "red":        $red,
//   "orange":     $orange,
//   "yellow":     $yellow,
//   "green":      $green,
//   "teal":       $teal,
//   "cyan":       $cyan,
//   "white":      $white,
//   "gray":       $gray-600,
//   "gray-dark":  $gray-800
// ), $colors);

export class DashboardUI {

    /**
     * Constructeur de DashboardUI.
     * @param {Object} config - Configuration contenant les paramètres du tableau de bord.
     */
    constructor(sessionState) {

        this.sessionStatService = new SessionStateService(sessionState);

        this.user =  {
            role : this.sessionStatService.getValue("user_role")
        }; 


        this.mainSidebarSelector = "#main-sidebar";
        this.dashboardContainer =  "#dashboard-content";
    }

    /**
     * Initialise l'adaptation du tableau de bord à l'utilisateur.
     */
    init() {
        this.customizeSidebar();
    }

    /**
     * Personnalise le menu latéral en fonction des permissions utilisateur.
     */
    customizeSidebar() {
        const mainSidebar = $(this.sidebarSelector); // Sélection correcte du sidebar
    
        // Supprimer les classes existantes qui commencent par "sidebar-dark-secondary-"
        mainSidebar.removeClass(function(index, className) {
            return (className.match(/(^|\s)sidebar-dark-\S+/g) || []).join(' ');
        });
    
        // let colors = {
        //     "color1": "#2973B2",
        //     "color2": "#0D92F4",
        //     "color3": "#229799",
        // };

        // // Appliquer la classe correspondant au rôle
        // if (this.user.role === "apprenant") {
        //     document.documentElement.style.setProperty("--gapp-info", colors.color1);
        // } else if (this.user.role === "formateur") {
        //     document.documentElement.style.setProperty("--gapp-info", colors.color2);
        // } else if (this.user.role === "gapp") {
        //     document.documentElement.style.setProperty("--gapp-info", colors.color3);
        // }else{
        //     mainSidebar.addClass("sidebar-dark-info");
        // }
    }


}
