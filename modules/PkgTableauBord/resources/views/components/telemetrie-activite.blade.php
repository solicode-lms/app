@props([
    'typeActivite' => 'PILOTAGE_KANBAN',
    'intervalleSecondes' => 60,
    'seuilInactiviteMinutes' => 5
])

<script>
(function() {
    // Télémétrie didactique — Suivi de la Charge Administrative ($C_logistique)
    var typeActivite = @json($typeActivite);
    var intervalleMs = {{ (int) $intervalleSecondes }} * 1000;
    var seuilInactiviteMs = {{ (int) $seuilInactiviteMinutes }} * 60 * 1000;
    var derniereInteraction = Date.now();
    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
    var pingUrl = '{{ route("admin.PkgTableauBord.telemetrie.activite") }}';

    function enregistrerActivite() {
        derniereInteraction = Date.now();
    }

    var evenements = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'];
    evenements.forEach(function(evt) {
        window.addEventListener(evt, enregistrerActivite, { passive: true });
    });

    setInterval(function() {
        var tempsEcoule = Date.now() - derniereInteraction;
        // Verrou d'inactivité didactique : ne comptabiliser que si actif dans les 5 dernières minutes
        if (tempsEcoule < seuilInactiviteMs) {
            fetch(pingUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    type_activite: typeActivite,
                    duree_secondes: {{ (int) $intervalleSecondes }}
                })
            }).catch(function() {
                // Télémétrie non bloquante en cas de perte de connectivité
            });
        }
    }, intervalleMs);
})();
</script>
