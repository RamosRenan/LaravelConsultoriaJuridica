@extends('layouts.app')

@section('content_header')
    <h1><i class="fa fa-phone-alt"></i> @lang('siscop.solicitations.title')</h1>
@stop

@section('js')
<script type ="text/javascript" src="{{ asset('/vendor/openlayer_v2.13.1/OpenLayers.js') }}"></script>
<script type="text/javascript" src="{{ asset('/vendor/jQuery-Mask-Plugin/jquery.mask.js') }}"></script>

<script type ="text/javascript">
var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
spOptions = {
    onKeyPress: function(val, e, field, options) {
        field.mask(SPMaskBehavior.apply({}, arguments), options);
    }
};

$('#phone').mask(SPMaskBehavior, spOptions);

var pontoCentralCoordenadas = {
    x: -49.273252,
    y: -25.428356
}

var map, srid4326, srid900913, pontoCentral, marcacoes;

function inicializaOpenLayers() {
    
    // os dados serão recebidos em EPSG 4326 e serão convertidos para EPSG 90013
    srid4326 = new OpenLayers.Projection("EPSG:4326");   // WGS 1984
    srid900913   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
    
    if (pontoCentralCoordenadas == undefined) {
        // lonlat Curitiba
        var y            = -25.428356; 
        var x            = -49.273252;
        
        pontoCentral = new OpenLayers.LonLat(x, y).transform( srid4326, srid900913);
        
    } else {
        pontoCentral = new OpenLayers.LonLat(pontoCentralCoordenadas.x, pontoCentralCoordenadas.y).transform( srid4326, srid900913);
    }
    
    var extent         = new OpenLayers.Bounds(-55,-27,-46,-22).transform(srid4326,srid900913);
    
    OpenLayers.Util.onImageLoadError = function(){this.src = '../OpenLayers-2.13.1/img/blank.gif';};
 
    map = new OpenLayers.Map(
        "map", 
        {
            restrictedExtent: extent,
            allOverlays: true,
            controls: [
                new OpenLayers.Control.Navigation({
                    dragPanOptions: {
                        enableKinetic: true
                    }
                }),
                new OpenLayers.Control.Attribution(),
                new OpenLayers.Control.Zoom()
            ]
        }
    );
    
    var mapnik = new OpenLayers.Layer.XYZ(
        "OSM-PMPR", 
        ["http://osm.pmpr.parana/osm_tiles/${z}/${x}/${y}.png"], 
        {
            sphericalMercator: true,
            transitionEffect: "resize",
            buffer: 1,
            numZoomLevels: 19,
            isBaseLayer: true
        }
    );
    
    map.addLayer(mapnik);
    map.setCenter(pontoCentral, 10, true);
}
  
// carrega ponto referente a ocorrencia
function carregarPonto(x, y, nome) {
    
    var ponto = new OpenLayers.LonLat(x, y).transform(srid4326, srid900913);

    if (marcacoes != null) {
        marcacoes.destroy();
    }
      
    marcacoes = new OpenLayers.Layer.Markers( nome );
    map.addLayer(marcacoes);
    
    var size = new OpenLayers.Size(38,36);
    var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
    var icon = new OpenLayers.Icon('../js/OpenLayers-2.13.1/img/marker.png', size, offset);
    
    var marcacao = new OpenLayers.Marker(ponto, icon.clone());
    
    marcacoes.addMarker(marcacao);

    map.setCenter(ponto, 16);
}
  
function limparPontos() {
    if (marcacoes != null) {
        marcacoes.destroy();
        map.setCenter(pontoCentral, 10, true);
    }
    for (nc in novasCamadas) {
        limparCamada(nc);
    }
}

var novasCamadas = {};

