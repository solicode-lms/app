

this.$watch('$store.qcm.activeUaIndex', () => {
                this.$nextTick(() => {
                    this.scrollToIdealPosition();
                });
            });


- pourquoi on utilise $watch, alpine ne génére pas par défaut la gestion d'état : conception reactive ?

---

pour question d'organisation, c'est quoi la mellieur architecture pour strcutrer le code Alpine, dans un seul fichier, chaque composant dans un fichier, ? Comment organiser le code js avec son code html 

---