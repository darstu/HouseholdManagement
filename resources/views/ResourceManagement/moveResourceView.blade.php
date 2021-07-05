<!-- Latest compiled and minified CSS -->


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

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
                <h3 style="margin-left: 14px;">Move {{$resource->Name}}</h3>
            </div>
            <div class="row mt-4">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{route('moveResource2', ['Id' => $house->id_Home, 'id_resource' =>$resource->id_Stock_card])}}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">


                        <div class="form-group">
                            <label for="fk_Warehouse_place">From Warehouse place*</label>
                            <select  class=" selectpicker form-control" data-live-search="true" name="category" id="category" required >
                                <option value="" >Select</option>
                                @foreach($visos as $ep)
{{--                                    @if (old('category')==$ep->place)--}}
                                    <option value="{{$ep->place}}" data-tokens="{{$ep->placename}}">{{$ep->placename}} ({{$ep->path}})</option>
{{--                               @endif--}}
                                @endforeach
                            </select>
{{--                                <select  class="form-control" name="category" id="category" required >--}}


{{--                                <option value="" >Select from  </option>--}}
{{--                                @foreach($visos as $ep)--}}
{{--                                    <option value="{{$ep->place}}" data-tokens="{{$ep->placename}}">{{$ep->placename}} ({{$ep->path}})</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                            <input list="category1" id= "category" name="category">--}}
{{--                            <datalist name="category" id="category1"  >--}}


