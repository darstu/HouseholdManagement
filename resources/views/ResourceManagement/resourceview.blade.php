<link rel="stylesheet" type="text/css" href="https://unpkg.com/xzoom/dist/xzoom.css" media="all" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/xzoom/dist/xzoom.min.js"></script>
{{--<link rel="stylesheet" href=" https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">--}}


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<style>

    /*.no-sort::after { display: none!important; }*/
    /*.no-sort::before { display: none!important; }*/

    /*.no-sort {*/
    /*    pointer-events: none !important;*/
    /*    cursor: default !important;*/
    /*}*/
    /*.table thead th {*/
    /*    border-bottom: 0!important;*/
    /*}*/


    /*table.dataTable>thead>tr>th:not(.sorting_disabled), table.dataTable>thead>tr>td:not(.sorting_disabled) {*/
    /*    padding-right: 25px!important;*/
    /*}*/
    .modal.fade{
       padding-right: 0!important;
    }


    modal-body {
        max-width: 100%;
        overflow-x: auto;
    }
    .scoll-tree {
        width:5000px;
    }

</style>
<script>
    $(document).ready(function () {
        $('#dtBasicExample').DataTable({
            "ordering": false // false to disable sorting (or any other option)
        });
        $('.dataTables_length').addClass('bs-select');
    });
</script>

{{--@extends('app')--}}
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

 @php
     $url=Request()->fullUrl();
     $urlYes=0;
 if(strpos($url, 'All'))
 $urlYes=1;
 @endphp

    <div class="container-fluid"  style="padding-bottom: 50px;">
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
                <div class="col-6" style="padding-left: 0">
                    <h2 class="page-title" >{{$title->Name}}</h2>
                </div>
                    <div class="col-2">
                        <div class="btn-group float-right mt-2" role="group">
                            <a class="btn btn-primary" href="{{ route('addResourceFromResourceView', ['Id' => $house->id_Home, 'id_resource'=>$title->id_Stock_card]) }}">
                                <i class="fa fa-plus" aria-hidden="true"></i> <span class="d-none d-lg-block">Add new</span></a>
                        </div>
                    </div>
                <div class="col-2">
                    <div class="btn-group float-right mt-2" role="group">
                        <a class="btn btn-primary " href="{{ route('moveResource', ['Id' => $house->id_Home, 'id_resource' =>$title->id_Stock_card]) }}">
                            <i class="fas fa-exchange-alt" aria-hidden="true"></i><span class="d-none d-lg-block">Move resource</span></a>
                    </div>
                </div>
                <div class="col-2">
                    <div class="btn-group float-right mt-2" role="group">
                        <a class="btn btn-primary " href="{{ route('deleteResource', ['Id' => $house->id_Home, 'id_resource' =>$title->id_Stock_card]) }}">
                            <i class="fas fa-minus" aria-hidden="true"></i><span class="d-none d-lg-block">Reduce resource</span></a>
                        {{--                                            <a class="btn btn-md btn-secondary" href="#">--}}
                        {{--                                                <i class="fa fa-flag" aria-hidden="true"></i> Report</a>--}}
                    </div>
                </div>
            </div>
        </div>

{{--        <div class="container-fluid">--}}
            <div class="row">
                <div class="col-lg-4">
                    <img class="img-fluid" src="{{ asset('/images') . '/' . $title->image . '.jpg'}}"  alt="stock image {{$title->image}}" >

{{--                    @foreach($items as $resource)--}}

{{--                    @endforeach--}}
                </div>
                <div class="col-lg-6 product_description">
                        <div class="product_category"><p>Category:  {{$title->Stock_Type->Type_name}}</p></div>
{{--                        <div class="product_name">{{$item->pavadinimas}}</div>--}}
                        {{-- <div class="rating_r rating_r_4 product_rating"><i></i><i></i><i></i><i></i><i></i></div>--}}
                        <div class="product_text"><p>Description: {{$title->Description}}</p></div>
                        <div class="product_measure ">
                            <p><span>Measurement unit:</span> {{$title->measurement_unit}}</p>
{{--                            <p><span id="info1">Diameter:</span> {{$item->diametras}}mm</p>--}}
{{--                            @if($item->galiuko_aukstis)--}}
{{--                                <p><span id="info1">Tip height:</span> {{$item->galiuko_aukstis}}cm</p>--}}
{{--                            @endif--}}
                        </div>
                </div>
            </div>

