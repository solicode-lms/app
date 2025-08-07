                        <div class="progress progress-sm">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $realisationProjet->progression_validation_cache }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $realisationProjet->progression_validation_cache }}%">
                            </div>
                        </div>
                        <small>
                            {{ $realisationProjet->progression_validation_cache }}% Terminé
                        </small>