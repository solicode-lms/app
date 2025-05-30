<div class="widget col-lg-6 col-6" data-id="{{$widgetUtilisateur->id}}">
    <div class="card">
        <div class="card-header  text-{{$widget?->sysColor?->textColor}}" style="background-color:  {{ $widget->sysColor?->hex}}">
            <h3 class="card-title"> 
                <i class="fas {{ $widget->icon }}"></i> {{ $widget->name }}
            </h3>
            <div class="card-tools ">
                <span title="Nombre des éléménts" class="badge badge-info">{{ $widget->count }}</span>

                
                @if($widget->link )
                <a href="{{ $widget->link }}" class="btn btn-tool text-{{$widget?->sysColor?->textColor}} showIndex"  title="Voir les détails">  
                    <i class="fas fa-search-plus"></i></a>
                @endif

                <button type="button" class="btn btn-tool text-{{$widget?->sysColor?->textColor}}" 
                    data-store-key="collapse-widget-{{ $widget->id }}"
                    data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool text-{{$widget?->sysColor?->textColor}}" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
             
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        @if(!empty($widget->data) && isset($widget->data[0]))
                            @foreach(array_keys((array)$widget->data[0]) as $key)
                                <th>{{ ucfirst($key) }}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($widget->data ?? [] as $row)
                        <tr>
                            @foreach ((array)$row as $cell)
                                <td>
                                    @if(is_array($cell))
                                        @switch($cell['nature'] ?? 'String')
                                            @case('badge')
                                                <span class="badge" style="background-color: {{ $cell['couleur'] ?? '#999' }}; color:{{ $cell['textCouleur'] ?? '#000' }}">
                                                    {{ $cell['value'] }}
                                                </span>
                                                @break
                                            @case('progression')
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar bg-green" role="progressbar" aria-valuenow="{{ $cell['value'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $cell['value'] }}%">
                                                    </div>
                                                </div>
                                                <small>
                                                    {{ $cell['value'] }}% Terminé
                                                </small>
                                                @break
                                            @case('String')
                                            @default
                                                {{ $cell['value'] }}
                                        @endswitch
                                    @else
                                        {{ $cell }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            @if($widget->link )
            <a href="{{ $widget->link }}" class="small-box-footer"> Voir les détails <i class="fas fa-arrow-circle-right"></i></a>
            @endif
           
        </div>
    </div>
    
</div>