{{--        SITO NEREIKIAAAAAA--}}
{{--            <ul>--}}
{{--                @foreach ($categories as $category)--}}
{{--                    <li>{{ $category->Warehouse_name }} {{$category->total_quantity}}</li>--}}
{{--                {{$category}}--}}
{{--                    <ul>--}}
{{--                        @foreach ($category->Warehouse_Place2 as $childCategory)--}}
{{--                            @include('ResourceManagement/child_category2', ['child_category' => $childCategory])--}}
{{--                        @endforeach--}}
{{--                    </ul>--}}
{{--                @endforeach--}}
{{--            </ul>--}}

{{--            <hr>--}}
            <div class="row mt-2">
                        <div class="col-lg-8" style="overflow-x: auto;">
                            <br>
                            <div class="row">
                                <div class="col-sm-6 col-md-8">
                                    <h4>Quantities:</h4>
                                </div>
                                <div class="col-sm-6 col-md-4" style="text-align: right">
                                    @if($urlYes==0)
                                        <a type="button" class="btn btn-secondary" href="{{ route('resourceViewAll',['Id'=> $house->id_Home, 'id_resource'=>$title->id_Stock_card])}}">All resources</a>
                                    @else
                                        <a type="button" class="btn btn-secondary" href="{{ route('resourceView',['Id'=> $house->id_Home, 'id_resource'=>$title->id_Stock_card])}}">Active only</a>
                                    @endif
                                        <a type="button" class="btn btn-primary" href="{{ route('batchesList',['Id'=> $house->id_Home, 'stock_id'=>$title->id_Stock_card])}}">Batches</a>


                                </div>
{{--                                <div class="col-sm-1 col-md-2" style="text-align:right">--}}
{{--                                    --}}{{--                        <div class="btn-group float-right mt-2" role="group">--}}
{{--                                    <button class="btn btn-primary"  data-url="{{ route('setMinMax2FromResource',--}}
{{--                            ['Id' => $house->id_Home, 'stock_card_id'=> $title->id_Stock_card]) }}" id="add-item">--}}
{{--                                       Batches</button>--}}

{{--                                     </div>--}}

                            </div>
                            <br>
{{--                            offset-lg-1" style="padding-top: 10px; margin-left: -15px--}}
{{--                            <div >--}}
                            <table style="overflow-x: auto" class="table table-hover table-condensed" id="sortTable" >
                                <thead>
                                <tr style="border-bottom: 0px">
                                    <th class="no-sort" style="width:40%;border-bottom: 10px;">Place</th>
                                    <th  class="no-sort" style="width:40%;border-bottom: 10px;">Quantity ({{$title->measurement_unit}})</th>
                                    <th class="no-sort" style="width: 20%;border-bottom: 10px;"></th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($topPlacesWhichHaveItem as $top)
                                    <tr  style="background-color: #e9ecef">
                                        <td> {{$top->place->Warehouse_name}} </td>
                                        <td> @if($top->quantity<0)
                                                0 ({{$top->totalQuantityIncludingChildren}})
                                                 @else
                                                {{$top->quantity}} ({{$top->totalQuantityIncludingChildren}})
                                        @endif
                                        </td>
                                        @if($top->quantity>=0)
                                        <td>
                                            <!-- Button trigger modal -->
                                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModalCenter-{{$top->place->id_Warehouse_place}}">
                                                Details
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="exampleModalCenter-{{$top->place->id_Warehouse_place}}" tabindex="-1"
                                               role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg " role="document" style=" ">

                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">{{$top->place->Warehouse_name}}</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div style="overflow-x: auto">
                                                        <div class="modal-body">
                                                            <table class="table table-hover table-condensed" id="irsortTable-{{$top->place->id_Warehouse_place}}">
                                                                <thead style="border-top: 0px;">
                                                                <tr style="border-bottom: 0px;">
{{--                                                                    <th style="border-bottom: 10px;">ID</th>--}}
                                                                    <th style="border-bottom: 10px;">Quantity ({{$title->measurement_unit}})</th>
                                                                    <th style="border-bottom: 10px;">Expiration date</th>
                                                                    <th style="border-bottom: 10px;">Batch no.</th>
                                                                    <th style="border-bottom: 10px;">Entry Type</th>
                                                                    <th style="border-bottom: 10px;">Action date</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>

                                                                @foreach($list as $l)
                                                                    @if($top->place->id_Warehouse_place==$l->fk_Warehouse_place)
                                                                        <tr>
{{--                                                                            <td>{{$l->stock_id}}</td>--}}
                                                                            <td>{{$l->quantity}}</td>
                                                                            @if($l->expiration_date < '9999-09-09')
                                                                                @if($l->expiration_date < date('Y-m-d'))
                                                                                <td style="color: #e3342f">{{$l->expiration_date}}</td>
                                                                                    @else
                                                                                    <td style="">{{$l->expiration_date}}</td>
                                                                                    @endif
                                                                            @else
                                                                                <td>-</td>
                                                                            @endif
                                                                            <td>{{$l->fk_Batch}}</td>
                                                                            @if($l->reason!=null)
                                                                            <td data-toggle="tooltip" title="{{$l->reason}}">
                                                                                {{$l->Entry_Type->name}}</td>
                                                                                @else
                                                                                <td>{{$l->Entry_Type->name}} </td>
                                                                            @endif
                                                                            <td>{{\Carbon\Carbon::parse($l->posting_date)->format('Y-m-d')}}</td>
                                                                        </tr>
                                                                    @endif
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
                                            @else
                                        <td></td>
                                            @endif
                                        </tr>
                                        @foreach ($top->childrenNodes as $childCategory)
                                            @include('ResourceManagement/child_category', ['child_category' => $childCategory,  'tab'=> '  '])
                                        @endforeach

{{--                                @foreach($places as $pl)--}}
{{--                                    @if($pl->total_quantity >0)--}}
{{--                                        <td> {{$pl->Warehouse_name}}--}}
{{--                                                <ul>--}}
{{--                                                   @foreach ($pl->Warehouse_Place as $childCategory)--}}
{{--                                                        @include('ResourceManagement/child_category', ['child_category' => $childCategory])--}}
{{--                                                    @endforeach--}}
{{--                                                </ul>--}}
{{--                                        </td>--}}

{{--                                        <td> {{$pl->total_quantity}}</td>--}}
{{--                                        <td>--}}
{{--                                        @foreach ($pl->Warehouse_Place as $doer)--}}
{{--                                            @foreach ($doer->task as $task)--}}
{{--                                               {{ $doer->Warehouse_name}}--}}
{{--                                            @endforeach--}}
{{--                                        @endforeach--}}
{{--                                    </td>--}}
{{--                                        <td>--}}
{{--                                            <!-- Button trigger modal -->--}}
{{--                                            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModalCenter-{{$pl->id_Warehouse_place}}">--}}
{{--                                                More--}}
{{--                                            </button>--}}
{{--                                            <!-- Modal -->--}}
{{--                                            <div class="modal fade" id="exampleModalCenter-{{$pl->id_Warehouse_place}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">--}}
{{--                                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">--}}
{{--                                                    <div class="modal-content">--}}
{{--                                                        <div class="modal-header">--}}
{{--                                                            <h5 class="modal-title" id="exampleModalLongTitle">{{$pl->Warehouse_name}}</h5>--}}
{{--                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                                                <span aria-hidden="true">&times;</span>--}}
{{--                                                            </button>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="modal-body">--}}
{{--                                                            <table class="table table-hover table-condensed" id="irsortTable-{{$pl->id_Warehouse_place}}">--}}
{{--                                                                <thead style="border-top: 0px;">--}}
{{--                                                                <tr style="border-bottom: 0px;">--}}
{{--                                                                    <th style="border-bottom: 10px;">ID</th>--}}
{{--                                                                    <th style="border-bottom: 10px;">Quantity</th>--}}
{{--                                                                    <th style="border-bottom: 10px;">Expiration date</th>--}}
{{--                                                                    <th style="border-bottom: 10px;">Batch no.</th>--}}
{{--                                                                    <th style="border-bottom: 10px;">Entry Type</th>--}}
{{--                                                                    <th style="border-bottom: 10px;">Action date</th>--}}
{{--                                                                </tr>--}}
{{--                                                                </thead>--}}
{{--                                                                <tbody>--}}
{{--                                                                    @foreach($list as $l)--}}
{{--                                                                        @if($pl->id_Warehouse_place==$l->fk_Warehouse_place)--}}
{{--                                                                            <tr>--}}
{{--                                                                            <td>{{$l->stock_id}}</td>--}}
{{--                                                                            <td>{{$l->quantity}}</td>--}}
{{--                                                                                @if($l->expiration_date < '9999-09-09')--}}
{{--                                                                            <td>{{$l->expiration_date}}</td>--}}
{{--                                                                                    @else--}}
{{--                                                                                    <td>-</td>--}}
{{--                                                                                @endif--}}
{{--                                                                            <td>{{$l->Stock_Batch->number}}</td>--}}
{{--                                                                            <td>{{$l->Entry_Type->name}}</td>--}}
{{--                                                                            <td>{{$l->posting_date}}</td>--}}
{{--                                                                            </tr>--}}
{{--                                                                        @endif--}}
{{--                                                                    @endforeach--}}
{{--                                                                </tbody>--}}
{{--                                                            </table>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="modal-footer">--}}
{{--                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--    --}}{{--                                                        <button type="button" class="btn btn-primary">Save changes</button>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    @endif--}}
{{--                                    @php--}}
{{--                                        $qcount=$qcount+$pl->total_quantity;--}}
{{--                                    @endphp--}}
                                    <script>
                                    $('#irsortTable-{{$top->place->id_Warehouse_place}}').DataTable({

                                        "order": [[4, "desc"]]
                                    } );
                                    </script>
                                 @php
                                    $qcount=$qcount+$top->totalQuantityIncludingChildren;
                                    @endphp
                                   @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="font-weight: bold"> Total Quantity in Household:</td>
                                        <td><span style="font-weight: bold"> {{$qcount}}</span> </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
{{--                            </div>--}}
                        </div>
            </div>
            <br>
            <hr>
            <div class="row mt-2">
                <div class="col-lg-8" style="margin-top: 2rem; overflow-x: auto;">
                    <div class="row">
                    <div class="col-md-9 col-lg-10">
                        <h4>Set min max quantities:</h4>
                    </div>
                    <div class="col-md-3 col-lg-2">
{{--                        <div class="btn-group float-right mt-2" role="group">--}}
                            <button class="btn btn-primary"  data-url="{{ route('setMinMax2FromResource',
                            ['Id' => $house->id_Home, 'stock_card_id'=> $title->id_Stock_card]) }}" id="add-item">
                                <i class="fa fa-plus" aria-hidden="true"></i> Set new</button>
{{--                        </div>--}}
{{--                        <a type="button" class="btn btn-primary" href="{{ route('wat')}}" data-toggle="modal" data---}}
{{--                                target="#AddMinMax" ><i class="fa fa-edit" aria-hidden="true">nuu</i></a>--}}
                    </div>
                    </div>
                    <br>
                <table class="table table-hover table-condensed" id="sortTable_2">
                    <thead>
                    <tr>
                        <th>Place</th>
                        <th>Min q. ({{$title->measurement_unit}})</th>
                        <th>Max q. ({{$title->measurement_unit}})</th>
                        <th class="no-sort"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($minMaxList as $minmax)
                            <tr  class="data-row">
                            @foreach($visos as $placepath)
                                @if($placepath->place==$minmax->fk_Warehouse_place)
                                        <td class="name" data-toggle="tooltip" title="{{$placepath->path}}">{{$minmax->Warehouse_Place->Warehouse_name}}
                                        </td>
                                    @break
                                        @endif
                                @endforeach
{{--                            </td>--}}
                            <td class="min">{{$minmax->min_amount}}</td>
                            <td class="max">{{$minmax->max_amount}}</td>
                            <td>
                                <button type="button" class="btn btn-secondary" id="edit-item" data-url="{{route('saveMinMax', ['Id' => $house->id_Home,
                                                 'set_id' => $minmax->fk_Stock_card, 'set_id2'=>$minmax->fk_Warehouse_place])}}">Edit</button>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>


            </div>
{{--        </div>--}}
    </div>

    <!-- Attachment Modal1 -->
    <div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="add-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-modal-label">Add {{$title->Name}} min max quantities</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form"  id="AddMinMax" method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="modal-body" id="attachment-body-content">

