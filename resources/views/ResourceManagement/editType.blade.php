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
    @if($title == 'Supplier type')
        <br>
        <h3>Supplier type information</h3>
        <div class="card col-lg-6 col-sm-8" style="margin-top: 15px; padding-top: 20px; padding-bottom: 10px">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('confirmEditSupplierType', $stype->type_id) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group row">
                        <label style="text-align: left; font-weight: bold" for="inputName" class=" col-4">Type Name: *</label>
                        <input style="margin-left: 20px; margin-bottom: 10px" type="text" class="form-control col-7" id="inputName" required name="Name" value="{{$stype->Name}}">
                    </div>
                    <div class="form-group row">
                        <label style="text-align: left; font-weight: bold" for="inputDescription" class=" col-4">Description:</label>
                        <input style="margin-left: 20px; margin-bottom: 10px" type="text" class="form-control col-7" id="inputDescription" name="Description" value="{{$stype->Description}}">
                    </div>
                    <p class="requiredfield">* Required fields</p>
                    <div style="padding-left: 15px; margin-right: 5%">
                        <button style="float: right" type="submit" class="btn btn-primary">Save Type</button>
                    </div>
                </form>
    </div>
    @elseif($title = 'Stock type')
        <h3>Resource type information</h3>
            <div class="card col-lg-6 col-sm-8" style="margin-top: 15px; padding-top: 20px; padding-bottom: 10px">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('confirmEditStockType', $stype->id_Stock_type) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-row">
                            <label style="text-align: left; font-weight: bold" for="inputName" class=" col-4">Type Name: *</label>
                            <input style=" margin-left: 20px; margin-bottom: 10px" type="text" class="form-control col-7" id="inputName" required name="Type_name" value="{{$stype->Type_name}}">
                        </div>
                        <div class="form-row">
                            <label style=" text-align: left; font-weight: bold" for="inputDescription" class=" col-4">Description:</label>
                            <input style=" margin-left: 20px; margin-bottom: 10px" type="text" class="form-control col-7" id="inputDescription" name="Type_description" value="{{$stype->Type_description}}">
                        </div>
                        <p class="requiredfield">* Required fields</p>
                        <div style="padding-left: 15px; margin-right: 5%">
                            <button style="float: right" type="submit" class="btn btn-primary">Save Type</button>
                        </div>
                    </form>
        </div>
    @endif
@endsection

<style>
    .requiredfield {
        text-align: left;
        float: left;
        margin-left: 15px;
    }
</style>