function carregarPontoEmCamada(ponto, idCamada, id, texto) {
    var marca;
    var popup;
    if (ponto instanceof Ponto) {
        var pontoOL = new OpenLayers.LonLat(ponto.srid4326.x, ponto.srid4326.y).transform(srid4326, srid900913);
        var size = new OpenLayers.Size(38,36);
        var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
        var icon = new OpenLayers.Icon('../js/OpenLayers-2.13.1/img/marker-green-t.png', size, offset);
        marca = new OpenLayers.Marker(pontoOL, icon.clone());
        
        if (typeof id != 'undefined' && typeof texto != 'undefined') {
        popup = new OpenLayers.Popup.Anchored(id,
            pontoOL,
            new OpenLayers.Size(180,60),
            texto,
            null,
            true);
        map.addPopup(popup);
        popup.hide();
        marca.events.register('click', marca, function(e) {popup.toggle();});
        }
        
        novasCamadas[idCamada]['marker'].addMarker(marca);
    }
}

function limparCamada(idCamada) {
    if (typeof novasCamadas[idCamada] != 'undefined') {
        novasCamadas[idCamada]['marker'].destroy();
        delete novasCamadas[idCamada];
    }
}
  
function criarCamada(idCamada, nomeCamada) {
    novasCamadas[idCamada] = {
        'nome' : nomeCamada,
        'marker' : new OpenLayers.Layer.Markers( nomeCamada )
    };
    map.addLayer(novasCamadas[idCamada]['marker']);
}

function existeCamada(idCamada) {
    if (typeof novasCamadas[idCamada] == "undefined") {
        return false;
    } else {
        return true;
    }
}
  
function removerPopups() {
    for (item in map.popups) {
        map.removePopup(map.popups[item]);
    }
}

$(document).ready(function() {
    $("#BMcard").hide();
    inicializaOpenLayers();
});

</script>
@stop

