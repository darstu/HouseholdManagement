@extends('app')
@section('ActionMenu')
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">Action list</div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action bg-light">Current household: {{session('houseName')}}</a>
            <a class="list-group-item list-group-item-action bg-light" data-toggle="modal" data-target="#searchModal">Search</a>
            <a href="{{route('Favorites')}}" class="list-group-item list-group-item-action bg-light">Favorite
                recipes</a>
            <a href="{{route('CreateRecipe')}}" class="list-group-item list-group-item-action bg-light">Add new
                recipe</a>
            @if(session('houseID'))
                {{--                <a href="#" class="list-group-item list-group-item-action bg-light">Clean up</a>--}}
                <a href="{{route('Product.Mapping')}}" class="list-group-item list-group-item-action bg-light">Map
                    products with stock</a>
                <a onclick="return confirm('Do you want to remove product mappings')"
                   href="{{route('Product.Mapping.Clear')}}" class="list-group-item list-group-item-action bg-light">Clear
                    product mapping</a>
            @endif
            @if(session('MultipleHouseholds'))
                <a onclick="openOverlay(0)" class="list-group-item list-group-item-action bg-light">Change household</a>
            @endif
        </div>
        <div class="sidebar-heading">
            <h5 style="display: inline;padding-right: 30px">Filters</h5>
        </div>
        <div class="list-group list-group-flush">
            <form action="{{route('Recipes.filter')}}" method="get">
                <li class="list-group-item list-group-item-action bg-light">
                    <p>Dish type</p>
                    <div style="overflow: auto;max-height: 100px">
                        @foreach($types as $type)
                            <div><input type="checkbox" name="type[]" value="{{$type->id_Dish_type}}"
                                    {{ (is_array(old('type')) and in_array($type->id_Dish_type, old('type'))) ? ' checked' : '' }}> {{$type->Name}}
                            </div>
                        @endforeach
                    </div>
                </li>
                <li class="list-group-item list-group-item-action bg-light">
                    <p>Diet type</p>
                    <div style="overflow: auto;max-height: 100px">
                        @foreach($dietTypes as $type)
                            <div><input type="checkbox" name="diet[]" value="{{$type->id_Diet_type}}"
                                    {{ (is_array(old('diet')) and in_array($type->id_Diet_type, old('diet'))) ? ' checked' : '' }}> {{$type->Name}}
                            </div>
                        @endforeach
                    </div>
                </li>
                <li class="list-group-item list-group-item-action bg-light">
                    <p>Servings count</p>
                    <input id="slider" name="slider" type="range" min="0" max="30" value="{{old('slider',0)}}">
                    <input id="sliderVal" type="number" min="0" max="30" value="{{old('slider',0)}}">
                </li>
                <li class="list-group-item list-group-item-action bg-light">
                    <p>More filters</p>
                    <div><input type="checkbox" name="Ability" value="true"
                            {{ old('Ability')=='true' ? ' checked' : '' }}> Able to make
                    </div>
                    <div><input type="checkbox" name="Visibility" value="true"
                            {{ old('Visibility')=='true' ? ' checked' : '' }}> Private recipes
                    </div>
                </li>
                <li class="list-group-item list-group-item-action bg-light">
                    <button id="submitbtn" type="submit" class="btn btn-primary">Apply</button>
                    <button type="button" class="btn btn-secondary" id="clear">Clear filters</button>
                </li>
            </form>
        </div>
    </div>
@endsection
@section('Content')
    @isset($Warning)
        <div class="alert alert-warning">{{ $Warning }}</div>
    @endisset
    @if(session()->has('Warning'))
        <div class="alert alert-warning">{{ session()->get('Warning') }}</div>
    @endif
    @if(session()->has('success'))
        <div class="alert alert-success">{{ session()->get('success') }}</div>
    @endif
    <div class="container-fluid" style="border:1px solid #cecece; padding-bottom: 15px">
        <div class="row">
            @foreach($recipes as $recipe)
                <div class="card" style="margin: 10px 0 0 10px;cursor: default;">
                    <a href="{{route('Recipe', ['id_Recipe' => $recipe->id_Recipe])}}"
                       style="color:inherit;font-size:20px;padding: 10px 5px 0 5px">
                        <div class="col-sm-5" style="max-width: 300px;">
                            <div class="row">
                                <div><img
                                        @if(file_exists(public_path('images/'.$recipe->Image_address)))
                                        src="{{asset('/images/'.$recipe->Image_address)}}"
                                        @else
                                        src="{{$recipe->Image_address}}"
                                        @endif
                                        alt="" style="width:300px;height: 300px"></div>
                            </div>
                            <div class="row">
                                <p style="margin: auto">{{$recipe->Name}}</p>
                            </div>
                            <div class="row">
                                @if($recipe->AbilityToMake===-1)
                                    <p style="color: red;margin: auto">Can't calculate</p>
                                @elseif($recipe->AbilityToMake===0)
                                    <p style="color: green;margin: auto">Able to make</p>
                                @else
                                    <p style="color: red;margin: auto">Missing {{$recipe->AbilityToMake}}
                                        ingredients</p>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <div style="float: right; margin-top: 10px">{{$recipes->appends($_GET)->links()}}</div>
    <div id="searchModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Search</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{route('RecipeSearch')}}" class="d-flex">
                        <input class="form-control" type="text" placeholder="Search.." name="search">
                        <button class="btn btn-primary" type="submit" style="width: 50px"><i class="fa fa-search"></i>
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
<script>
    function handler(event) {
        event.stopPropagation();
    }

    $(document).ready(function () {

        const checkboxes = $("input[type='checkbox']"),
            submitButt = $("#submitbtn"),
            searchOverlay = $('#searchOverlay'),
            clear = $("#clear"),
            slider = $('#slider, #sliderVal');
        const filt = window.location.href.indexOf("filter")

        clear.hide();
        if (filt < 0) {
            checkboxes.prop('checked', false);
            slider.val(0);
        }
        checkboxes.click(function () {
            var atLeastOneIsChecked = checkboxes.is(':checked');
            if (atLeastOneIsChecked) {
                clear.show();
            } else {
                clear.hide();
            }
        });
        slider.on('change', function () {
            slider.not(this).val(this.value);
        });
        if (checkboxes.is(':checked')) {
            clear.show();
        }
        clear.on('click', function () {
            checkboxes.prop('checked', false);
            slider.val(0);
            if (filt > -1) {
                window.location.href = "{{ route('RecipeList')}}"
            }
        });
        $('#openSearch').click(function () {
            searchOverlay.show();
        });
        searchOverlay.click(function () {
            searchOverlay.hide();
        });
    });

</script>
<style>
    input[type="number"] {
        max-width: 40px;
    }
</style>
