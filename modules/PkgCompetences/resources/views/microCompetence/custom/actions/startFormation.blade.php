startFormation

  <a 
                            data-toggle="tooltip" 
                            title="Suivre la formation" 
                            href="{{ route('microCompetences.startFormation', ['id' => $entity->id]) }}" 
                            data-id="{{$entity->id}}" 
                            data-url="{{ route('microCompetences.startFormation', ['id' => $entity->id]) }}" 
                            data-action-type="confirm"
                            class="btn btn-default btn-sm d-none d-md-inline d-lg-inline  context-state actionEntity">
                                <i class="fas fa-graduation-cap"></i>
                            </a>