@section('content')
    {{ Form::open(['method' => 'POST', 'route' => ['siscop.solicitations.store'], 'autocomplete' => 'off']) }}

    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">@lang('global.app_create')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 form-group">
                    {{ Form::label('phone', __('siscop.solicitations.fields.phone').'*', ['class' => 'control-label']) }}
                    {{ Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => '']) }}
                    @if($errors->has('phone'))
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    @endif
                </div>
                <div class="col-md-4 form-group">
                    {{ Form::label('name', __('siscop.solicitations.fields.name').'*', ['class' => 'control-label']) }}
                    {{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('city', __('siscop.solicitations.fields.city').'*', ['class' => 'control-label']) }}
                    {{ Form::text('city', old('city'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('city'))
                        <span class="text-danger">{{ $errors->first('city') }}</span>
                    @endif
                </div>
                <div class="col-md-3 form-group">
                    {{ Form::label('neighborhood', __('siscop.solicitations.fields.neighborhood').'*', ['class' => 'control-label']) }}
                    {{ Form::text('neighborhood', old('neighborhood'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('neighborhood'))
                        <span class="text-danger">{{ $errors->first('neighborhood') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-5 form-group">
                    {{ Form::label('address', __('siscop.solicitations.fields.address').'*', ['class' => 'control-label']) }}
                    {{ Form::text('address', old('address'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('address'))
                        <span class="text-danger">{{ $errors->first('address') }}</span>
                    @endif
                </div>
                <div class="col-md-2 form-group">
                    {{ Form::label('number', __('siscop.solicitations.fields.number').'*', ['class' => 'control-label']) }}
                    {{ Form::text('number', old('number'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('number'))
                        <span class="text-danger">{{ $errors->first('number') }}</span>
                    @endif
                </div>
                <div class="col-md-5 form-group">
                    {{ Form::label('reference', __('siscop.solicitations.fields.reference').'*', ['class' => 'control-label']) }}
                    {{ Form::text('reference', old('reference'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('reference'))
                        <span class="text-danger">{{ $errors->first('reference') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    {{ Form::label('corner', __('siscop.solicitations.fields.corner').'*', ['class' => 'control-label']) }}
                    {{ Form::text('corner', old('corner'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('corner'))
                        <span class="text-danger">{{ $errors->first('corner') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-group">
                    {{ Form::label('crime', __('siscop.solicitations.fields.crime').'*', ['class' => 'control-label']) }}
                    {{ Form::text('crime', old('crime'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('crime'))
                        <span class="text-danger">{{ $errors->first('crime') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    {{ Form::label('description', __('siscop.solicitations.fields.description').'*', ['class' => 'control-label']) }}
                    {{ Form::textarea('description', old('description'), ['rows' => 3, 'class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('description'))
                        <span class="text-danger">{{ $errors->first('description') }}</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    {{ Form::label('unit', __('siscop.solicitations.fields.unit').'*', ['class' => 'control-label']) }}
                    {{ Form::text('unit', old('unit'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('unit'))
                        <span class="text-danger">{{ $errors->first('unit') }}</span>
                    @endif
                </div>
                <div class="col-md-6 form-group">
                    {{ Form::label('document', __('siscop.solicitations.fields.document').'*', ['class' => 'control-label']) }}
                    {{ Form::text('document', old('document'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) }}
                    @if($errors->has('document'))
                        <span class="text-danger">{{ $errors->first('document') }}</span>
                    @endif
                </div>
            </div>
            <div class="row" id="PMcard">
                <div class="col-md-4">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">@lang('siscop.solicitations.fields.time')</h3>
                        </div>
                        <div class="card-body">
                        @foreach ($time as $item)
                            <label>
                                <div class="icheck-primary d-inline">
                                    {{ Form::radio('time', $item->id, old('time') ? true : false, ['id' => 'checkboxtime'.$item->id]) }}
                                    {{ Form::label('checkboxtime'.$item->id, '&nbsp;') }}
                                </div>
                                {{ $item->name }}
                            </label>
                            <br />
                        @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">@lang('siscop.solicitations.fields.suspect')</h3>
                        </div>
                        <div class="card-body">
                        @foreach ($suspects as $item)
                            <label>
                                <div class="icheck-primary d-inline">
                                    {{ Form::radio('suspect', $item->id, old('suspect') ? true : false, ['id' => 'checkboxsuspect'.$item->id]) }}
                                    {{ Form::label('checkboxsuspect'.$item->id, '&nbsp;') }}
                                </div>
                                {{ $item->name }}
                            </label>
                            <br />
                        @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">@lang('siscop.solicitations.fields.risks')</h3>
                        </div>
                        <div class="card-body">
                        @foreach ($risks as $item)
                            <label>
                                <div class="icheck-primary d-inline">
                                    {{ Form::checkbox('risks[]', $item->id, old('risks') ? true : false, ['id' => 'checkboxrisks'.$item->id]) }}
                                    {{ Form::label('checkboxrisks'.$item->id, '&nbsp;') }}
                                </div>
                                {{ $item->name }}
                            </label>
                            <br />
                        @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div id="BMcard">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">@lang('siscop.solicitations.fields.info')</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($infos as $item)
                            <div class="col-md-4">
                                <label>
                                    <div class="icheck-primary d-inline">
                                        {{ Form::checkbox('infos[]', $item->id, old('infos') ? true : false, ['id' => 'checkboxinfo'.$item->id]) }}
                                        {{ Form::label('checkboxinfo'.$item->id, '&nbsp;') }}
                                    </div>
                                    {{ $item->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-4">
                {{ Form::submit(__('siscop.solicitations.fields.classify'), ['class' => 'btn btn-lg btn-block btn-danger']) }}
                </div>
                <div class="col-md-4">
                {{ Form::submit(__('siscop.solicitations.fields.complement'), ['class' => 'btn btn-lg btn-block btn-primary']) }}
                </div>
                <div class="col-md-4">
                {{ Form::submit(__('siscop.solicitations.fields.insert'), ['class' => 'btn btn-lg btn-block btn-success']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
