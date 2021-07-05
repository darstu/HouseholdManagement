@extends('app')
@section('ActionMenu')
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">Action list</div>
        <div class="list-group list-group-flush">
            <a href="{{route('Favorites')}}" class="list-group-item list-group-item-action bg-light">Favorite
                recipes</a>
            <a href="{{route('CreateRecipe')}}" class="list-group-item list-group-item-action bg-light">Add new
                Recipe</a>
        </div>
    </div>
@endsection
@section('Content')
    <div class="container">
        <div class="row">
            <h2>Create review on</h2>
        </div>
        <div class="row">
            <div class="col-sm-1">
                <img
                    @if(file_exists(public_path('images/'.$recipe->Image_address)))
                    src="{{asset('/images/'.$recipe->Image_address)}}"
                    @else
                    src="{{$recipe->Image_address}}"
                    @endif
                    style="width: 100%">
            </div>
            <div class="col-sm-5 align-self-center">
                <p>{{$recipe->Name}}</p>
            </div>
        </div>
    </div>
    <form method="POST" action="{{route('Recipe.rate.save',$recipe->id_Recipe)}}" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div style="margin-top: 10px" class="row">
                <h4>Rating<span style="color: red">*</span></h4>
            </div>
            <div class="row rating">
                <label>
                    <input type="radio" name="stars" value="1"/>
                    <span class="icon">★</span>
                </label>
                <label>
                    <input type="radio" name="stars" value="2"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                </label>
                <label>
                    <input type="radio" name="stars" value="3"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                </label>
                <label>
                    <input type="radio" name="stars" value="4"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                </label>
                <label>
                    <input type="radio" name="stars" value="5"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                </label>
            </div>
            <br>
            <div class="row">
                <h4>Upload a photo</h4>
            </div>
            <div class="row">
                <input id="Image" type="file" name="Image" value="{{old('Image')}}">
            </div>
            <br>
            <div class="row">
                <h4>Headline<span style="color: red">*</span></h4>
            </div>
            <div class="row col-sm-6">
                <input type="text" class="form-control" name="Headline" value="{{old('Headline')}}">
            </div>
            <div class="row ">
                <h4>Feedback<span style="color: red">*</span></h4>
            </div>
            <div class="row col-sm-6">
                <textarea onclick="handler(event)" rows="5" placeholder="" name="Feedback"
                          class="form-control">{{old('Feedback')}}</textarea>
            </div>
            <div>
                <button onclick="handler(event)" type="submit">Save review</button>
            </div>
        </div>
    </form>
@endsection
<style>
    .rating {
        display: inline-block;
        position: relative;
        height: 50px;
        line-height: 50px;
        font-size: 50px;
    }

    .rating label {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        cursor: pointer;
    }

    .rating label:last-child {
        position: static;
    }

    .rating label:nth-child(1) {
        z-index: 5;
    }

    .rating label:nth-child(2) {
        z-index: 4;
    }

    .rating label:nth-child(3) {
        z-index: 3;
    }

    .rating label:nth-child(4) {
        z-index: 2;
    }

    .rating label:nth-child(5) {
        z-index: 1;
    }

    .rating label input {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
    }

    .rating label .icon {
        float: left;
        color: transparent;
    }

    .rating label:last-child .icon {
        color: #000;
    }

    .rating:not(:hover) label input:checked ~ .icon,
    .rating:hover label:hover input ~ .icon {
        color: #09f;
    }

    .rating label input:focus:not(:checked) ~ .icon:last-child {
        color: #000;
        text-shadow: 0 0 5px #09f;
    }
</style>