{{--                                <option value="" >Select from  </option>--}}
{{--                                @foreach($visos as $ep)--}}
{{--                                    <option value="{{$ep->place}}" data-tokens="{{$ep->placename}}">{{$ep->placename}} ({{$ep->path}})</option>--}}
{{--                                @endforeach--}}
{{--                            </datalist>--}}

                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label>Batch* (number, expiration date, quantity)</label>
                                <select name="sub_category" id="subCategory" required class="form-control">
                                    <option value="">Select</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Expiration date</label>
                                <input type="date" name="sub_category2" id="expiration_date" class="form-control" readonly>
                            </div>

                            <div class="form-group  col-md-3">
                                <label for="max_quantity">Quantity* (max: <span id="lbl" ></span> {{$resource->measurement_unit}})</label>
                                <input type="number" value="{{old('quantity')}}" required step="0.01" name="quantity"  min="0.01" max="" class="form-control" id="max_quantity" placeholder="0.00">
                            </div>

                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label>Batch*</label>--}}
{{--                                    <select name="sub_category" id="subCategory" required class="form-control">--}}
{{--                                        <option value="">Select batch</option>--}}
{{--                                    </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label>Expiration date*</label>--}}
{{--                            <select name="sub_category2" id="subCategory2" required class="form-control">--}}
{{--                                <option value="">Select expiration date</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}

                        <div class="form-group">
                            <label for="fk_Warehouse_place">To Warehouse place* <span style="font-weight: bold" id="topPlace"></span></label>
                            <select id="fk_Warehouse_place" name="fk_Warehouse_place" required class="form-control selectpicker" data-live-search="true">
                                <option value="" >Select</option>
{{--                                    <option value="{{$p->id_Warehouse_place}}">{{$p->Warehouse_name}}</option>--}}
{{--                                @endforeach--}}

                            </select>
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label for="quantity">Quantity* (max: <span id="lbl" ></span>)</label>--}}
{{--                            <input type="number" step="0.01" max="" required name="quantity"  min="0.01" class="form-control" id="quantity" placeholder="0.00">--}}
{{--                        </div>--}}

                        <div class="form-group">
                            <label for="reason">Reason*</label>
                            <input type="text" required name="reason"  class="form-control" id="reason" placeholder="Insert...">
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <input type="text" name="comment"  class="form-control" id="comment" placeholder="Insert...">
                        </div>


                        <button type="submit" class="btn btn-secondary " onclick="return confirm('Do you really want to move resource(-es)?')">Move resource</button>
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
    {{--    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>--}}
    {{--    <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
    <script type='text/javascript'>
        $(document).ready(function(){
            // Department Change
            $('#category').change(function(){
                // Department id
                var id_place = $(this).val();
                var house= "{!! addcslashes($house->id_Home, '"') !!}";
                var id= "{!! addcslashes($resource->id_Stock_card, '"') !!}";
                // alert(id_place);

                // Empty the dropdown
                $('#subCategory').find('option').not(':first').remove();
                $('#fk_Warehouse_place').find('option').not(':first').remove();

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

                                // var stock_id = response['data'][i].stock_id;
                                // var batch = response['data'][i].fk_Batch;
                                // var number = response['data'][i].number;
                                // var date = response['data'][i].expiration_date;
                                var stock_id = response['data'][i].stock_id;
                                var batch = response['data'][i].fk_Batch;
                                var quant = response['data'][i].total_quantity;
                                var date = response['data'][i].expiration_date;
                                var unit = response['data'][i].measurement_unit;
                                if(date==null || date >='9999-09-09')
                                {
                                    var date = '-'
                                }

                                if(quant>0) {
                                    var option = "<option value='" + batch + "'>" + batch + ', ' + date + ', ' + quant +' '+ unit+ "</option>";
                                }

                                // var number = response['data'][i].number;
                                // var option = "<option value='"+batch+"'>"+batch+"</option>";

                                // var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";

                                $("#subCategory").append(option);
                            }
                        }
                        var len2 = 0;
                        if(response['data2'] != null){
                            len2 = response['data2'].length;
                        }
                        if(len2 > 0){
                            // Read data and create <option >
                            for(var i=0; i<len2; i++) {
                                var place = response['data2'][i].id_Warehouse_place;
                                var name = response['data2'][i].Warehouse_name;
                                var option2 = "<option value='"+place+ "' data-valuea='" + name + "'>"+name+"</option>";
                                // alert(option2);
                                // var option = "<option value='" + id_Stock_batch + "' data-valuea='->" + number + "'>" + number + "</option>";
                                $("#fk_Warehouse_place").append(option2);
                            }
                        }
                        $('#fk_Warehouse_place').selectpicker('refresh');
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
                                // alert(maxQ);
                                // var number = response['data'][i].number;
                                // var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";

                                                if(expiration < '9999-09-09') {
                                                    $("#expiration_date").val(expiration);
                                                    // $("#max_quantity").val(maxQ);
                                                    $('#lbl').empty();
                                                    $("#lbl").append(maxQ);
                                                    $('#max_quantity').attr('max', maxQ);
                                                }
                                                else{
                                                    var expiration2='';
                                                    $("#expiration_date").val(expiration2);
                                                    // $("#max_quantity").val(maxQ);
                                                    $('#lbl').empty();
                                                    $("#lbl").append(maxQ);
                                                    $('#max_quantity').attr('max', maxQ);
                                                }

                                $( "#expiration_date" ).prop( "readonly", true );
                            }
                        }

                    }
                });
            });

        });

        //Second for expiration date
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

        {{--                    }--}}
        {{--                }--}}

        {{--            }--}}

        {{--        });--}}


        {{--    });--}}

        {{--});--}}

        $(document).ready(function(){
            // Department Change
            $('#subCategory2').change(function(){
                var id_place = $('#category').val();
                var id_batch = $('#subCategory').val();
                var id_exp = $(this).val();
                var house= "{!! addcslashes($house->id_Home, '"') !!}";
                var id= "{!! addcslashes($resource->id_Stock_card, '"') !!}";

                $.ajax({
                    url: '../../getCategoryDelete2/' + house + '/' + id + '/' + id_place + '/' + id_batch + '/' + id_exp,
                    type: "get",
                    dataType: "json",
                    success: function (response) {

                        var len = 0;
                        if (response['data'] != null) {
                            len = response['data'].length;
                        }
                        if (len > 0) {
                            // Read data and create <option >
                            for (var i = 0; i < len; i++) {
                                var q = response['data'][i].total_quantity;
                                var quan = "<input max='"+q+"'</input>";

                                $('#lbl').empty();
                                $("#lbl").append(q);
                                $("#quantity").append(quan);

                            }
                        }
                    }
                });


            });
        });

        {{--$(document).ready(function(){--}}
        {{--    // Department Change--}}
        {{--    $('#category').change(function(){--}}
        {{--        // Department id--}}
        {{--        var id_place = $(this).val();--}}

        {{--        var house= "{!! addcslashes($house->id_Home, '"') !!}";--}}
        {{--        --}}{{--var id= "{!! addcslashes($resource->id_Stock_card, '"') !!}";--}}

        {{--        // Empty the dropdown--}}
        {{--        $('#fk_Warehouse_place').find('option').not(':first').remove();--}}
        {{--        // AJAX request--}}
        {{--        $.ajax({--}}
        {{--            url: '../../getCategoryMove2/'+ house +'/'+ id_place,--}}
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

        {{--                        var id = response['data'][i].id_Warehouse_place;--}}
        {{--                        var name = response['data'][i].Warehouse_name;--}}

        {{--                        // var number = response['data'][i].number;--}}
        {{--                        var option = "<option value='"+id+"'>"+name+"</option>";--}}

        {{--                        // var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";--}}

        {{--                        $("#fk_Warehouse_place").append(option);--}}
        {{--                    }--}}
        {{--                }--}}

        {{--            }--}}

        {{--        });--}}
        //     });

        // });
        $(document).ready(function(){
            // Department Change
            $('#fk_Warehouse_place').change(function(){
                // Department id

                var id = $(this).val();
                var name =  $(this).find('option:selected').data('valuea');
                $("#topPlace").append(name);
                // alert(name);

                // alert(name);
                var house= "{!! addcslashes($house->id_Home, '"') !!}";
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
                                }
                            }
                        }
                        else{
                            // $('#fk_Warehouse_place').find('option').not(':first').remove();
                            // var option = "<option value='" + id_Stock_batch + "' data-valuea='->" + number + "'>NoMore</option>";
                            // $("#fk_Warehouse_place").append(option);
                        }
                        $('#fk_Warehouse_place').selectpicker('refresh');
                    }
                });
            });
        });
        $(document).ready(function()
        {
            $('#topPlace').on('click', function()
            {
                $('#topPlace').html('');
                var house = "{!! addcslashes($house->id_Home, '"') !!}";
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
                        $('#fk_Warehouse_place').selectpicker('refresh');
                    }

                });

            });

        });

        function filterFunction() {
            var input, filter, ul, li, a, i, option;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            div = document.getElementById("myDropdown");
            a = div.getElementsByTagName("option");
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
{{--    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>--}}
{{--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>--}}
{{--    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-en_US.min.js"></script>--}}

@endsection



