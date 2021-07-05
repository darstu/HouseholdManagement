<link rel="stylesheet" type="text/css" href="https://unpkg.com/xzoom/dist/xzoom.css" media="all" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/xzoom/dist/xzoom.min.js"></script>
{{--<link rel="stylesheet" href=" https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">--}}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<style>

    .no-sort::after { display: none!important; }
    .no-sort::before { display: none!important; }

    .no-sort {
        pointer-events: none !important;
        cursor: default !important;
        padding-right: 15px;!important;
    }
    table.dataTable>thead .sorting_asc:after{
        align-self: center;
    }
    form .form-control:focus{
        border-color: #0d6efd;
        box-shadow: none;
    }

    .dropbtn {
        background-color: lightseagreen;
        /*color: white;*/
        /*padding: 16px;*/
        /*font-size: 16px;*/
        border: none;
        cursor: pointer;
    }

    /*.dropbtn:hover, .dropbtn:focus {*/
    /*    background-color: #3e8e41;*/
    /*}*/

    #myInput {
          box-sizing: border-box;
          /*background-image: url('searchicon.png');*/
          background-position: 14px 12px;
          background-repeat: no-repeat;
          font-size: 16px;
          padding: 14px 20px 12px 45px;
          border: none;
          border-bottom: 1px solid #ddd;
      }
    #myInput2 {
        box-sizing: border-box;
        /*background-image: url('searchicon.png');*/
        background-position: 14px 12px;
        background-repeat: no-repeat;
        font-size: 16px;
        padding: 14px 20px 12px 45px;
        border: none;
        border-bottom: 1px solid #ddd;
    }

    #myInput:focus {outline: 3px solid #ddd;}
    #myInput2:focus {outline: 3px solid #ddd;}

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f6f6f6;
        min-width: 230px;
        overflow: auto;
        border: 1px solid #ddd;
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown a:hover {background-color: #ddd;}

    .show {display: block;}



</style>
<script>
    $(document).ready(function () {
        $('#dtBasicExample').DataTable({
            "ordering": false // false to disable sorting (or any other option)
        });
        $('.dataTables_length').addClass('bs-select');
    });

</script>

@extends('householdActionMenu')
@section('Content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

{{--    @php--}}
{{--       if(Auth::user()->id!='all')--}}
{{--    $user= Auth::user()->id;--}}
{{--    @endphp--}}
{{--    {{$user}}--}}
    <div class="container-fluid"  style="padding-bottom: 50px">
        <div class="row  mt-2">
            <div class="col" style="max-width: 10px">
                <a style="position: center" href="{{URL::previous()}}">
                    <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="container-fluid" style="padding-bottom: 20px; padding-left: 0">
            <div class="row mt-4">
                <div class="col-sm-6" id="pavadinimas">
                    <h2 class="page-title">{{$title}}</h2>
                </div>
                <div class="col-sm-2" id="mygtukai">
                    <div class="btn-group float-right mt-2" role="group">
                        @if($title!='Personal purchase offer')
                        <a class="btn btn-secondary " href="{{route('insertToPurchaseOffer', ['Id' => $house->id_Home,'user'=> 'all'])}}">
                            <i class="fa fa-plus" aria-hidden="true"></i> <span class="d-none d-lg-block">Add item</span></a>
                            @else
                            <a class="btn btn-secondary " href="{{route('insertToPurchaseOffer', ['Id' => $house->id_Home,'user'=> Auth::user()->id])}}">
                                <i class="fa fa-plus" aria-hidden="true"></i> <span class="d-none d-lg-block">Add item</span></a>
                            @endif
                    </div>
                </div>
                <div class="col-sm-2" id="mygtukai">
                    <div class="btn-group float-right mt-2" role="group">
                        <a class="btn btn-secondary " onclick="return confirm('Are you sure you want to clear household purchase offer?');" href="{{ route('clearPurchaseOffer', ['Id' => $house->id_Home]) }}">
                            <i class="fas fa-trash"></i><span class="d-none d-lg-block">Clear offer</span></a>
                    </div>
                </div>
                <div class="col-sm-2" id="mygtukai">
                    <div class="btn-group float-right mt-2" role="group">
                        @if($user=='all')
                        <a class="btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen" href="{{route('purchaseOffer', ['Id' => $house->id_Home, 'user'=> Auth::user()->id])}}">
                            <i class="fas fa-house-user"></i><span class="d-none d-lg-block"> Personal offer</span></a>
                            @else
                            <a class="btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen" href="{{route('purchaseOffer', ['Id' => $house->id_Home, 'user'=> 'all'])}}">
                                <i class="fas fa-home"></i><span class="d-none d-lg-block"> Household offer</span></a>
                        @endif
                    </div>
                </div>

            </div>
            <div class="row mt-4">
                <div class="dropdown col-sm-3 col-md-2" style="padding-top: 2px">
                    @if($supplier!=' ')
                        <button onclick="myFunction()" class="dropbtn btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen">Filtered by: {{$supplier->Name}}</button>
                       @else
                    <button onclick="myFunction()" class="dropbtn btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen">Filter by supplier</button>
                    @endif
                    <div id="myDropdown" class="dropdown-content">
                        <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                        @if($type!=' ')
                            <a href="{{ route('filteredPurchaseOffer', ['Id' => $house->id_Home,'user'=>$user,'supplier' => ' ', 'type'=>$type->id_Stock_type]) }}">
                                All</a>
                        @else
                            <a href="{{ route('purchaseOffer', ['Id' => $house->id_Home, 'user'=>$user])}}">
                                All</a>
                        @endif

                        @foreach($suppliers as $st)
                            @if($type!=' ')
                            <a href="{{ route('filteredPurchaseOffer', ['Id' => $house->id_Home,'user'=>$user,'supplier' => $st->fk_supplier, 'type'=>$type->id_Stock_type]) }}">
                                {{$st->Supplier->Name}}</a>
                            @else
                                <a href="{{ route('filteredPurchaseOffer', ['Id' => $house->id_Home,'user'=>$user,'supplier' => $st->fk_supplier, 'type'=>' ']) }}">
                                    {{$st->Supplier->Name}}</a>
                            @endif
                                @endforeach
                    </div>
                </div>

                <div class="dropdown col-sm-3 col-md-2" style="padding-top: 2px">
                    @if($type!=' ')
                        <button onclick="myFunction2()" class="dropbtn btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen"><span id="filtertext" style="overflow: hidden">Filtered by: {{$type->Type_name}}</span></button>
                    @else
                        <button onclick="myFunction2()" class="dropbtn btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen">Filter by type</button>
                    @endif
                    <div id="myDropdown2" class="dropdown-content">
                        <input type="text" placeholder="Search.." id="myInput2" onkeyup="filterFunction2()">
                        @if($supplier!=' ')
                            <a href="{{ route('filteredPurchaseOffer', ['Id' => $house->id_Home,'user'=>$user,'supplier' => $supplier->supplier_id, 'type'=>' ']) }}">
                                All</a>
                        @else
                            <a href="{{ route('purchaseOffer', ['Id' => $house->id_Home, 'user'=>$user])}}">
                                All</a>
                        @endif

                        @foreach($stockTypes as $st)
                            @if($supplier!=' ')
                            <a href="{{ route('filteredPurchaseOffer', ['Id' => $house->id_Home,'user'=>$user,'supplier' => $supplier->supplier_id, 'type'=>$st->id_Stock_type]) }}">
                                {{$st->Type_name}}</a>
                            @else
                                <a href="{{ route('filteredPurchaseOffer', ['Id' => $house->id_Home,'user'=>$user,'supplier' => ' ', 'type'=>$st->id_Stock_type]) }}">
                                    {{$st->Type_name}}</a>
                                @endif
                        @endforeach
                    </div>
                </div>


{{--                <div class="dropdown col-3 col-md-2">--}}
{{--                    <button onclick="myFunction()" class="dropbtn btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen">Filter by type</button>--}}
{{--                    <div id="myDropdown" class="dropdown-content">--}}
{{--                        <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">--}}
{{--                        <a href="{{ route('purchaseOffer', ['Id' => $house->id_Home, 'user'=>'all'])}}">--}}
{{--                            All</a>--}}
{{--                        @foreach($suppliers as $st)--}}
{{--                            <a href="{{ route('filteredPurchaseOffer', ['Id' => $house->id_Home,'filter_id' => $st->Supplier->supplier_id]) }}">--}}
{{--                                {{$st->Supplier->Name}}</a>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>

        <div class="container-fluid">
            <div class="row mt-2">
                <div class=" col-lg-10 col-xl-9 offset-lg-1" style="overflow-x: auto; padding-top: 10px; margin-left: -15px">
                    <table class="table table-hover table-condensed" id="sortTable">
                        <thead>
                        <tr style="border-bottom: 0px">
                            <th style="border-bottom: 10px;">Resource</th>
                            <th style="border-bottom: 10px;">Quantity</th>
                            <th class="no-sort" style="border-bottom: 10px;"></th>
                            <th class="no-sort" style="border-bottom: 10px;"></th>
                            <th class="no-sort" style="border-bottom: 10px;">Added to personal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $pl)
                            <tr>
                                <td> {{$pl->Stock_Card->Name}}</td>
                                <td> {{$pl->total_quantity}} {{$pl->Stock_Card->measurement_unit}}</td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter-{{$pl->offer_id}}">
                                        More
                                    </button>
                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModalCenter-{{$pl->offer_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">{{$pl->Stock_Card->Name}}</h5>
                                                    <button id="closeModal" type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div style="overflow-x: auto">
                                                <div class="modal-body">
                                                    <table class="table table-hover table-condensed" id="irsortTable-{{$pl->offer_id}}">
{{--                                                        id="irsortTable-{{$pl->offer_id}}--}}
                                                        <thead style="border-top: 0px;">
                                                        <tr style="border-bottom: 0px;">
                                                            <th class="no-sort" style="border-bottom: 10px;">Quantity</th>
                                                            <th class="no-sort" style="border-bottom: 10px;">Warehouse place</th>
{{--                                                            <th style="width:10%;border-bottom: 10px;" class="no-sort"></th>--}}
                                                            <th class="no-sort" style="border-bottom: 10px;">Min q.</th>
                                                            <th class="no-sort" style="border-bottom: 10px;">Max q.</th>
                                                            <th class="no-sort" style="border-bottom: 10px;">Existing q.</th>
                                                            <th class="no-sort" style="border-bottom: 10px;"></th>
                                                            <th class="no-sort" style="border-bottom: 10px;">Purchased</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
{{--                                                        <form style="margin-bottom:0" role="form" method="POST" action="#" enctype="multipart/form-data">--}}
{{--                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                                                        @foreach($allitems as $l)
                                                            @if($pl->fk_stock_card_id==$l->fk_stock_card_id)
                                                                <tr>
{{--                                                                    <td>{{$l->amount}}--}}
                                                                    <td style="vertical-align: middle; ">
                                                                    <form style="margin-bottom:0" role="form"  method="POST" action="{{route('itemedit',['Id' => $house->id_Home,
                                                                    'id' => $l->total_quantity_in_place, 'card'=> $l->fk_stock_card_id, 'place'=>$l->fk_Warehouse_place])}}" enctype="multipart/form-data">
                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                                                        <div class="row ">
                                                                            <div class=" col-sm-11 col-md-11 col-lg-10 col-xl-7" style="vertical-align: middle; padding-right: 0px" >
                                                                             <div class="input-group">

                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text" style=" padding: 0; padding-right: 3px; background:none; border:none" id="">{{$l->Stock_card->measurement_unit}}</span>
                                                                                    </div>
                                                                                    <input style=" text-align: center; outline: none; padding-left:2px; padding:0; padding-right:2px; border: none" type="number" value= "{{$l->total_quantity_in_place}}"
                                                                                           name="number" min="0.01" step="0.01" class="no-border form-control" id="number-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3"style="padding-left: 5px;">
                                                                        <button type="submit" class="btn btn-secondary ">Save</button></div>
                                                                        </div>
                                                                    </form>
                                                                    </td>

                                                                    @foreach($visos as $placepath)
                                                                        @if($placepath->place==$l->fk_Warehouse_place)
                                                                            <td style="vertical-align: middle" data-toggle="tooltip" title="{{$placepath->path}}">{{$l->Warehouse_Place->Warehouse_name}}
                                                                            </td>
                                                                        @endif
                                                                        @endforeach

                                                                    <td style="vertical-align: middle">
                                                                       @if ($l->min_amount>0){{$l->min_amount}}
                                                                           @else 0
                                                                        @endif
                                                                    </td>
                                                                    <td style="vertical-align: middle">
                                                                        @if ($l->max_amount>0){{$l->max_amount}}
                                                                        @else 0
                                                                        @endif
                                                                    </td>
                                                                    <td  style="vertical-align: middle">
                                                                        {{$l->existing_amount}}
                                                                            </td>
                                                                    <td style="vertical-align: middle; padding-left: 5px; padding-right: 5px">
                                                                        <a href="#" class="btn btn-primary" tabindex="0" data-toggle="popover1-{{$l->offer_id}}"  data-popover-content="#a1-{{$l->offer_id}}" data-placement="left">More</a>
                                                                        <div id="a1-{{$l->offer_id}}" class="hidden" style="display: none">
                                                                            <div class="popover-heading">{{$l->Stock_Card->Name}} in {{$l->Warehouse_Place->Warehouse_name}}
{{--                                                                                <span style="float:right;cursor:pointer;" class="fa fa-times" data-toggle="popover1"></span>--}}
                                                                            </div>
                                                                            <div class="popover-body">
                                                                                <table class="table table-hover table-condensed"  style="width:100%">
                                                                                    <tr>
                                                                                    <th class="" style="border-bottom: 10px;">Quantity</th>
                                                                                    <th style="border-bottom: 10px;">Reason</th>
                                                                                    <th style="width:20%;border-bottom: 10px;">Date:</th>
                                                                                    </tr>
                                                                                    @foreach($allitemsDetailed as $detailedItem)
                                                                                        @if($detailedItem->fk_stock_card_id==$l->fk_stock_card_id && $detailedItem->fk_Warehouse_place==$l->fk_Warehouse_place )
                                                                                       <tr>
                                                                                            <td style="vertical-align: middle">
                                                                                               {{$detailedItem->amount}}  {{$detailedItem->Stock_Card->measurement_unit}}
                                                                                            </td>

                                                                                                @if($detailedItem->byQuantity==0)
                                                                                               <td style="vertical-align: middle">
                                                                                                    Expires
                                                                                               </td>
                                                                                                    @elseif($detailedItem->byQuantity==1)
                                                                                               <td style="vertical-align: middle">
                                                                                                    Calculated
                                                                                               </td>
                                                                                                    @elseif($detailedItem->byQuantity==3)
                                                                                               <td style="vertical-align: middle" title="By: {{$detailedItem->AddedBy->name}}">
                                                                                                    Manually altered
                                                                                               </td>
                                                                                             @elseif($detailedItem->byQuantity==4)
                                                                                               <td style="vertical-align: middle" title="By: {{$detailedItem->AddedBy->name}}">
                                                                                                   Purchased
                                                                                               </td>
                                                                                                    @else
                                                                                               <td style="vertical-align: middle">
                                                                                                   From recipe
                                                                                               </td>
                                                                                                @endif
                                                                                           <td style="vertical-align: middle">
                                                                                               {{$detailedItem->date}}
                                                                                           </td>

                                                                                        </tr>
                                                                                        @endif
                                                                                            @endforeach
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td  class="text-center"style="vertical-align: middle">
                                                                        @if($l->buyer==Auth::user()->id || $l->buyer==null || $l->buyer==0)
                                                                        <button id="buy-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}" style="padding-top: 5px" class="btn btn-primary"><i style="color: white"class="fa fa-plus" aria-hidden="true"></i></button>
                                                                   @else
                                                                            <button disabled id="buy-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}" style="padding-top: 5px" class="btn btn-primary"><i style="color: white"class="fa fa-plus" aria-hidden="true"></i></button>
                                                                        @endif
                                                                    </td>
                                                                </tr>

                                                                <tr  id="tobuy-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}" style="display: none">
{{--                                                                    {{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}--}}
                                                                    <form class="contactForm-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}" id="contactForm-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}" action="" name="contactForm">
                                                                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
{{--                                                                    <td></td>--}}
{{--                                                                    <td></td>--}}
{{--                                                                    <td></td>--}}

                                                                    <td colspan="2">
                                                                        <label for="batch">Batch no.</label>
                                                                        <input type="text" name="batch1" class="form-control" id="batch1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}" placeholder="Insert..">
                                                                    </td>
                                                                    <td colspan="2" style="max-width: 170px">
                                                                        <label for="expiration">Ex. date</label>
                                                                        <input style="" type="date" name="expiration1"  class="form-control" id="expiration1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}" >
                                                                    </td>
                                                                    <td colspan="2">
                                                                        <label for="quantity">Quantity ({{$l->Stock_Card->measurement_unit}})</label>
                                                                        <input type="number" step="0.01" name="quantity1" min="0.01" class="form-control" id="quantity1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}" placeholder="0.01">
                                                                    </td>
                                                                    <td style="vertical-align: bottom">
                                                                        <button type="submit" class="btn btn-secondary ">Add</button>
{{--                                                                        <input type="submit" value="Submit">--}}
{{--                                                                        <input id="tag-form-submit" type="submit" class="btn btn-primary" value="Add Tag">--}}

                                                                    </td>
                                                                    </form>
                                                                </tr>
{{--                                                                <div id='response'></div>--}}
                                                                <script>
                                                                       </script>
                                                                <script type='text/javascript'>
    // $(function() {

    $(".contactForm-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}").on("submit", function (e) {
        window.your_route = "{{ route('addResourcePP',['Id'=>$house->id_Home, 'resource_id'=>$l->fk_stock_card_id, 'place'=>$l->fk_Warehouse_place]) }}";
        var dataString = $(this).serialize();
        var batch1 = $('#batch1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}').val();
                var expiration1 = $('#expiration1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}').val();
                var quantity1 = $('#quantity1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            url:window.your_route,
            dataType: "json",
            // data: dataString,
            data: {
                "_token": "{{ csrf_token() }}",
                batch1: batch1,
                expiration1: expiration1,
                quantity1: quantity1,
            },
            {{--data:'_token = <?php echo csrf_token() ?>',--}}
            success: function (response) {
                alert(response.message);
                // alert(response.data);
                // Display message back to the user here
                var len = 0;
                if(response['data'] != null){
                    len = response['data'].length;
                    // alert(len);
                }
                if(len > 0){
                    // Read data and create <option >
                    for(var i=0; i<len; i++){

                        var id_Stock_batch = response['data'][i].total_quantity;
                        // alert(id_Stock_batch);
                        var quan = "<input style=\" text-align: center; outline: none; padding:0; padding-right:2px; border: none\" class=\"no-border form-control\" " +
                            "type=\"number\" name=\"number\" min=\"0\" step=\"0.01\" id=\"number\" value='"+id_Stock_batch.toFixed(2)+"'</input>";
                        $("#number-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}").replaceWith(quan);

                    }
                }
                if(response.zero!=null){
                    var quan = "<input style=\" text-align: center; outline: none; padding:0; padding-left:2px; padding-right:2px; border: none\" class=\"no-border form-control\" " +
                        "type=\"number\" name=\"number\" min=\"0\" step=\"0.01\" id=\"number\" value='"+0+"'</input>";
                    $("#number-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}").replaceWith(quan);
                }
                document.getElementById("contactForm-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}").reset();
            },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
            }
        });

        e.preventDefault();

    });


    // $('#closeModal').on('click',function() {
    //     window.location.reload();
    // });

    $(document).ready(function () {
        $("#exampleModalCenter-{{$pl->offer_id}}").on('hide.bs.modal', function () {
            window.location.reload();
        });
    });

    $(document).ready(function () {
        // Department Change
        $('#batch1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}').change(function () {
            // Department id
            var b = $('#batch1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}').val();
            // alert(b);

            var house = "{!! addcslashes($house->id_Home, '"') !!}";
            var resource = "{!! addcslashes($l->fk_stock_card_id, '"') !!}";

            // alert(house,b);
            // Empty the dropdown
            $('#expiration1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}').find('input').remove();
            $('#expiration1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}').val('');
            $("#expiration1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}").prop("readonly", false);

            // AJAX request
            $.ajax({
                url: '../../checkBatch/' + house + '/' + resource + '/' + b,
                type: "get",
                dataType: "json",
                success: function (response) {

                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }
                    // alert(len);
                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {

                            var expiration = response['data'][i].expiration_date;
                            var b = response['data'][i].fk_Batch;
                            // alert(expiration);

                            // var number = response['data'][i].number;

                            // var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";

                            // $("#subCategory").append(option);
                            $("#expiration1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}").val(expiration);
                            // alert(expiration);
                            $("#expiration1-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}").prop("readonly", true);
                        }
                    }

                }
            });
        });

    });

</script>
{{--                                                                {{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}--}}

                                                            <script>
                                                                $("#buy-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}").click(function(){
                                                                    $("#tobuy-{{$l->fk_stock_card_id}}{{$l->fk_Warehouse_place}}").toggle();
                                                                });
                                                            </script>
                                                            @endif
                                                            <script>
                                                                $(function() {
                                                                    $("[data-toggle=popover1-{{$l->offer_id}}]").popover({
                                                                        html: true,
                                                                        content: function() {
                                                                            var content = $(this).attr("data-popover-content");
                                                                            return $(content).children(".popover-body").html();
                                                                        },
                                                                        title: function() {
                                                                            var title = $(this).attr("data-popover-content");
                                                                            return $(title).children(".popover-heading").html();
                                                                        }
                                                                    });
                                                                    $(".btn-primary .bu").click(function(){
                                                                        $("[data-toggle=popover1]").popover('hide');
                                                                    });
                                                                });

                                                            </script>

                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                     </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                                <td>

                                    {{--                                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="">--}}
                                    {{--                                                                        </button>--}}
                                    <button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-trigger="focus" data-placement="right"
                                            data-content="
                                                @foreach($suppliersAll as $sup)
                                            @if($pl->fk_stock_card_id==$sup->fk_stock_card)
                                            {{$sup->Supplier->Name}} <br>
                                            @endif
                                            @endforeach">
                                        Suppliers
                                    </button>

                                </td>
                                <td>
                                    @if($pl->want_to_buy>0)
{{--                                        <i data-toggle="tooltip" data-placement="right" title="{{$pl->Buyer->name}}"--}}
{{--                                           style="color: #38c172" class="fas fa-check"></i>--}}
                                        <form role="form" method="POST" action="{{route('removeFromOwn',
                                   ['Id' => $house->id_Home, 'item'=> $pl->fk_stock_card_id, 'user' =>Auth::user()->id])}}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                          <button  data-toggle="tooltip" data-placement="right" title="{{$pl->Buyer->name}}"
                                              type="submit" style=" border:0; background-color: transparent" class="btn btn-secondary ">
                                                <i style="color: #38c172" class="fas fa-check"></i></button>
                                        </form>

                                            @else

                                   <form role="form" method="POST" action="{{route('insertToOwn',
                                   ['Id' => $house->id_Home, 'item'=> $pl->fk_stock_card_id, 'user' =>Auth::user()->id])}}">
                                       <input type="hidden" name="_token" value="{{ csrf_token() }}">
{{--                                    <a class="btn btn-secondary" style="background-color: transparent" method="POST" href="">--}}
                                       <button type="submit" style="background-color: transparent" class="btn btn-secondary ">
                                   <i style="color: #6c757d" class="fas fa-check"></i></button>
                                   </form>
                                        @endif
                                </td>
                            </tr>
{{--                            @php--}}
{{--                                $qcount=$qcount+$pl->total_quantity;--}}
{{--                            @endphp--}}
                            <script>
                                $('#irsortTable-{{$pl->offer_id}}').DataTable({
                                    "aaSorting": [],
                                    "order": []
                                });
                            </script>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Content for Popover #1 -->


    <script>
        $('#sortTable').DataTable();

    </script>


@endsection

<script>

    $(function () {
        $('[data-toggle="popover"]').popover({html: true})
    })

    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })

    function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
    }

    function filterFunction() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdown");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }
    }
    function myFunction2() {
        document.getElementById("myDropdown2").classList.toggle("show");
    }

    function filterFunction2() {
        var input, filter, ul, li, a, i;
        input = document.getElementById("myInput2");
        filter = input.value.toUpperCase();
        div = document.getElementById("myDropdow2n");
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            txtValue = a[i].textContent || a[i].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }
    }


</script>

{{--<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>--}}
{{--<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
