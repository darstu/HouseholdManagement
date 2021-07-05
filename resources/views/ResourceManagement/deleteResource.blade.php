<style>
    select {
        background-image:
            linear-gradient(45deg, transparent 50%, #495057 60%),
            linear-gradient(135deg, #495057 40%, transparent 50%) !important;
        background-position: calc(100% - 15px) 15px, calc(100% - 10px) 15px, 100% 0;
        background-size: 5px 5px, 5px 5px;
        background-repeat: no-repeat;
        -webkit-appearance: none;
        -moz-appearance: none;

    }
</style>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
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
                <h2 class="page-title" style="margin-left: 14px;">Reduce {{$resource->Name}} quantity</h2>
            </div>
            <div class="row mt-4">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{route('confirmDeleteResource', ['Id' => $house->id_Home, 'id_resource' =>$resource->id_Stock_card])}}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">


                        <div class="form-group">
                            <label for="fk_Warehouse_place">From Warehouse place</label>
                            {{--                            <select id="fk_Warehouse_place" name="fk_Warehouse_place" class="form-control">--}}
                            <select class="form-control selectpicker" data-live-search="true" name="category" id="category" required >
                                <option value="">Choose...</option>
                                @foreach($visos as $ep)
                                    <option value="{{$ep->place}}">{{$ep->placename}} ({{$ep->path}})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-5">
                            <label>Batch (number, expiration date, quantity)</label>
                            <select name="sub_category" id="subCategory" required class="form-control">
                                <option value="">Select batch</option>
                            </select>
                            </div>

                            <div class="form-group col-md-4">
                            <label>Expiration date</label>
                            <input type="date" name="sub_category2" id="expiration_date" class="form-control" readonly>
{{--                                <option value="">Select expiration date</option>--}}
{{--                            </input>--}}
                            </div>

                            <div class="form-group  col-md-3">
                                <label for="max_quantity">Quantity (max: <span id="lbl" ></span> {{$resource->measurement_unit}})</label>
                                <input type="number" required step="0.01" name="quantity"  min="0.01" max="" class="form-control" id="max_quantity" placeholder="0.00">
                            </div>

                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label for="quantity">Quantity </label>--}}
{{--                            <input type="number" required step="0.01" name="quantity"  min="0.01" class="form-control" id="quantity" placeholder="0.00">--}}
{{--                        </div>--}}

                        <div class="form-group">
                            <label for="inlineRadio1">Action type</label> <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio1" value="4" required>
                                <label class="form-check-label" for="inlineRadio1">Gifted</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio2" value="3">
                                <label class="form-check-label" for="inlineRadio2">Sold</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio3" value="5">
                                <label class="form-check-label" for="inlineRadio3">Removed</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio4" value="7">
                                <label class="form-check-label" for="inlineRadio4">Consumed</label>
                            </div>
                        </div>

                        <button type="submit" onclick="return confirm('Do you really want to reduce resource(-es)?')" class="btn btn-secondary ">Delete</button>
                    </form>
                </div>

            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    {{--            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>--}}

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-en_US.min.js"></script>
    <script type='text/javascript'>
        $(document).ready(function(){
            // Department Change
            $('#category').change(function(){
                // Department id
                var id_place = $(this).val();
                var house= "{!! addcslashes($house->id_Home, '"') !!}";
                var id= "{!! addcslashes($resource->id_Stock_card, '"') !!}";

                // Empty the dropdown
                $('#subCategory').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: '../../getCategoryDelete1/'+ house +'/'+ id +'/'+ id_place,
                    type: "get",
                    dataType: "json",
                    success: function(response){

                        var len = 0;
                        if(response['data'] != null){
                            len = response['data'].length;
                        }

                        if(len > 0){
                            // Read data and create <option >
                            for(var i=0; i<len; i++){


                                var stock_id = response['data'][i].stock_id;
                                var batch = response['data'][i].fk_Batch;
                                var quant = response['data'][i].total_quantity;
                                var date = response['data'][i].expiration_date;
                                var unit = response['data'][i].measurement_unit;
                                if(date==null || date >='9999-09-09')
                                {
                                    var date = '-'
                                }

// alert(unit);
                                // var number = response['data'][i].number;
                                if(quant>0) {
                                    var option = "<option value='" + batch + "'>" + batch + ' , ' + date + ' , ' + quant +' '+ unit+ "</option>";
                                }

                                // var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";

                                $("#subCategory").append(option);
                            }
                        }

                    }

                });
            });

        });

        $(document).ready(function(){
            // Department Change
            $('#subCategory').change(function(){
                // Department id
                var b = $('#subCategory').val();
                // alert(b);
                var id_place = $('#category').val();
                var house= "{!! addcslashes($house->id_Home, '"') !!}";
                var resource= "{!! addcslashes($resource->id_Stock_card, '"') !!}";

                // alert(house,b);
                // Empty the dropdown
                $('#expiration_date').find('input').remove();

                $('#expiration_date').val('');
                // $( "#expiration_date" ).prop( "readonly", false );

                // AJAX request
                $.ajax({
                    url: '../../checkBatch2/'+ house +'/'+ resource +'/'+ id_place +'/'+ b,
                    type: "get",
                    dataType: "json",
                    success: function(response){

                        var len = 0;
                        if(response['data'] != null){
                            len = response['data'].length;
                        }
                        if(len > 0){
                            // Read data and create <option >
                            for(var i=0; i<len; i++){

                                var expiration = response['data'][i].expiration_date;
                                var b = response['data'][i].fk_Batch;
                                var maxQ = response['data'][i].total_quantity;
                                if(expiration==null || expiration >='9999-09-09')
                                {
                                    var expiration = '-'
                                }
                                // alert(maxQ);

                                // var number = response['data'][i].number;

                                // var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";

                                // $("#subCategory").append(option);
                                $("#expiration_date").val(expiration);
                                // $("#max_quantity").val(maxQ);
                                $('#lbl').empty();
                                $("#lbl").append(maxQ);
                                $('#max_quantity').attr('max', maxQ);

                                $( "#expiration_date" ).prop( "readonly", true );
                            }
                        }

                    }
                });
            });

        });

        {{--//Second for expiration date--}}
        {{--$(document).ready(function(){--}}
        {{--    // Department Change--}}
        {{--    $('#subCategory').change(function(){--}}
        {{--        // Department id--}}
        {{--        var id_place = $('#category').val();--}}
        {{--        var id_batch = $(this).val();--}}
        {{--        var house= "{!! addcslashes($house->id_Home, '"') !!}";--}}
        {{--        var id= "{!! addcslashes($resource->id_Stock_card, '"') !!}";--}}

        {{--        // Empty the dropdown--}}
        {{--        $('#subCategory2').find('option').not(':first').remove();--}}
        {{--        // AJAX request--}}
        {{--        $.ajax({--}}
        {{--            url: '../../getCategoryDelete/'+ house +'/'+ id +'/'+ id_place +'/'+ id_batch,--}}
        {{--            type: "get",--}}
        {{--            dataType: "json",--}}
        {{--            success: function(response){--}}

        {{--                var len = 0;--}}
        {{--                if(response['data'] != null){--}}
        {{--                    len = response['data'].length;--}}
        {{--                }--}}

        {{--                if(len > 0){--}}
        {{--                    // Read data and create <option >--}}
        {{--                    for(var i=0; i<len; i++){--}}

        {{--                        var stock_id = response['data'][i].stock_id;--}}
        {{--                        var batch = response['data'][i].fk_Batch;--}}
        {{--                        var id_Stock_batch = response['data'][i].number;--}}
        {{--                        var date = response['data'][i].expiration_date;--}}
        {{--                        var q = response['data'][i].total_quantity;--}}
        {{--                        if(date < '9999-09-09') {--}}
        {{--                            // var number = response['data'][i].number;--}}
        {{--                            var option = "<option value='" + date + "'> " + date + " | " + q + "</option>";--}}
        {{--                        }--}}
        {{--                        else{--}}
        {{--                            var date1 ='None';--}}
        {{--                            var option = "<option value='" + date + "'> " + date1 + " | " + q + "</option>";--}}
        {{--                            $('#lbl').empty();--}}
        {{--                            $("#lbl").append(q);--}}
        {{--                        }--}}
        {{--                        // var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";--}}

        {{--                        $("#subCategory2").append(option);--}}
        {{--                        // if(date!=null) {--}}
        {{--                        //     // var number = response['data'][i].number;--}}
        {{--                        //     var option = "<option value='" + date + "'> " + date + " | " + q + "</option>";--}}
        {{--                        // }--}}
        {{--                        // else{--}}
        {{--                        //     var option = "";--}}
        {{--                        //     $('#lbl').empty();--}}
        {{--                        //     $("#lbl").append(q);--}}
        {{--                        // }--}}
        {{--                        //--}}
        {{--                        // // var number = response['data'][i].number;--}}
        {{--                        // // var option = "<option value='"+date+"'> "+date+" | "+q+"</option>";--}}
        {{--                        //--}}
        {{--                        // // var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";--}}
        {{--                        //--}}
        {{--                        // $("#subCategory2").append(option);--}}
        {{--                    }--}}
        {{--                }--}}

        {{--            }--}}

        {{--        });--}}
        {{--    });--}}

        {{--});--}}

    </script>

@endsection
