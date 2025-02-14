// Classe utilitaire pour les tableaux
export default class ArrayUtil {
    // Fonction 1 : Supprimer les éléments correspondant à une condition
    static remove(array, condition) {
        for (let i = array.length - 1; i >= 0; i--) {
            if (condition(array[i])) {
                array.splice(i, 1);
            }
        }
        return array; // Retourne le tableau modifié
    }

  // Fonction 2 : Convertir un objet en tableau
  static objectToArray(data) {

        const tableau = [];
        Object.entries(data).forEach(([key, value]) => {
            tableau[key] = value;
        });


        return tableau;
    }

}
