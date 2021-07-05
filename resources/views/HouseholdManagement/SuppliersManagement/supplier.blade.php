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
    <a style="position: center" href="{{action('App\Http\Controllers\HouseholdManagement\SuppliersController@index', ['id_house' => $house->id_Home])}}">
        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
        </svg>
    </a>
    <br>
    <h3>Supplier information</h3><br>
    <div class="row" style="padding-left: 30px">
    <div class="card col-lg-6 col-sm-8" style="margin-bottom: 2%; padding-left: 30px">
        <table class="table table-condensed">
            <tr style="border-bottom: 0px">
                <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">Supplier Name:</th>
                <td>{{$homesupp->Name}}</td>
            </tr>
            <tr>
                <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">Supplier type:</th>
                <td>{{$nowtype->Name}}</td>
            </tr>
            <tr>
                <th style="width:50%;border-bottom: 10px; text-align: left; padding-left: 20px">Address: </th>
                <td>{{$homesupp->Address}}</td>
            </tr>
            <tr>
                <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">City:</th>
                @if($homesupp->City == null)
                    <td>-----</td>
                @else
                    <td>{{$homesupp->City}}</td>
                @endif
            </tr>
            <tr>
                @if($homesupp->Phone == null)
                    <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">Phone number: </th>
                    <td>----</td>
                @else
                    <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">Phone number: </th>
                    <td>{{$homesupp->Phone}}</td>
                @endif
            </tr>
        </table>
    </div>

        @if($permission_edit->restricted == 1)
        @else
            <div>
                <button style="float: left; margin-left: 10px" class="btn btn-primary"> <a href='{{route('SupplierEdit', ['id_house' => $house->id_Home, 'Id' => $homesupp->supplier_id])}}' style="color: white" >Edit Supplier</a></button>
            </div>
        @endif
        @if($permission_delete->restricted == 1)
        @else
            <div class="col-md">
                <button class="btn btn-danger" style="margin-left: 10px; float: right" type="delete"><a onclick="return confirm('Do you really want to deactivate this supplier?')" href="{{route('removeSupplier', ['Id' => $homesupp->supplier_id])}}" style="color: white" >Deactivate Supplier</a></button>
            </div>
        @endif
    </div>
@endsection
