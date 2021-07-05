@extends('app')
@section('ActionMenu')
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">Action list</div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action bg-light">Current household: {{session('houseName')}}</a>
            <a href="{{route('CreateRecipe')}}" class="list-group-item list-group-item-action bg-light">Add new
                recipe</a>
            <a href="{{route('Recipe.newVersion',$recipe)}}" class="list-group-item list-group-item-action bg-light">Add
                new version</a>
            @if(!$recipe->Favorites->count())
                <a href="{{route('Recipe.add.fav',$recipe->id_Recipe)}}"
                   class="list-group-item list-group-item-action bg-light">Add to favorites</a>
            @else
                <a href="{{route('Recipe.fav.delete',$recipe->id_Recipe)}}"
                   class="list-group-item list-group-item-action bg-light">Remove from favorites</a>
            @endif
            <a href="{{route('Recipe.rate',$recipe)}}"
               class="list-group-item list-group-item-action bg-light">Write review</a>
            {{--            <a href="#" class="list-group-item list-group-item-action bg-light">Report recipe</a>--}}
            @if(Auth::id()==$recipe->User->id)
                <a href="{{route('Recipe.edit',$recipe)}}" class="list-group-item list-group-item-action bg-light">Edit
                    recipe</a>
                <a onclick="return confirm('Do you want to remove recipe?')"
                   href="{{route('Recipe.delete',$recipe->id_Recipe)}}"
                   class="list-group-item list-group-item-action bg-light">Block Recipe</a>
            @endif
        </div>
    </div>
