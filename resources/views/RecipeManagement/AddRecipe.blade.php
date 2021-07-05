@extends('app')
@section('ActionMenu')
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">Action list</div>
        <div class="list-group list-group-flush">
            <a href="{{route('Favorites')}}" class="list-group-item list-group-item-action bg-light">Favorite
                recipes</a>
        </div>
    </div>
@endsection
@section('Content')
    <?php $urlData = session('urlData');
    $j = 0;   ?>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <br>
    <div class="container">
        @if(!isset($recipe))
            <div class="col-md-6">
                <button class="btn btn-secondary" id="urlDetail" data-toggle="modal" data-target="#urlModal">Input
                    recipe by address
                </button>
            </div>
            <div id="summary" class="col-md-6">
                <div class="row"><b>Accepted links:</b></div>
                <div class="row">www.allrecipes.com</div>
            </div>
        @endif

        <form role="form" method="POST" action="
@isset($tag)
        @if($tag=='edit')
        {{route('Recipe.update',$recipe)}}
        @elseif ($tag=='newVersion')
        {{route('AddRecipe',$recipe->id_Recipe)}}
        @endif
        @else
        {{route('AddRecipe',0)}}
        @endisset
            " enctype="multipart/form-data">
            @csrf
            <div class="form-group col-md-8">
                <label for="inputName">Recipe title <span style="color: red">*</span></label>
                <input type="text" class="form-control" id="inputName" name="Name"
                       @if(isset($urlData))
                       value="{{$urlData['Name']}}"
                       @elseif(isset($recipe))
                       value="{{$recipe->Name}}"
                       @endif
                       value="{{old('Name')}}"
                       style="text-transform: capitalize;">
            </div>
            <div class="form-group col-md-8">
                <label for="inputDescription">Recipe Description <span style="color: red">*</span></label>
                <textarea class="form-control" id="inputDescription" name="Description" rows="5"
                          placeholder="Describe the recipe or give insights about it"
                          style="text-transform: capitalize;">@if(isset($urlData)){{$urlData['Description']}}@elseif(isset($recipe)){{$recipe->Description}}@else{{old('Description')}}@endif</textarea>

            </div>
            <div class="form-group row" style="padding-left: 15px">
                <div class="col-md-3">
                    <label for="inputRecipeType">Dish type <span style="color: red">*</span></label>
                    <input type="text" class="form-control" id="DishType"
                           name="DishType" style="text-transform: capitalize;"
                           @if(isset($urlData))value="{{$urlData['DishType']}}"
                           @elseif(isset($recipe))value="{{$recipe->Dish_Type->Name}}"
                           @endisset value="{{old('DishType')}}">
                </div>
                <div class="col-md-3">
                    <label for="Difficulty">Difficulty <span style="color: red">*</span></label>
                    <select id="Difficulty" class="form-control" name="Difficulty">
                        <option value="">Choose...</option>
                        <option
                            value="Easy" @isset($recipe){{$recipe->Difficulty == 'Easy' ? 'selected' : ''}}
                            @else{{old('Difficulty') == 'Easy' ? 'selected' : ''}}@endif>Easy
                        </option>
                        <option
                            value="Medium" @isset($recipe){{$recipe->Difficulty== 'Medium' ? 'selected' : ''}}
                            @else{{old('Difficulty') == 'Medium' ? 'selected' : ''}}@endif>Medium
                        </option>
                        <option
                            value="Hard" @isset($recipe){{$recipe->Difficulty== 'Hard' ? 'selected' : ''}}
                            @else{{old('Difficulty'== 'Hard' ? 'selected' : '')}}@endif>Hard
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-group row" style="padding-left: 15px">
                <div class="col-md-3">
                    <label for="inputRecipeType">Diet type <span style="color: red">*</span></label>
                    <input type="text" class="form-control" id="DietType"
                           name="DietType" style="text-transform: capitalize;"
                           @if(isset($urlData))value="{{$urlData['DietType']}}"
                           @elseif(isset($recipe))value="{{$recipe->Diet_type->Name}}"
                           @endisset value="{{old('DietType',)}}">
                </div>
                <div class="col-md-3">
                    <label for="inputTime">Cooking time <span style="color: red">*</span></label>
                    <input type="time" class="form-control" id="timeinput" name="Cooking_time"
                           @if(isset($urlData))
                           value="{{$urlData['CookingTime']}}"
                           @elseif(isset($recipe))
                           value="{{$recipe->Cooking_time}}"
                           @endif
                           value="{{old('Cooking_time')}}">
                </div>
            </div>
            <div class="col-md-8">
                <div style="border:1px solid black"></div>
            </div>
            <div style="padding: 10px 0 0 15px">Ingredient List <span style="color: red">*</span></div>
            <div class="form-group row">
                <div class="col-md-3" style="padding-left:60px">
                    Name
                </div>
                <div class="col-md-2" style="padding-left:60px">
                    Amount
                </div>
                <div class="form-group col-md-3" style="padding-left:60px">
                    Units
                </div>
            </div>
            @isset($urlData)
                @for($i = 0; $i < sizeof($urlData['Ingredients']); $i++)
                    <div class="form-group row">
                        <label class="col-form-label" style="padding-left:30px">{{$i+1}}.</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control"
                                   name="inputIngredientName[]" style="text-transform: capitalize;"
                                   value="{{$urlData['Ingredients'][$i]['name']}}">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="colFormLabel" name="inputIngredientAmount[]"
                                   value="{{$urlData['Ingredients'][$i]['amount']}}">
                        </div>
                        <div class="form-group col-md-3">
                            <select id="inputUnits" class="form-control inputUnits" name="inputUnits[]">
                                <option value="">Choose...</option>
                                @foreach($units as $unit)
                                    <option
                                        value="{{$unit->Name}}" {{ucfirst($urlData['Ingredients'][$i]['unit']) == $unit->Name ? 'selected' : ''}}>{{$unit->Name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor
                <?php $j = sizeof($urlData['Ingredients']); ?>
            @endisset
            @for($i = 0+$j; $i < 100; $i++)
                <div class="IngredientLine">
                    <div class="form-group row">

                        <label class="col-form-label" style="padding-left:30px">{{$i+1}}.</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control IngredientName" id="IngredientName"
                                   name="inputIngredientName[]" style="text-transform: capitalize;"
                                   @isset($recipe->Recipe_Ingredient[$i]->Product)
                                   value="{{old('inputIngredientName.'.$i,$recipe->Recipe_Ingredient[$i]->Product->Name)}}">
                            <input type="hidden" name="oldIngredientName[]"
                                   value="{{$recipe->Recipe_Ingredient[$i]->Product->Name}}">
                            @else
                                value="{{old('inputIngredientName.'.$i)}}">
                            @endisset
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="colFormLabel" name="inputIngredientAmount[]"
                                   @isset($recipe->Recipe_Ingredient[$i]->Amount)
                                   value="{{old('inputIngredientAmount.'.$i,$recipe->Recipe_Ingredient[$i]->Amount)}}">
                            <input type="hidden" name="oldIngredientAmount[]"
                                   value="{{$recipe->Recipe_Ingredient[$i]->Amount}}">
                            @else
                                value="{{old('inputIngredientAmount.'.$i)}}">
                            @endisset
                        </div>
                        <div class="form-group col-md-3">
                            <select id="inputUnits" class="form-control inputUnits" name="inputUnits[]">
                                <option value="" selected>Choose...</option>
                                @foreach($units as $unit)
                                    <option
                                        value="{{$unit->Name}}" @isset($recipe->Recipe_Ingredient[$i]->Unit)
                                        {{$recipe->Recipe_Ingredient[$i]->Unit->Name == $unit->Name ? 'selected' : ''}}
                                        @else{{old('inputUnits.'.$i) == $unit->Name ? 'selected' : ''}}
                                        @endif
                                    >{{$unit->Name}}</option>
                                @endforeach
                            </select>
                            @isset($recipe->Recipe_Ingredient[$i]->Unit)
                                <input type="hidden" name="oldUnits[]"
                                       value="{{$recipe->Recipe_Ingredient[$i]->Unit->Name}}">
                            @else
                                <input type="hidden" name="oldUnits[]" value="">
                            @endisset
                        </div>
                    </div>
                </div>
            @endfor
            <div class="form-group row">
                <a href="#" id="loadIngredient" style="padding-left: 30px">Load More</a>
                <div class="col-md-2">
                </div>
                <label for="ServingCount" class="col-form-label">Servings count from listed ingredients <span
                        style="color: red">*</span></label>
                <div class="col-md-2">
                    <input type="number" class="form-control" id="ServingCount" name="Servings_Count"
                           @if(isset($urlData))
                           value="{{$urlData['Servings']}}"
                           @elseif(isset($recipe))
                           value="{{$recipe->Servings_count}}"
                           @endif
                           value="{{old('Servings_Count')}}">
                </div>
            </div>
            <div class="col-md-8">
                <div style="border:1px solid black"></div>
            </div>
            <div style="padding: 10px 0 0 15px">Cooking instruction <span style="color: red">*</span></div>
            <br>.
            @isset($urlData)
                @for($i = 0; $i < sizeof($urlData['Instructions']); $i++)
                    <div>
                        <div class="form-group row">
                            <label for="uriInst" class="col-form-label" style="padding-left:30px">{{$i+1}}.</label>
                            <div class="col-md-8">
                        <textarea class="form-control" id="uriInst"
                                  name="inputInstructionName[]"
                                  style="text-transform: capitalize;">{{$urlData['Instructions'][$i]}}</textarea>
                                <label>Step Photo</label>
                                <input style="padding-left: 15px" type="file" class="row" name="InstructionImage[]"
                                       value="{{old('InstructionImage'.$i)}}">
                            </div>
                        </div>
                    </div>
                @endfor
                <?php $j = sizeof($urlData['Instructions']); ?>
            @endisset
            @for($i = $j; $i < 50; $i++)
                <div class="InstructionLine">
                    <div class="form-group row">
                        <label for="mhm" class="col-form-label" style="padding-left:30px">{{$i+1}}.</label>
                        <div class="col-md-8">
                        <textarea class="form-control" id="mhm"
                                  name="inputInstructionName[]"
                                  style="text-transform: capitalize;">@isset($recipe->Cooking_instruction_step[$i]){{old('inputInstructionName.'.$i,$recipe->Cooking_instruction_step[$i]->Step_Description)}}@else{{old('inputInstructionName.'.$i)}}@endisset</textarea>
                            @isset($recipe->Cooking_instruction_step[$i])
                                <input type="hidden" name="oldInstructions[]"
                                       value="{{$recipe->Cooking_instruction_step[$i]->Step_Description}}">
                                @isset($recipe->Cooking_instruction_step[$i]->Image_address)
                                    <label>Upload a new photo</label>
                                @else
                                    <label>Upload a photo</label>
                                @endisset
                                <input style="padding-left: 15px" type="file" class="row" name="InstructionImage[]"
                                       value="{{old('InstructionImage'.$i)}}">
                                <input type="hidden" name="oldInstructionsImage[]"
                                       value="{{$recipe->Cooking_instruction_step[$i]->Image_address}}">
                            @else
                                <input type="hidden" name="oldInstructions[]"
                                       value="">
                                <label>Upload a photo</label>
                                <input style="padding-left: 15px" type="file" class="row" name="InstructionImage[]"
                                       value="{{old('InstructionImage'.$i)}}">
                                <input type="hidden" name="oldInstructionsImage[]" value="">
                            @endisset
                        </div>
                    </div>
                </div>
            @endfor
            <div id="loadInstructions" style="padding: 0 0 10px 15px">
                <a href="#">Load More</a>
            </div>
            <div class="col-md-8">
                <div style="border:1px solid black"></div>
            </div>
            <div style="padding: 10px 0 0 15px">
                @isset($recipe)
                    <p>Photo</p>
                    <a id="photoUpload">Upload a new photo</a>
                    <input type="hidden" name='oldPhoto' value="{{$recipe->Image_address}}">
                @else
                    <p>Photo <span style="color: red">*</span></p>
                    <a id="photoUpload">Upload a photo</a>
                @endif
                @if(isset($urlData)||old('hostname')!==null)
                    <span style="padding-left: 50px" id="photoSourceUpload">Use from source</span>
                    <h2 id="urlPhoto" style="display: none">Photo uploaded</h2>
                @endif
                <input type="hidden" name="imageSource"
                       @isset($urlData)
                       value="{{$urlData['Image']}}"
                       @endisset
                       value="{{old('imageSource')}}">
                <input type="hidden" name="hostname"
                       @isset($urlData)
                       value="{{$urlData['source']}}"
                       @endisset
                       @isset($recipe->Source)
                       value="{{$recipe->Source->Name}}"
                       @else
                       value="{{old('hostname')}}"
                    @endisset>
                <input type="hidden" name="url"
                       @isset($urlData)
                       value="{{$urlData['url']}}"
                       @endisset
                       @isset($recipe->Source)
                       value="{{$recipe->Source->Address}}"
                       @else
                       value="{{old('url')}}"
                    @endisset>
            </div>
            <br>
            <div id="photo" class="form-group" style="padding-left: 15px">
                <button>Choose</button>
                <input id="Image" type="file" name="Image" value="{{old('Image')}}">
            </div>
            <div class="col-md-8">
                <div style="border:1px solid black"></div>
            </div>
            <div style="padding: 10px 10px 0 15px">Visibility <span style="color: red">*</span></div>
            <div class="cold-md-8" style="padding-left: 15px">
                @isset($recipe)
                    <label class="radio-inline" style="margin-right: 5px">
                        <input type="radio" name="optRadio" value="1" {{$recipe->Visibility==1 ? 'checked' : ''}} >
                        Public
                    </label>
                    <label class="radio-inline" style="margin-right: 5px">
                        <input type="radio" name="optRadio" value="0" {{$recipe->Visibility==0 ? 'checked' : ''}}>
                        Private
                    </label>
                @else
                    <label class="radio-inline" style="margin-right: 5px">
                        <input type="radio" name="optRadio" value="1" checked> Public
                    </label>
                    <label class="radio-inline" style="margin-right: 5px">
                        <input type="radio" name="optRadio" value="0"> Private
                    </label>
                @endisset
            </div>
            <div style="padding-left: 15px">
                <button type="submit" class="btn btn-primary">
                    @isset($tag)
                        @if($tag=='edit')
                            Save recipe
                        @elseif ($tag=='newVersion')
                            Upload new version
                        @endif
                    @else
                        Upload recipe
                    @endisset
                </button>
            </div>
        </form>
    </div>
    <div id="urlModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Input recipe URL</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{route('AddRecipeURL')}}" class="d-flex">
                        <input class="form-control" type="text" placeholder="input url.." name="url">
                        <button class="btn btn-primary" type="submit" style="width: 50px"><i class="fa fa-check"></i>
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    function handler(event) {
        event.stopPropagation();
    }

    $(document).ready(function () {
        $(".IngredientLine").slice(0, 5).show();
        if ($(".IngredientLine:hidden").length !== 0) {
            $("#loadIngredient").show();
        }
        $("#loadIngredient").click(function (e) {
            e.preventDefault();
            $(".IngredientLine:hidden").slice(0, 1).show(); // Updated this line
            if ($(".IngredientLine:hidden").length === 0) {
                alert('No more lines');
                $("#loadIngredient").hide();
            }
        });
        $(".InstructionLine").slice(0, 3).show();
        if ($(".InstructionLine:hidden").length !== 0) {
            $("#loadInstructions").show();
        }
        $("#loadInstructions").click(function (e) {
            e.preventDefault();
            $(".InstructionLine:hidden").slice(0, 1).show(); // Updated this line
            if ($(".InstructionLine:hidden").length === 0) {
                alert('No more lines');
                $("#loadInstructions").hide();
            }
        });
        $(".IngredientName").each(function () {
            $(this).autocomplete({
                source: function (request, response) {
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url: "{{ route('Recipe.create.fetch') }}",
                        type: "POST",
                        dataType: "json",
                        data: {'query': request.term, _token: _token, 'check': 'Product'},
                        success: function (data) {
                            response(data);
                        }
                    })
                }
            })
        });
        $("#urlDetail").hover(function () {
            $("#summary").toggle();
        });
        $("#DishType").autocomplete({
            source: function (request, response) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('Recipe.create.fetch') }}",
                    type: "POST",
                    dataType: "json",
                    data: {'query': request.term, _token: _token, 'check': 'Type'},
                    success: function (data) {
                        response(data);
                    }
                })
            }
        });
        $("#DietType").autocomplete({
            source: function (request, response) {
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "{{ route('Recipe.create.fetch') }}",
                    type: "POST",
                    dataType: "json",
                    data: {'query': request.term, _token: _token, 'check': 'DietType'},
                    success: function (data) {
                        response(data);
                    }
                })
            }
        });
        $("#photoUpload").click(function () {
            $("#photo").show();
        });
        $("#photoSourceUpload").click(function () {
            $("#urlPhoto").show();
        });
    });
</script>
<style>
    .ui-helper-hidden-accessible {
        display: none !important;
    }

    #urlDetail {
        cursor: pointer;
    }

    #summary {
        padding-left: 30px;
        background: white;
        z-index: 2;
        position: absolute;
        display: none;
    }

    #photo {
        display: none;
    }

    .IngredientLine {
        display: none;
    }

    .InstructionLine {
        display: none;
    }
</style>
