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
                <h3 style="margin-left: 14px;">Add resource</h3>
            </div>
            <div class="row mt-4">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{route('addResource2', ['Id' => $house->id_Home])}}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        {{--                                <div class="form-group">--}}
                        {{--                                    <label for="fk_Stock_card">Stock card</label>--}}
                        {{--                                    <select id="fk_Stock_card" name="fk_Stock_card" class="form-control">--}}
                        {{--                                        <option>Choose..</option>--}}
                        {{--                                        <option>...</option>--}}
                        {{--                                        @foreach($cards as $c)--}}
                        {{--                                            <option value="{{$c->id_Stock_card}}">{{$c->Name}}</option>--}}
                        {{--                                        @endforeach--}}
                        {{--                                    </select>--}}
                        {{--                                </div>--}}



                        <div class="form-group">
                            <label>Stock card:</label>
                            <select name="category" id="category" class="form-control">
                                <option value="0">Select stock</option>
{{--                                @foreach($cards as $category)--}}
{{--                                    <option value="{{ $category->id_Stock_card }}">{{$category->id_Stock_card}} + {{ $category->Name }}</option>--}}
{{--                                @endforeach--}}
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Batch</label>
{{--                            <div class="row">--}}
{{--                                <div class="col-6">--}}
                                    <select name="sub_category" id="subCategory" class="form-control">
                                        <option value="0">Select batch</option>
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                                <div class="col-6">--}}
{{--                                    <a class="btn btn-secondary " href="{{ route('addBatch', ['Id' => $house->id_Home]) }}">--}}
{{--                                        <i class="fa fa-plus" aria-hidden="true"></i> Add Batch</a>--}}
                                </div>
{{--                            </div>--}}

{{--                        </div>--}}


{{--                        <div class="form-group">--}}
{{--                            <label for="fk_Warehouse_place">Warehouse place</label>--}}
{{--                            <select id="fk_Warehouse_place" name="fk_Warehouse_place" class="form-control">--}}
{{--                                <option >Choose...</option>--}}
{{--                                @foreach($places as $p)--}}
{{--                                    <option value="{{$p->id_Warehouse_place}}">{{$p->Warehouse_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

                        {{--                            <div class="form-group">--}}
                        {{--                                <label for="fk_Batch">Batch</label>--}}
                        {{--                                <div class="row">--}}

                        {{--                                    <div class="col-6">--}}
                        {{--                                        <select id="fk_Batch" name="fk_Batch" class="form-control">--}}
                        {{--                                            <option>Choose...</option>--}}
                        {{--                                            @foreach($batches as $b)--}}
                        {{--                                                <option value="{{$b->id_Stock_batch}}">{{$b->number}}</option>--}}
                        {{--                                            @endforeach--}}
                        {{--                                        </select>--}}
                        {{--                                    </div>--}}
                        {{--                                    <div class="col-6">--}}
                        {{--                                        <a class="btn btn-secondary " href="{{ route('addBatch', ['Id' => $house->id_Home]) }}">--}}
                        {{--                                            <i class="fa fa-plus" aria-hidden="true"></i> Add Batch</a>--}}
                        {{--                                    </div>--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}

                        <div class="form-group">
                            <label for="quantity">Name</label>
                            <input type="text" step="0.01" name="quantity" min="0.01" class="form-control" id="quantity" placeholder="0.00">
                        </div>


                        <div class="form-group">
                            <label for="expiration_date">Address</label>
                            <input type="text" class="form-control" name="expiration_date" id="expiration_date">
                        </div>
                        <div class="form-group">
                            <label for="expiration_date">Description</label>
                            <input type="text" class="form-control" name="expiration_date" id="expiration_date">
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label for="inlineRadio1">Action type</label> <br>--}}
{{--                            <div class="form-check form-check-inline">--}}
{{--                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio1" value="2">--}}
{{--                                <label class="form-check-label" for="inlineRadio1">Received</label>--}}
{{--                            </div>--}}
{{--                            <div class="form-check form-check-inline">--}}
{{--                                <input class="form-check-input" type="radio" name="fk_Entry_type" id="inlineRadio2" value="1">--}}
{{--                                <label class="form-check-label" for="inlineRadio2">Purchase</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group">--}}
                                                        <label for="fk_Warehouse_place">Warehouse place</label>
                                                        <select id="fk_Warehouse_place" name="fk_Warehouse_place" class="form-control">
                                                            <option >Choose...</option>
{{--                                                            @foreach($places as $p)--}}
{{--                                                                <option value="{{$p->id_Warehouse_place}}">{{$p->Warehouse_name}}</option>--}}
{{--                                                            @endforeach--}}
                                                        </select>
                                                    </div>

                        <button type="submit" class="btn btn-secondary ">Add resource</button>
                    </form>
                </div>

            </div>
        </div>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type='text/javascript'>
        $(document).ready(function(){
            // Department Change
            $('#category').change(function(){
                // Department id
                var id = $(this).val();
                var house= "{!! addcslashes($house->id_Home, '"') !!}";
                // Empty the dropdown
                $('#subCategory').find('option').not(':first').remove();
                // AJAX request
                $.ajax({
                    url: '../getCategory/'+ house +'/'+ id,
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

                                var id_Stock_batch = response['data'][i].id_Stock_batch;
                                var number = response['data'][i].number;

                                var option = "<option value='"+id_Stock_batch+"'>"+number+"</option>";

                                $("#subCategory").append(option);
                            }
                        }

                    }
                });
            });

        });

    </script>


@endsection

<script>

</script>
