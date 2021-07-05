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
    <br>
    @if($title == 'Stock card')
        <a style="position: center" href="{{route('stockCards', ['id_house' => $house->id_Home])}}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <br>
        <div class="row col-12">
            <div class="col-lg-5 col-sm-12">
                <div class="card" style="margin-bottom: 2%">
                    <img class="card-img-top" style="margin: 15px auto 15px auto; width: 350px; height: 350px;" src="{{ asset('/images') . '/' . $stock->image . '.jpg'}}"  alt="{{$stock->image}}" >
                    <h2>{{$stock->Name}}</h2>
                    <table class="table table-condensed">
                        <tr>
                            @if($stock->Description == null)
                                <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">Description:</th>
                                <td>----</td>
                            @else
                                <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">Description:</th>
                                <td>{{ $stock->Description }}</td>
                            @endif
                        </tr>
                        <tr>
                            <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">Type:</th>
                            @foreach($stoc_t as $st)
                                @if($stock->fk_Stock_type == $st->id_Stock_type)
                                    <td>{{ $st->Type_name }}</td>
                                @endif
                            @endforeach
                        </tr>
                        <tr>
                            <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">Unit:</th>
                            <td>{{ $stock->measurement_unit }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card col-2" style="padding-left: 30px; padding-right: 20px; text-align: left; width: fit-content; height: fit-content">
                <h5>Resource suppliers:</h5>
                @if($sc == 0)
                    <p>This resource has no suppliers</p>
                @else
                    @foreach($supplierbelong as $supplierssb)
                        @foreach($supplier as $supplierss)
                            @if($supplierss->supplier_id == $supplierssb->fk_supplier)
                                <p>{{$supplierss->Name}}</p>
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </div>
            @if($permission_edit->restricted == 1)
            @else
                <div class="col-2">
                    <button style="margin-left: 10px;" class="btn btn-primary"> <a href='{{route('CardEdit', ['id_house' => $house->id_Home, 'Id' => $stock->id_Stock_card, 'title' => $title])}}' style="color: white" >Edit resource</a></button>
                </div>
            @endif
            @if($permission_delete->restricted == 1)
            @else
                <div class="col-md">
                    <button style="margin-left: 10px; float: right" class="btn btn-danger" type="delete"><a onclick="return confirm('Do you really want to deactivate this resource card?')" href="{{route('removeStockCard', ['Id' => $stock->id_Stock_card])}}" style="color: white" >Deactivate resource</a></button>
                </div>
            @endif
        </div>
    @else
        <a style="position: center" href="{{route('warehousePlaces', ['id_house' => $house->id_Home])}}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <div class="row col-12">
            <div class="card col-5" style="margin-bottom: 2%">
                <h2>{{$ware->Warehouse_name}}</h2>
                <table class="table table-condensed">
                    <tr>
                        <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">Description:</th>
                        @if($ware->Description == null)
                            <td>----</td>
                        @else
                            <td>{{ $ware->Description }}</td>
                        @endif
                    </tr>
                    <tr>
                        <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 20px">Address:</th>
                        @if($ware->Address == null)
                            <td>--------</td>
                        @else
                            <td>{{ $ware->Address }}</td>
                        @endif
                    </tr>
                    <tr>
                        <th style="width:50%;border-bottom: 10px; text-align: left; padding-left: 20px">Where it's placed:</th>
                        @if($ware->fk_Warehouse_place == null)
                            <td>{{ $house->Name }}</td>
                        @else
                            <td>{{ $warehouses->Warehouse_name }}</td>
                        @endif
                    </tr>
                </table>
            </div>
            @if($permission_edit->restricted == 1)
            @else
                <div>
                    <button style="margin-left: 10px; float: left" class="btn btn-primary"> <a href='{{route('CardEdit', ['id_house' => $house->id_Home, 'Id' => $ware->id_Warehouse_place, 'title' => $title])}}' style="color: white" >Edit storage</a></button>
                </div>
            @endif
            @if($permission_delete->restricted == 1)
            @else
                <div class="col-md" >
                    <button class="btn btn-danger" style="float: right" type="delete"><a onclick="return confirm('Do you really want to deactivate this storage place?')" href="{{route('removeWarCard', ['Id' => $ware->id_Warehouse_place])}}" style="color: white" >Deactivate storage</a></button>
                </div>
            @endif
        </div>
        <h3 style="margin-left: 10px">Storage places in {{$ware->Warehouse_name}}</h3>
        @if($countstorageplaces == 0)
            <p style="margin-left: 10px">{{$ware->Warehouse_name}} has no storage places inside.</p>
        @else
        <div class="col-4">
            <table class="table" style="border: none">
                <thead>
                <tr style="border: none">
                    <th style="width: 30%; border: none">Storage</th>
                    <th style="width: 70%; ; border: none">Unit inside</th>
                </tr>
                </thead>
                <tbody>
                @foreach($storage as $stor)
                    <tr class="trhover" onClick="location.href='{{route('card', ['id_house' => $house->id_Home, 'Id' => $stor->id_Warehouse_place, 'title' => $title])}}'">
                        <td style="width: 30%">{{$stor->Warehouse_name}}</td>
                        <?php
                        $units = 0;
                        ?>
                        <td style="width: 100%;">
                        @foreach($allwares as $newware)
                                @if($newware->fk_Warehouse_place == $stor->id_Warehouse_place)

                                {{$newware->Warehouse_name}};

                                    <?php
                                    $units = $units + 1;
                                    ?>
                                @endif
                        @endforeach
                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            @endif
    @endif
@endsection

<style>
    .trhover:hover
    {
        text-decoration: underline;
        cursor: pointer;
        font-weight: bold;
        background-color: #ebebee;
    }
</style>

