
<tr class="">
    <td  style="padding-left: 30px">&nbsp;{{$tab}}{{ $child_category->place->Warehouse_name }}</td>
    <td>
       @if($child_category->quantity<0)
            0 ({{$child_category->totalQuantityIncludingChildren}})
        @else
            {{$child_category->quantity}} ({{$child_category->totalQuantityIncludingChildren}})
        @endif
      </td>
    @if($child_category->quantity>=0)
    <td>
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModalCenter-{{$child_category->place->id_Warehouse_place}}">
            Details
        </button>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter-{{$child_category->place->id_Warehouse_place}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{$child_category->place->Warehouse_name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div style="overflow-x: auto">
                    <div class="modal-body">
                        <table class="table table-hover table-condensed" id="irsortTable-{{$child_category->place->id_Warehouse_place}}">
                            <thead style="border-top: 0px;">
                            <tr style="border-bottom: 0px;">
{{--                                <th style="border-bottom: 10px;">ID</th>--}}
                                <th style="border-bottom: 10px;">Quantity({{$title->measurement_unit}})</th>
                                <th style="border-bottom: 10px;">Expiration date</th>
                                <th style="border-bottom: 10px;">Batch no.</th>
                                <th style="border-bottom: 10px;">Entry Type</th>
                                <th style="border-bottom: 10px;">Action date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($list as $l)
                                @if($child_category->place->id_Warehouse_place==$l->fk_Warehouse_place)
                                    <tr>
{{--                                        <td>{{$l->stock_id}}</td>--}}
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
                        {{--                                                        <button type="button" class="btn btn-primary">Save changes</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </td>
    @else
        <td></td>
    @endif
    <script>
        $('#irsortTable-{{$child_category->place->id_Warehouse_place}}').DataTable(
            {

                "order": [[4, "desc"]]
            }
        );
    </script>
</tr>

@if ($child_category->childrenNodes)
{{--    <ul>--}}
@php
$tab .= html_entity_decode('&nbsp;&nbsp;&nbsp;&nbsp;');
@endphp
        @foreach ($child_category->childrenNodes as $childCategory)
            @include('ResourceManagement/child_category', ['child_category' => $childCategory, 'tab'=> $tab])
        @endforeach
{{--    </ul>--}}
@endif
