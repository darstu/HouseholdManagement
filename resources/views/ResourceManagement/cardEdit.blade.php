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
    @if($title == 'Stock card')<?php
    $title = "Stock card";
    ?>
        <a style="position: center" href="{{route('card', ['id_house' => $house->id_Home, 'Id' => $stock->id_Stock_card, 'title' => $title])}}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <h3>Resource card information</h3>
        <div class="row">
        <div class="card marginfix col-lg-5 col-sm-8" style="margin-top: 15px; padding-top: 20px; padding-left: 20px; margin-left: 10px">
            <form class="form-horizontal" role="form" enctype="multipart/form-data" method="POST" action="{{ url('confirmEditCard', [$stock->id_Stock_card, 'title' => $title]) }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <img class="card-img-top" style="width: 200px; height: 200px; margin-bottom: 10px" src="{{ asset('/images') . '/' . $stock->image . '.jpg'}}"  alt="{{$stock->image}}" >
                <div class="form-row">
                    <label style="width: 40%; text-align: left; font-weight: bold" for="inputName">Resource name: *</label>
                    <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="inputName" required name="Name" value="{{$stock->Name}}">
                </div>
                <div class="form-row">
                    <label style="width: 40%; text-align: left; font-weight: bold" for="inputType">Resource type: *</label>
                    <select style="width: 50%; margin-left: 20px; margin-bottom: 10px" id="inputType" class="form-control" required name="fk_Stock_type">
                        <option value="{{$now_type->id_Stock_type}}">{{$now_type->Type_name}}</option>
                        @foreach($stock_types as $type)
                            @if($type->fk_household_id == $house->id_Home)
                                <option value="{{$type->id_Stock_type}}">{{$type->Type_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <label style="width: 40%; text-align: left; font-weight: bold" for="inputDescription">Description:</label>
                    <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="inputDescription" name="Description" value="{{$stock->Description}}">
                </div>
                <div class="form-row">
                    <label style="width: 40%; text-align: left; font-weight: bold" for="measurement_unit">Unit: *</label>
                    <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="measurement_unit" name="measurement_unit" value="{{$stock->measurement_unit}}">
                </div>
                <div class="form-row">
                    <label for="image" style="width: 40%; text-align: left; font-weight: bold">Change a photo</label>
                    <input type="file" class="col-7" id="image" accept="image/jpg" name="image" value="{{$stock->image}}">
                </div>
                <br>
                <p class="requiredfield">* Required fields</p>
                <div style="padding-left: 15px; margin-right: 5%">
                    <button style="float: right" type="submit" class="btn btn-primary">Save resource</button>
                </div>
            </form>
        </div>
            <div class="card col-3" style="padding-left: 30px; margin-top: 15px; margin-left: 15px; text-align: left; height: fit-content; width: 500px">
                <h5>Resource suppliers:</h5>
                @if($sc == 0)
                    <p>This resource has no suppliers</p>
                @else
                    @foreach($supplierbelong as $supplierssb)
                        @foreach($supplier as $supplierss)
                            @if($supplierss->supplier_id == $supplierssb->fk_supplier and $supplierss->removed != 1)
                                <p><a class="btn btn-danger" style="margin-right: 10px" href="{{ route('removeSupplierFromStock', ['fk_stock_card' => $stock->id_Stock_card, 'fk_suppliers' => $supplierssb->fk_supplier]) }}">
                                        Remove</a>{{$supplierss->Name}}</p>
                            @endif
                        @endforeach
                    @endforeach
                @endif
            </div>
            <div class="row">
            <div class=" col-md" style="padding-left: 30px; margin-top: 10px; text-align: left; width: fit-content">
                <form role="form" method="POST" action="{{ url('addSupplierForStock', ['fk_stock_card' => $stock->id_Stock_card]) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-row">
                        <label style="text-align: left; font-weight: bold" for="inputType">Add Supplier:</label>
                        <select style="width: auto; margin-bottom: 15px" id="inputType" class="form-control" name="fk_supplier">
                            <option value="0">----------</option>
                            @foreach($supplier as $supplierss)
                                @foreach($supp_types as $stypes)
                                    @if($supplierss->fk_type_id == $stypes->type_id and $supplierss->removed != 1)
                                        <?php
                                        $c = 0;
                                        ?>
                                        @foreach($supplierbelong as $supplierssb)
                                            @if($supplierss->supplier_id == $supplierssb->fk_supplier)
                                                <?php
                                                $c = $c + 1;
                                                ?>
                                            @endif
                                        @endforeach
                                        @if($c == 0)
                                            <option value="{{$supplierss->supplier_id}}">{{$supplierss->Name}}, {{$stypes->Name}}</option>
                                        @endif
                                    @endif
                                @endforeach
                            @endforeach
                        </select>
                    <div style="float: right">
                        <button style="float: right" type="submit" class="btn btn-secondary">Add</button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
        </div>
    @else
        <?php
        $title = "Warehouse place";
        ?>
        <a style="position: center" href="{{route('card', ['id_house' => $house->id_Home, 'Id' => $ware->id_Warehouse_place, 'title' => $title])}}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
            </svg>
        </a>
        <br>
        <h3>Storage information</h3>
        <div class="card col-sm-8 col-lg-6" style="margin-top: 15px; padding-top: 20px; padding-left: 30px">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('confirmEditCard', [$ware->id_Warehouse_place, 'title' => $title]) }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-row">
                    <label style="width: 40%; text-align: left; font-weight: bold" for="inputName">Storage name: *</label>
                    <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="inputName" required name="Warehouse_name" value="{{$ware->Warehouse_name}}">
                </div>
                <div class="form-row">
                    <label style="width: 40%; text-align: left; font-weight: bold" for="inputType">Storage place:</label>
                    <select style="width: 50%; margin-left: 20px; margin-bottom: 10px" id="inputType" class="form-control" required name="fk_Warehouse_place">
                        @if($now_place == null)
                            <option value="0">{{$house->Name}}</option>
                        @else
                            <option value="{{$now_place->id_Warehouse_place}}">{{$now_place->Warehouse_name}}</option>
                        @endif
                        @foreach($ware_types as $type)
                            <?php
                            $l = 0;
                            ?>
                            @foreach($lockedwarehouse as $locked)
                                @if($locked->warehouse_id == $type->id_Warehouse_place or $locked->warehouse_id == $type->fk_Warehouse_place)
                                    <?php
                                    $l = $l+1;
                                    ?>
                                @endif
                            @endforeach
                            @if($type->id_Warehouse_place != $ware->id_Warehouse_place and $type->removed != 1 and $l == 0)
                                <option value="{{$type->id_Warehouse_place}}">{{$type->Warehouse_name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <label style="width: 40%; text-align: left; font-weight: bold" for="inputDescription">Description:</label>
                    <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="inputDescription" name="Description" value="{{$ware->Description}}">
                </div>
                <div class="form-row">
                    <label style="width: 40%; text-align: left; font-weight: bold" for="inputAddress">Address:</label>
                    <input style="width: 50%; margin-left: 20px; margin-bottom: 10px" type="text" class="form-control" id="inputAddress" name="Address" value="{{$ware->Address}}">
                </div>
                <br>
                <p class="requiredfield">* Required fields</p>
                <div style="padding-left: 15px; margin-right: 5%">
                    <button style="float: right" type="submit" class="btn btn-primary">Save storage</button>
                </div>
            </form>
        </div>
    @endif
@endsection

<style>
    .lineForHouse {
        font-weight: bold;
        text-align: left;
        float: left;
        margin-left: 15px;
    }
    .requiredfield {
        text-align: left;
        float: left;
        margin-left: 15px;
    }

    .marginfix {
        text-align: left;
        margin-bottom: 10px !important;
    }
</style>