@endsection
@section('Content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @if(session()->has('warning'))
        <div class="alert alert-warning">
            {{ session()->get('warning') }}
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
    <div class="container">
        <div class="row">
            <div class="col-sm-8" style="margin-top: 10px">
                <h3>{{$recipe->Name}}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div>
                    <i class="fas fa-utensils"></i>
                    <h5 style="display: inline"><b>Dish type:</b></h5>
                    <p style="display: inline;margin-right: 20px"> {{ucfirst($recipe->Dish_type->Name)}}</p>
                </div>
                <div>
                    <i class="fas fa-seedling"></i>
                    <h5 style="display: inline"><b>Diet type:</b></h5>
                    <p style="display: inline">{{ucfirst($recipe->Diet_type->Name)}}</p>
                </div>
            </div>
            <br>
            <div class="col-sm-4">
                <div>
                    <div style="display: inline;margin-right: 20px ">
                        <i class="fas fa-clock"></i>&nbsp<h5 style="display: inline"><b>Cook:</b></h5>&nbsp
                        @if(($hours=substr($recipe->Cooking_time,0,2))=="00")
                            {{substr($recipe->Cooking_time,3,2)}} minutes
                        @elseif(($hours=substr($recipe->Cooking_time,0,1))=="0")
                            {{$hours=substr($recipe->Cooking_time,1,1)}} hours {{substr($recipe->Cooking_time,3,2)}}
                            minutes
                        @else
                            {{$hours}} hours {{substr($recipe->Cooking_time,3,2)}} minutes
                        @endif
                    </div>
                </div>
                <div style="font-size: 0.5rem; display: inline;vertical-align: top">
                    @if($recipe->Difficulty=="Easy")
                        <i class="fas fa-circle fa-lg mt-2"></i>
                    @elseif($recipe->Difficulty=="Medium")
                        <i class="fas fa-circle fa-xs"></i>
                        <i class="fas fa-circle fa-xs"></i>
                    @else
                        <i class="fas fa-circle fa-xs"></i>
                        <i class="fas fa-circle fa-xs"></i>
                        <i class="fas fa-circle fa-xs"></i>
                    @endif
                </div>
                <h5 style="display: inline"><b>Difficulty:</b></h5>
                <p style="display: inline">{{$recipe->Difficulty}}</p>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-8">
                <img
                    @if(file_exists(public_path('images/'.$recipe->Image_address)))
                    src="{{asset('/images/'.$recipe->Image_address)}}"
                    @else
                    src="{{$recipe->Image_address}}"
                    @endif
                    style="width:100%;max-height: 500px">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                @isset($recipe->Main)
                    <div><b>Version of <a href="{{route('Recipe',$recipe->fk_Main_Recipe)}}">{{$recipe->Main->Name}}</a></b>
                    </div>
                @endisset
            </div>
        </div>
        <div class="row">
            @if($recipe->fk_User)
                <span>Uploaded by <b>{{$recipe->User->name}}</b> &nbsp&nbsp&nbsp</span>
            @endif
            @if($recipe->Source)
                <span>Source: <a
                        href="{{$recipe->Source->Address}}"><b>{{$recipe->Source->Name}}</b></a> &nbsp&nbsp&nbsp</span>
        </div>
        <div class="row">
            @endif
            <p>Rated &nbsp&nbsp&nbsp </p>
            <span>
                        @for($i=0.5;$i<$rate;$i++)
                    <i class="fas fa-star"></i>
                @endfor
                @if($rate * 2 % 2==1)
                    <i class="fas fa-star-half-alt"></i>
                    @for($i=$rate;$i<4;$i++)
                        <i class="far fa-star"></i>
                    @endfor
                @else
                    @for($i=$rate;$i<5;$i++)
                        <i class="far fa-star"></i>
                    @endfor
                @endif
                            </span>
            <span>&nbsp&nbsp&nbsp From {{$rateCount}} reviews </span>
        </div>
        <div class="row">
            <div><h5><b>Description</b></h5></div>
        </div>
        <div class="row">
            <div class="col-sm-8" style="margin-bottom: 10px">{{$recipe->Description}}</div>
        </div>
        <div class="row">
            <div><h5><b>Servings count</b></h5></div>
        </div>
        <div class="row">
            <div class="col-sm-8" style="margin-bottom: 10px">
                <input id="calcInput" type="number" style="max-width: 40px" min="1"
                       value="{{$recipe->Servings_count}}">
                <button id="calculate" class="btn btn-secondary">Calculate</button>
            </div>
        </div>
        <div class="row">
            <div><h5><b>Ingredients</b></h5></div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <button onclick="original()" class="btn btn-secondary" style="max-width: 30%">Original</button>
                <button onclick="metric()" class="btn btn-secondary">Metric</button>
                <div class="card" style="text-align:left;cursor:default;margin-bottom: 20px">
                    <div class="card-body">
                        @foreach($recipe->Recipe_Ingredient as $key => $ingredient)
                            <div>
                                <span>{{$ingredient->Product->Name}} </span>
                                @isset($recipeIng)
                                    @isset($originalIng)
                                        <span style="font-weight: bold"> {{$originalIng[$key]->Amount}} </span>
                                    @else
                                        <span style="font-weight: bold"> {{$recipeIng[$key]->Amount}} </span>
                                    @endisset
                                    @if($metric===0)
                                        <span style="font-weight: bold">{{$ingredient->Unit->Name}},</span>
                                    @else
                                        <span style="font-weight: bold">{{$recipeIng[$key]->Unit->Name}},</span>
                                    @endif
                                @else
                                    <span style="font-weight: bold"> {{$ingredient->Amount}} </span>
                                    <span style="font-weight: bold">{{$ingredient->Unit->Name}},</span>
                                @endisset
                                @if(isset($recipe->missing)&&$recipe->missing->contains('id',$ingredient->fk_Product))
                                    @php
                                        $item=$recipe->missing->first(function ($item)use ($ingredient){return $item['id']==$ingredient->fk_Product;})
                                    @endphp
                                    @if($item['unknown']===1)
                                        <span style="color: red;cursor: pointer;" data-toggle="modal"
                                              data-target="#mappingModal-{{$ingredient->fk_Product}}">Unknown</span>
                                        <div id="mappingModal-{{$ingredient->fk_Product}}" class="modal fade"
                                             role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Product mapping</h4>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            &times;
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('Product.Confirm.Mapping')}}"
                                                              method="post">
                                                            @csrf
                                                            <input type="hidden" value="true" name="fromRecipe">
                                                            <div>
                                                                <label for="product"><b>Product</b></label>
                                                                <input class="form-control"
                                                                       value="{{$ingredient->Product->Name}}" readonly
                                                                       style="cursor: default">
                                                                <input type="hidden" name="product[]"
                                                                       value="{{$ingredient->Product->id_Product}}">
                                                            </div>
                                                            <div>
                                                                <label for="stockCard" style="margin-top: 20px"><b>Stock
                                                                        card</b></label>
                                                                <select class="form-control" name="stockCard[]"
                                                                        required>
                                                                    <option value="">-------</option>
                                                                    @foreach($stockCards as $card)
                                                                        <option
                                                                            value="{{$card->id_Stock_card}}">{{$card->Name}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <button class="btn btn-primary" type="submit"
                                                                    style="width: 40px; height: 40px;margin-top: 5px; float: right">
                                                                <i class="fas fa-check"></i></button>
                                                        </form>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default"
                                                                data-dismiss="modal">Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span style="color: red">Missing</span>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                        @if(isset($recipe->missing)&&count($recipe->missing)>0)
                            @php $check=$recipe->missing->contains('unknown',1);
                            @endphp
                            <input type="hidden" value="{{$check}}" id="check">
                            <button id="generatePurchase" class="btn btn-secondary">Add missing to purchase offer
                            </button>
                        @elseif($recipe->AbilityToMake===0)
                            @php $check=false;
                            @endphp
                            <input type="hidden" value="{{$check}}" id="check">
                            <button id="useComponents" class="btn btn-secondary">Use Components</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div><h5><b>Method</b></h5></div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="card" style="text-align:left;cursor:default;margin-bottom: 20px">
                    <div class="card-body">
                        @foreach($recipe->Cooking_instruction_step as $step)
                            <div class="row">
                                <b>Step&nbsp{{$step->Step_number}}</b>
                            </div>
                            <div class="row">
                                <p style="padding-left: 20px">{{$step->Step_Description}}</p>
                            </div>
                            @isset($step->Image_address)
                                <div class="row">
                                    @if(file_exists(public_path('images/'.$step->Image_address)))
                                        <img src="{{asset('/images/'.$step->Image_address)}}">
                                    @endif
                                </div>
                            @endisset

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div><h5><b>Reviews</b></h5></div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="card" style="text-align:left;cursor:default;margin-bottom: 20px">
                    <div class="card-body">
                        @if($recipe->Rating->count())
                            @foreach($recipe->Rating as $review)
                                <div style="border:1px solid black">
                                    <div class="row">
                                        <b style="padding-left: 20px">by</b>&nbsp
                                        <b>{{$review->User->name}}</b>&nbsp
                                        @for($i=0.5;$i<$review->Rating;$i++)
                                            <i class="fas fa-star"></i>
                                        @endfor
                                        @if($review->Rating * 2 % 2==1)
                                            <i class="fas fa-star-half-alt"></i>
                                            @for($i=$review->Rating;$i<4;$i++)
                                                <i class="far fa-star"></i>
                                            @endfor
                                        @else
                                            @for($i=$review->Rating;$i<5;$i++)
                                                <i class="far fa-star"></i>
                                            @endfor
                                        @endif
                                    </div>
                                    <div class="row">
                                        <b style="padding-left: 20px">{{$review->Headline}}</b>
                                    </div>
                                    <div class="row">
                                        <p style="padding-left: 20px">{{$review->Feedback}}</p>
                                    </div>
                                    @isset($review->Image_address)
                                        <div class="row">
                                            @if(file_exists(public_path('images/'.$review->Image_address)))
                                                <img style="padding-left: 20px"
                                                     src="{{asset('/images/'.$review->Image_address)}}">
                                            @endif
                                        </div>
                                    @endisset
                                </div>
                            @endforeach
                        @else
                            <h2>No Ratings yet</h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div><h5><b>Comments section</b></h5></div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <div class="card" style="text-align:left;cursor:default;margin-bottom: 20px">
                    <div class="card-body">
                        @if($recipe->Comment->count())
                            @include('RecipeManagement.Partials.Comment_Replies', ['comments' => $recipe->Comment, 'id_Recipe' => $recipe->id_Recipe])
                        @else
                            <h2>No comments yet</h2>
                        @endif
                        <hr/>
                        <h5>Write a comment</h5>
                        <form method="post"
                              action="{{ route('Recipe.add.comment',['id_Recipe' => $recipe->id_Recipe,'id_Comment' => 0]) }}">
                            @csrf
                            <div class="form-group">
                                <input type="text" name="Text" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-warning" value="Add Comment"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    function original() {
        window.location.href = "{{route('Recipe.calculateServings',['id_Recipe'=>$recipe->id_Recipe,'calc'=>$recipe->Servings_count])}}";
    }

    function metric() {
        if (window.location.href.indexOf('calc') > -1) {
            window.location.href = "{{route('Recipe.calculate.metric',['recipe'=>$recipe,'calc'=>$recipe->Servings_count])}}"
        } else {
            window.location.href = "{{route('Recipe.calculate.metric',['recipe'=>$recipe,'calc'=>0])}}"
        }
    }

    function mapping(product, e) {
        e.preventDefault()
        alert('mappingModal-' + product)
        $('mappingModal-' + product).modal('toggle');
    }

    $(document).ready(function () {
        $('#generatePurchase').click(function () {
            let con = false;
            let check = $("#check").val();
            if (check) {
                con = confirm('Missing stock cards, create them?')
            } else {
                con = true;
            }
            if (con) {
                window.location.href = "{{route('Recipe.GeneratePurchaseOffer',['id_Recipe'=>$recipe->id_Recipe,'calc'=>$recipe->Servings_count])}}";
            }
        })
        $('#useComponents').click(function () {
            window.location.href = "{{route('Recipe.Use.Components',['id_Recipe'=>$recipe->id_Recipe,'calc'=>$recipe->Servings_count])}}";
        })
        $('#calculate').click(function () {
            const calc = $('#calcInput').val();
            if (window.location.href.indexOf('metric') > -1) {
                let uri = "{{route('Recipe.calculate.metric',['recipe'=>$recipe,'calc'=>':calc'])}}"
                uri = uri.replace(':calc', calc);
                window.location.href = uri;
            } else {
                let uri = "{{route('Recipe.calculateServings',['id_Recipe'=>$recipe->id_Recipe,'calc'=>':calc'])}}";
                uri = uri.replace(':calc', calc);
                window.location.href = uri;
            }
        })
    });
</script>