{{--                        <div class="form-group">--}}
{{--                            <label for="fk_Stock_card">Stock card</label>--}}
{{--                            <select id="fk_Stock_card" name="fk_Stock_card" class="form-control">--}}
{{--                                <option>Choose..</option>--}}
{{--                                @foreach($cards as $c)--}}
{{--                                    <option value="{{$c->id_Stock_card}}">{{$c->Name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="fk_Warehouse_place">Warehouse_place</label>--}}
{{--                            <select id="fk_Warehouse_place" name="fk_Warehouse_place" class="form-control selectpicker">--}}
{{--                                <option>Choose..</option>--}}
{{--                                @foreach($filteredPlacesForMinMax as $p)--}}
{{--                                    <option value="{{$p->id_Warehouse_place}}">{{$p->Warehouse_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
                        <div class="form-group">
                            <label for="fk_Warehouse_place">Warehouse place* <span style="font-weight: bold" id="topPlace"></span></label>
                            <select id="fk_Warehouse_place" name="fk_Warehouse_place" class="form-control" required>
                                <option value="">Select place</option>
                                @foreach($topPlaces as $p)
                                    <option value="{{$p->id_Warehouse_place}}" data-valuea="{{$p->Warehouse_name}}">{{$p->Warehouse_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="min">Min kept value ({{$title->measurement_unit}})</label>
                            <input type="number" step="0.01" name="min" min="0.00" class="form-control" id="min" placeholder="0.00" required>
                        </div>

                        <div class="form-group">
                            <label for="max">Max kept value ({{$title->measurement_unit}})</label>
                            <input type="number" step="0.01" name="max" min="0.00" class="form-control" id="max" placeholder="0.00" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Attachment Modal1 -->

    <!-- Attachment Modal -->
    <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit-modal-label">Edit {{$title->Name}} quantities</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form"  id="deleteFormClient" method="POST" action="" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="modal-body" id="attachment-body-content">

                                <div class="form-group">
                                    <label class="col-form-label" for="modal-input-name">Place</label>
                                    <input disabled type="text" name="modal-input-name" class="form-control" id="modal-input-name" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label" for="modal-input-description">Min quantity ({{$title->measurement_unit}})</label>
                                    <input type="number" step="0.01" min="0" name="min_amount" class="form-control" id="modal-input-description" required>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label" for="modal-input-description2">Max quantity ({{$title->measurement_unit}})</label>
                                    <input type="number" step="0.01" min="0" name="max_amount" class="form-control" id="modal-input-description2" required>
                                </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /Attachment Modal -->

    <script>
        $('#sortTable').DataTable({
            "aaSorting": [],
            // "ordering": false
        });

        $('#sortTable_2').DataTable();

    </script>



<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
{{--            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>--}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-en_US.min.js"></script>
<script>


    $(function () {
    $('[data-toggle="popover"]').popover()
    })

    $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    })


    $(document).ready(function() {
        /**
         * for showing edit item popup
         */
        $(document).on('click', "#edit-item", function() {
            $(this).addClass('edit-item-trigger-clicked'); //useful for identifying which trigger was clicked and consequently grab data from the correct row and not the wrong one.

            var options = {
                'backdrop': 'static'
            };
            $('#edit-modal').modal(options)
        })

        // on modal show
        $('#edit-modal').on('show.bs.modal', function() {
            var el = $(".edit-item-trigger-clicked");
            var row = el.closest(".data-row");

            // get the data
            const url = el.data('url');
            // alert(url);
            $('#deleteFormClient').attr('action', url);


            var name = row.children(".name").text();
            var min = row.children(".min").text();
            var max = row.children(".max").text();

            // // fill the data in the input fields

            $("#modal-input-name").val(name);
            $("#modal-input-description").val(min);
            $("#modal-input-description2").val(max);

        })

        // on modal hide
        $('#edit-modal').on('hide.bs.modal', function() {
            $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked')
            $("#deleteFormClient").trigger("reset");
        })
    })


    $(document).ready(function() {
        /**
         * for showing edit item popup
         */
        $(document).on('click', "#add-item", function() {
            $(this).addClass('add-item-trigger-clicked'); //useful for identifying which trigger was clicked and consequently grab data from the correct row and not the wrong one.

            var options = {
                'backdrop': 'static'
            };
            $('#add-modal').modal(options)
        })

        // on modal show
        $('#add-modal').on('show.bs.modal', function() {
            var el = $(".add-item-trigger-clicked");
            var row = el.closest(".data-row");

            // get the data
            const url = el.data('url');
            // alert(url);
            $('#AddMinMax').attr('action', url);
            //
            // var name = row.children(".name").text();
            // var min = row.children(".min").text();
            // var max = row.children(".max").text();
            //
            // // // fill the data in the input fields
            //
            // $("#modal-input-name").val(name);
            // $("#modal-input-description").val(min);
            // $("#modal-input-description2").val(max);

        })

        // on modal hide
        $('#add-modal').on('hide.bs.modal', function() {
            $('.add-item-trigger-clicked').removeClass('add-item-trigger-clicked')
            $("#add-form").trigger("reset");
        })
    })


        $(document).ready(function()
        {
            $('#topPlace').on('click', function()
            {
                $('#topPlace').html('');
                var house = "{!! addcslashes($house->id_Home, '"') !!}";
                // $('#fk_Warehouse_place').find('option').not(':first').remove();
                $('#fk_Warehouse_place').find('option').remove();
                var option = "<option value='"+''+"' data-valuea='"+''+"'>"+'Select'+"</option>";
                $("#fk_Warehouse_place").append(option);
                $.ajax({
                    url: '../../getAllPlaces/'+ house,
                    type: "get",
                    dataType: "json",
                    success: function(response){

                        var len = 0;
                        if(response['data'] != null){
                            len = response['data'].length;
                        }
// alert(len);
                        if(len > 0){
                            // Read data and create <option >
                            for(var i=0; i<len; i++){

                                var id_Stock_batch = response['data'][i].id_Warehouse_place;
                                var number = response['data'][i].Warehouse_name;

                                var option = "<option value='"+id_Stock_batch+"' data-valuea='"+number+"'>"+number+"</option>";
                                // alert(option);
                                $("#fk_Warehouse_place").append(option);
                                // $("#topPlace").append(number);
                            }
                        }
                    }
                });
            });
        });
    $(document).ready(function(){
        // Department Change
        $('#fk_Warehouse_place').change(function(){
            // Department id
            var id = $(this).val();
            // var name = $("select option:selected").data('valuea');
            var name =  $(this).find('option:selected').data('valuea');
            $("#topPlace").append(name);

            // alert(name);
            var house= "{!! addcslashes($house->id_Home, '"') !!}";
            var resource= "{!! addcslashes($title->id_Stock_card, '"') !!}";
            // Empty the dropdown

            // AJAX request
            $.ajax({
                url: '../../getChildPlaces/'+ house +'/'+ id,
                type: "get",
                dataType: "json",
                success: function(response){

                    var len = 0;
                    if(response['data'] != null){
                        len = response['data'].length;
                    }
// alert(len);
                    $('#fk_Warehouse_place option:not(:selected)').remove();
                    if(len > 0){
                        // Read data and create <option >
                        for(var i=0; i<len; i++){

                            var id_Stock_batch = response['data'][i].id_Warehouse_place;
                            var number = response['data'][i].Warehouse_name;
                            if(number!=null) {
                                // $('#fk_Warehouse_place').find('option').not(':first').remove();
                                var option = "<option value='" + id_Stock_batch + "' data-valuea='->" + number + "'>" + number + "</option>";

                                $("#fk_Warehouse_place").append(option);
                                // $("#topPlace").append(number);
                            }
                        }
                    }
                    else{
                        // $('#fk_Warehouse_place').find('option').not(':first').remove();
                        // var option = "<option value='" + id_Stock_batch + "' data-valuea='->" + number + "'>NoMore</option>";
                        // $("#fk_Warehouse_place").append(option);
                    }

                }
            });
        });

    });

    $(document).ready(function(){
        $('#min').change(function(){
            var minQ= $(this).val();
            // alert(minQ);
            $('#max').attr('min', minQ);
        });

    });



</script>
@endsection
