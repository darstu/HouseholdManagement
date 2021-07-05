<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
{{--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">--}}
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<style>

</style>


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
    <div class="container-fluid" >

        <div class="row mt-2">
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
                             <h2 class="page-title">{{$card->Name}} batches list</h2>
                </div>
            </div>
        </div>

        <div class="container-fluid" style="padding-bottom: 50px">
            <div class="row filters">
                <div class="col" style="padding-bottom: 20px">
                    @if($button!=2)
                        <a href="{{ route('batchesList', ['Id' => $house->id_Home, 'stock_id' =>$card->id_Stock_card]) }}"
                           class="btn btn-secondary active" role="button" aria-pressed="true">Active batches</a>
                        <a href="{{ route('allBatchesList', ['Id' => $house->id_Home, 'stock_id' =>$card->id_Stock_card]) }}"
                           class="btn btn-secondary" role="button" >All batches</a>
                    @else
                        <a href="{{ route('batchesList', ['Id' => $house->id_Home, 'stock_id' =>$card->id_Stock_card]) }}"
                           class="btn btn-secondary" role="button" >Active batches</a>
                        <a href="{{ route('allBatchesList', ['Id' => $house->id_Home, 'stock_id' =>$card->id_Stock_card]) }}"
                           class="btn btn-secondary active" role="button" aria-pressed="true">All batches</a>
                    @endif
                </div>
            </div>
            <div class="row">

                <div style="overflow-x: auto;" class="col-lg-8">
                <table class="table table-hover table-condensed" id="sortTableB" >
                    <thead>
                    <tr style="border-bottom: 0px">
                        <th class="" style="border-bottom: 10px;">Number</th>
                        <th style="border-bottom: 10px;">Expiration</th>
                        <th style="border-bottom: 10px;">Quantity</th>
                        <th class="no-sort" style="width: 20%;border-bottom: 10px;"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $batch)
                        <tr class="data-row">
                        <td class="number"> {{$batch->fk_Batch}}</td>
                            @if($batch->expiration_date>='9999-09-09')
                                <td class="expiration">-</td>
                                @else
                                    @if($batch->expiration_date < date('Y-m-d'))
                                        <td style="color: #e3342f" class="expiration">{{$batch->expiration_date}}</td>
                                    @else
                                        <td style="">{{$batch->expiration_date}}</td>
                                    @endif
                            @endif
                            <td>{{$batch->total_quantity}}</td>
                        <td>

{{--                            <a class="btn btn-secondary btn-md" href="{{ route('editBatch', ['Id' => $house->id_Home, 'batch_id'=>$batch->fk_Batch]) }}">--}}
{{--                                                                        <i aria-hidden="true"></i> Edit</a>--}}
                            <button type="button" class="btn btn-secondary" id="edit-item"
                                    data-url="{{route('saveEditBatch', ['Id' => $house->id_Home, 'card'=>$card->id_Stock_card,$batch->fk_Batch])}}">Edit</button>
                        </td>
                        </tr>
                        <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="edit-modal-label">Edit {{$card->Name}} batch</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form role="form"  id="deleteFormClient" method="POST" action="" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="modal-body" id="attachment-body-content">
                                            <div class="form-group">
                                                <label class="col-form-label" for="modal-input-description">Batch no.</label>
                                                <input type="text" minlength="3" name="batch" class="form-control" id="modal-input-description" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label" for="modal-input-description2">Expiration date</label>
                                                <input type="date"  min="0" name="expiration" class="form-control" id="modal-input-description2">
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
                    @endforeach
                    </tbody>
                </table>
                </div>

                <!-- Attachment Modal -->

                <!-- /Attachment Modal -->

            </div>
        </div>
    </div>
    <script>
        $('#sortTableB').DataTable({

                "order": [[1, "desc"]]
            }
        );

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


                // var name = row.children(".name").text();
                var min = row.children(".number").text();
                var max = row.children(".expiration").text();


                // // fill the data in the input fields


                $("#modal-input-description").val(min);
                $("#modal-input-description2").val(max);

            })

            // on modal hide
            $('#edit-modal').on('hide.bs.modal', function() {
                $('.edit-item-trigger-clicked').removeClass('edit-item-trigger-clicked')
                $("#deleteFormClient").trigger("reset");
            })
        })

    </script>
@endsection
