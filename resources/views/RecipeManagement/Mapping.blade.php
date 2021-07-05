@extends('app')
@section('ActionMenu')
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">Action list</div>
        <div class="list-group list-group-flush">
            <a id="openSearch" class="list-group-item list-group-item-action bg-light">Search</a>
            <a href="{{route('Favorites')}}" class="list-group-item list-group-item-action bg-light">Favorite
                recipes</a>
            <a href="{{route('CreateRecipe')}}" class="list-group-item list-group-item-action bg-light">Add new
                recipe</a>
            @if(session('MultipleHouseholds'))
                <a onclick="openOverlay(0)" class="list-group-item list-group-item-action bg-light">Change household</a>
            @endif
        </div>
    </div>
@endsection
@section('Content')
    <div class="container" style="margin-top: 20px; text-align: center">
        <h1>Product mapping</h1>
        <h4>Check or edit the products mapping and confirm</h4>
        <form action="{{route('Product.Confirm.Mapping')}}" method="post">
            @csrf
            <div class="card" style="margin-bottom: 10px; cursor: default;overflow: auto;max-height: 60vh;">
                <div class="card-body">
                    @foreach($mapping as $map)
                        @php
                            $item=$products->first(function ($item)use ($map){return $item['id_Product']==$map['product']->id_Product;});
                            $stock=$stockCards->first(function ($item) use($map){return $item['id_Stock_card']==$map['stockCard']->id_Stock_card;});
                        @endphp
                        <div id="map-{{$item->id_Product}}-{{$stock->id_Stock_card}}">
                            <label for="product"><b>Product</b></label>
                            <input class="form-control" value="{{$item->Name}}" readonly
                                   style="cursor: default;max-width: 300px;display: inline">
                            <input type="hidden" name="product[]" value="{{$item->id_Product}}">
                            <label for="stockCard"><b>Stock card</b></label>
                            <select class="form-control" name="stockCard[]" style="max-width: 300px;display: inline">
                                <option value="" style="display: none">-------</option>
                                @foreach($stockCards as $card)
                                    <option
                                        value="{{$stock->id_Stock_card}}" {{$stock == $card ? 'selected' : ''}}>{{$card->Name}}
                                    </option>
                                @endforeach
                            </select>
                            <i onclick="removeLine('map-{{$item->id_Product}}-{{$stock->id_Stock_card}}')"
                               class="fas fa-times"></i>
                        </div>
                    @endforeach
                </div>
            </div>
            <button onclick="return confirm('Complete product mapping?')" class="btn btn-primary" style="float: right">
                Confirm
            </button>
        </form>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    function removeLine(id) {
        var div = $('#' + id);
        div.find('input:hidden').val('');
        div.find("select").each(function () {
            this.selectedIndex = 0
        });
        div.hide();
    }
</script>
