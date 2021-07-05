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

    #myInput:focus {outline: 3px solid #ddd;}

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


        <div class="container-fluid" style="padding-bottom: 20px">
            <div class="row mt-4">
                <div class="col-8">
                    <h1>{{$title}}</h1>
                </div>
                <div class="col-2">
                    <div class="btn-group float-right mt-2" role="group">
                        @if($title!='Personal purchase offer')
                        <a class="btn btn-secondary " href="{{route('insertToPurchaseOffer', ['Id' => $house->id_Home,'user'=> 'all'])}}">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add item</a>
                        {{--                                            <a class="btn btn-md btn-secondary" href="#">--}}
                        {{--                                                <i class="fa fa-flag" aria-hidden="true"></i> Report</a>--}}
                            @else
                            <a class="btn btn-secondary " href="{{route('insertToPurchaseOffer', ['Id' => $house->id_Home,'user'=> Auth::user()->id])}}">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add item</a>
                            @endif
                    </div>
                </div>
                <div class="col-2">
                    <div class="btn-group float-right mt-2" role="group">
{{--                        Auth::user()->id--}}
                        <a class="btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen" href="{{route('purchaseOffer', ['Id' => $house->id_Home, 'user'=> Auth::user()->id])}}">
                            Personal purchase offer</a>
                        {{--                                            <a class="btn btn-md btn-secondary" href="#">--}}
                        {{--                                                <i class="fa fa-flag" aria-hidden="true"></i> Report</a>--}}
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="dropdown col-3 col-md-2">
                    <button onclick="myFunction()" class="dropbtn btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen">Filter by supplier</button>
                    <div id="myDropdown" class="dropdown-content">
                        <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                        <a href="{{ route('purchaseOffer', ['Id' => $house->id_Home, 'user'=>'all'])}}">
                            All</a>
                        @foreach($suppliers as $st)
                            <a href="{{ route('filteredPurchaseOffer', ['Id' => $house->id_Home,'filter_id' => $st->Supplier->supplier_id]) }}">
                                {{$st->Supplier->Name}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="dropdown col-3 col-md-2">
                    <button onclick="myFunction()" class="dropbtn btn btn-secondary" style="background-color: lightseagreen; border-color: lightseagreen">Filter by type</button>
                    <div id="myDropdown" class="dropdown-content">
                        <input type="text" placeholder="Search.." id="myInput" onkeyup="filterFunction()">
                        <a href="{{ route('purchaseOffer', ['Id' => $house->id_Home, 'user'=>'all'])}}">
                            All</a>
                        @foreach($suppliers as $st)
                            <a href="{{ route('filteredPurchaseOffer', ['Id' => $house->id_Home,'filter_id' => $st->Supplier->supplier_id]) }}">
                                {{$st->Supplier->Name}}</a>
                        @endforeach
                    </div>
                </div>
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
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div style="overflow-x: auto">
                                                <div class="modal-body">
                                                    <table class="table table-hover table-condensed" id="irsortTable">
{{--                                                        id="irsortTable-{{$pl->offer_id}}--}}
                                                        <thead style="border-top: 0px;">
                                                        <tr style="border-bottom: 0px;">
                                                            <th class="no-sort" style="width:10%;border-bottom: 10px;">Quantity</th>
                                                            <th style="width:10%;border-bottom: 10px;">Warehouse place</th>
{{--                                                            <th style="width:10%;border-bottom: 10px;" class="no-sort"></th>--}}
                                                            <th style="width:10%;border-bottom: 10px;">Min q.</th>
                                                            <th style="width:10%;border-bottom: 10px;">Max q.</th>
                                                            <th style="width:10%;border-bottom: 10px;">Existing q.</th>
                                                            <th style="width:10%;border-bottom: 10px;"></th>
                                                            <th style="width:10%;border-bottom: 10px;">Purchased</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
{{--                                                        <form style="margin-bottom:0" role="form" method="POST" action="#" enctype="multipart/form-data">--}}
{{--                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">--}}
                                                        @foreach($allitems as $l)
                                                            @if($pl->fk_stock_card_id==$l->fk_stock_card_id)
                                                                <tr>
{{--                                                                    <td>{{$l->amount}}--}}
                                                                    <td style="vertical-align: middle">
                                                                    <form style="margin-bottom:0" role="form" method="POST" action="{{route('itemedit',['Id' => $house->id_Home,
                                                                    'id' => $l->total_quantity_in_place, 'card'=> $l->fk_stock_card_id, 'place'=>$l->fk_Warehouse_place])}}" enctype="multipart/form-data">
                                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                        <div class="row no-gutters">
                                                                            <div class="col-sm-7 " style="vertical-align: middle; " >
                                                                             <div class="input-group">

                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text" style="background:none; border:none" id="">{{$l->Stock_card->measurement_unit}}</span>
                                                                                    </div>
                                                                                    <input style=" text-align: center; outline: none; padding:0; padding-right:2px; border: none" type="number" value= "{{$l->total_quantity_in_place}}"
                                                                                           name="number" min="0" step="0.01" class="no-border form-control" id="number">
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-5 " style="padding-left: 5px">
                                                                        <button type="submit" class="btn btn-secondary ">Save</button></div>
                                                                        </div>
                                                                    </form>
                                                                    </td>

                                                                    <td id="place" style="vertical-align: middle">{{$l->Warehouse_Place->Warehouse_name}}</td>
{{--                                                                    <td style="vertical-align: middle">--}}
{{--                                                                        @if($l->byQuantity==0)--}}
{{--                                                                            Expires--}}
{{--                                                                        @endif--}}
{{--                                                                    </td>--}}
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
{{--                                                                    @php $kazkas=null;--}}
{{--                                                                    @endphp--}}
{{--                                                                    @php--}}
{{--                                                                    $value=0;--}}
{{--                                                                    @endphp--}}
                                                                    <td  style="vertical-align: middle">
                                                                        {{$l->existing_amount}}
{{--                                                                            @foreach($allGroupedStocks as $ags)--}}
{{--                                                                            @if($ags->fk_Stock_card==$l->fk_stock_card_id && $ags->fk_Warehouse_place==$l->fk_Warehouse_place)--}}
{{--                                                                              {{$ags->total_quantity_in_place}}--}}
{{--                                                                           @continue--}}
{{--                                                                        @else 0--}}
{{--                                                                              @break--}}
{{--                                                                            @endif--}}
{{--                                                                            @endforeach--}}
                                                                            </td>
                                                                    <td style="vertical-align: middle;">
                                                                        <a href="#" class="btn btn-primary" tabindex="0" data-toggle="popover1-{{$l->offer_id}}"  data-popover-content="#a1-{{$l->offer_id}}" data-placement="left">More</a>
                                                                        <div id="a1-{{$l->offer_id}}" class="hidden" style="display: none">
                                                                            <div class="popover-heading">{{$l->Stock_Card->Name}} in {{$l->Warehouse_Place->Warehouse_name}}
{{--                                                                                <span style="float:right;cursor:pointer;" class="fa fa-times" data-toggle="popover1"></span>--}}
                                                                            </div>
                                                                            <div class="popover-body">
                                                                                <table class="table table-hover table-condensed"  style="width:100%">
                                                                                    <tr>
                                                                                    <th class="no-sort" style="width:10%;border-bottom: 10px;">Quantity</th>
                                                                                    <th style="width:10%;border-bottom: 10px;">Reason:</th>
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
                                                                                                    @else
                                                                                               <td style="vertical-align: middle" title="By: {{$detailedItem->AddedBy->name}}">
                                                                                                    Manually altered
                                                                                               </td>
                                                                                                @endif

                                                                                        </tr>
                                                                                        @endif
                                                                                            @endforeach
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="vertical-align: middle">
                                                                        <a href="#" style="padding-top: 5px">  <i style="color: lightseagreen"class="fa fa-plus" aria-hidden="true"></i> <span class="d-none d-lg-block"></span></a>

                                                                        <input type="button" class="tdAdd" value="+" />

                                                                    </td>
                                                                </tr>
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
                                                            <button type="submit" class="btn btn-secondary ">Save</button>
                                                        </form>
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
                                                @foreach($suppliers as $sup)
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
                                $('#irsortTable-{{$pl->offer_id}}').DataTable();
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


    $(function () {
        $('[data-toggle="popover"]').popover({html: true})
    })

    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })



    $(document).on("click", '.tdAdd', function () {

       var place = $('#place').text();

        var counter = $('#irsortTable tbody tr').length + 1;
        alert(lenght);
        var newRow = $("<tr>");

        var cols = "";
        cols += '<td></td>';
        cols += '<td></td>';
        cols += '<td></td>';
        cols += '<td></td>';
        cols += '<td style="vertical-align: middle; width: 10%"><input style="vertical-align: middle; width: 50px" type="number" min="0.1" step="0.01" name="purchased_quantity' + counter + '"/>' + counter + '</td>';
        cols += '<td style="vertical-align: middle; width: 10%"><input style="vertical-align: middle; width: 50px" type="text" name="batch_name' + counter + '"/></td>';
        cols += '<td style="vertical-align: middle; width: 10%"><input style="vertical-align: middle; width: 50px" type="date" name="expiration' + counter + '"/></td>';
        newRow.append(cols);
        newRow.insertAfter( $(this).closest("tr") );
    });

    $("table.order-list").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();
    });

    $(document).ready(function() {
        $("#formButton").click(function() {
            $("t").toggle();
        });
    });


</script>
