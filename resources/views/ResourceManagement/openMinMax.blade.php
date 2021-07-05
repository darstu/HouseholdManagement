@extends('householdActionMenu')
@section('Content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container-fluid">
        <div class="managebar container-fluid">
            <div class="row mt-4">
                <div class="col-8">
                    <h1 >{{$title}}</h1>
                </div>
                <div class="col-4">
                    <div class="btn-group float-right mt-2" role="group">
                                                <a class="btn btn-secondary "  href="{{ route('setMinMax', ['Id' => $house->id_Home]) }}">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add new</a>
                        {{--                    <a class="btn btn-md btn-secondary" href="#">--}}
                        {{--                        <i class="fa fa-flag" aria-hidden="true"></i> Report</a>--}}
                    </div>

                </div>
{{--                <div class="col-2">--}}
{{--                    <div class="btn-group float-right mt-2" role="group">--}}
{{--                        <a class="btn btn-secondary "  href="{{route('editMinMax', ['Id' => $house->id_Home,--}}
{{--                     'set_id' => $item->fk_Stock_card, 'set_id'=>$item->fk_Warehouse_place])}}">--}}
{{--                            <i class="fa fa-plus" aria-hidden="true"></i> Add new</a>--}}
{{--                        --}}{{--                    <a class="btn btn-md btn-secondary" href="#">--}}
{{--                        --}}{{--                        <i class="fa fa-flag" aria-hidden="true"></i> Report</a>--}}
{{--                    </div>--}}

{{--                </div>--}}
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                @foreach($items as $it)
                    <div class="col col-md-4">
                        {{--                <a href="#">--}}
                        {{--                        <a href="{{ route('resourceView', ['Id' => $house->id_Home, 'id_resource'=> $resource->fk_Stock_card])}}" >--}}
                        <div class="card">
                            {{--                    <img class="card-img-top" src="..." alt="Card image cap">--}}
                            <div class="card-body">
                                <h5 class="card-title">{{$it->Stock_Card->Name}} | {{$it->Warehouse_Place->Warehouse_name}}</h5>
                                <p class="info-wrap card-text">Min. quantity: {{$it->min_amount}} {{$it->Stock_Card->measurement_unit}}</p>
                                <p class="info-wrap card-text">Max. quantity: {{$it->max_amount}} {{$it->Stock_Card->measurement_unit}}</p>
                            </div>
                            <a class="btn btn-secondary "  href="{{route('editMinMax', ['Id' => $house->id_Home,
                                                 'set_id' => $it->fk_Stock_card, 'set_id2'=>$it->fk_Warehouse_place])}}">
                                                        <i aria-hidden="true"></i> Edit</a>
                        </div>
                        {{--                        </a>--}}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div style="float: right">{{$items->appends($_GET)->links()}}</div>
@endsection
