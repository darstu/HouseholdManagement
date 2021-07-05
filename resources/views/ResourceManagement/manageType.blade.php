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
    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    @if($title == 'Supplier type')
        <a style="position: center" href="{{ route('CreateSupplier', ['Id' => $house->id_Home]) }}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <br>
        <h3>Supplier types:</h3>

        <a class="btn btn-secondary " href="{{ route('CreateSupplierType', ['Id' => $house->id_Home]) }}">
            <i class="fa fa-plus" aria-hidden="true"></i> Add type</a>
<!--        <a class="btn btn-dark" style="float: right" href="{{--{{ route('CreateSupplier', ['Id' => $house->id_Home]) }}--}}">Back</a>-->
        <div class="card marginfix col-lg-6 col-sm-12" style="padding-left: 30px; margin-top: 20px; padding-top: 10px; text-align: left; width: fit-content">
            @if($sc == 0)
                <p>Household has no supplier types</p>
            @else
                @foreach($sup_type as $stype)
                            <p> {{$stype->Name}}  [{{$stype->Description}}] &nbsp
                                @if($permission_delete->restricted == 1)
                                @else
                                <a onclick="return confirm('Do you really want to delete this supplier type?')" class="btn btn-danger" style="margin-right: 10px; float: right" href="{{ route('removeSupplierType', ['type_id' => $stype->type_id]) }}">
                                    Delete</a>
                                @endif
                                @if($permission_edit->restricted == 1)
                                @else
                                <a class="btn btn-primary" style="margin-right: 10px; float: right" href="{{route('SupplierTypeEdit', ['id_house' => $house->id_Home, 'Id' => $stype->type_id])}}">
                                    Edit</a>
                                @endif
                            </p>
                @endforeach
            @endif
        </div>
    @elseif($title == 'Stock type')
        <a style="position: center" href="{{ route('CreateStockCard', ['Id' => $house->id_Home]) }}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <br>
        <h3>Resource types:</h3> <a class="btn btn-secondary " href="{{ route('CreateStockType', ['Id' => $house->id_Home]) }}">
        <i class="fa fa-plus" aria-hidden="true"></i> Add type</a>
<!--        <a class="btn btn-dark " style="float: right" href="">
            Back</a>-->
        <div class="card marginfix col-lg-6 col-sm-12" style="padding-left: 30px; margin-top: 20px; padding-top: 10px; text-align: left; width: fit-content">

            @if($sc == 0)
                <p>Household has no resource types</p>
            @else
                @foreach($sto_type as $stype)
                    <p>{{$stype->Type_name}} [{{$stype->Type_description}}]  &nbsp
                        @if($permission_delete->restricted == 1)
                        @else
                        <a onclick="return confirm('Do you really want to delete this resource type?')" class="btn btn-danger" style="margin-right: 10px; float: right" href="{{ route('removeStockType', ['id_Stock_type' => $stype->id_Stock_type]) }}">
                            Delete</a>
                        @endif
                        @if($permission_edit->restricted == 1)
                        @else
                        <a class="btn btn-primary" style="margin-right: 10px; float: right" href="{{route('StockTypeEdit', ['id_house' => $house->id_Home, 'Id' => $stype->id_Stock_type])}}">
                            Edit</a>
                        @endif
                    </p>
                @endforeach
            @endif
        </div>
    @endif
@endsection

<style>
    .marginfix {
        text-align: left;
        margin-bottom: 10px !important;
    }
</style>
