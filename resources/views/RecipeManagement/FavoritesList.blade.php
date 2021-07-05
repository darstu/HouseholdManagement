@extends('app')
@section('ActionMenu')
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="sidebar-heading">Search by category</div>
        <div class="list-group list-group-flush">
            <a href="{{route('Favorites')}}" class="list-group-item list-group-item-action bg-light">All
                categories</a>
            @foreach($categories as $category)
                @if($loop->first)
                    <a href="{{route('Favorites.Filter',['category'=>$category->id_Category,'check'=>0])}}"
                       class="list-group-item list-group-item-action bg-light">No category</a>
                @endif
                <a href="{{route('Favorites.Filter',['category'=>$category->id_Category,'check'=>1])}}"
                   class="list-group-item list-group-item-action bg-light">{{$category->Name}}
                    <i onclick="deleteCategory({{$category->id_Category}},'{{$category->Name}}',event)"
                       class="far fa-trash-alt"
                       style="float: right;z-index: 1;margin-left: 5px"></i>
                    <i onclick="openEditCategoryOverlay({{$category->id_Category}},'{{$category->Name}}',event)"
                       class="far fa-edit"
                       style="float: right;z-index: 1"></i>
                </a>
            @endforeach
        </div>
        <div class="list-group list-group-flush">
            <form method="get" action="{{route('Favorites.Add.Category')}}">
                <li class="list-group-item list-group-item-action bg-light">
                    <label for="Category">Create new category</label>
                    <input id="cat" class="form-control" type="text" name="Category">
                    <button id="submitCat" type="submit" class="btn btn-primary" style="margin-top: 10px" disabled>
                        Create
                    </button>
                </li>
            </form>
        </div>
    </div>
@endsection
@section('Content')
    @if(!(empty($Warning)))
        <div class="alert alert-warning">
            {{$Warning}}
        </div>
    @endif
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
    <div class="container-fluid" style="border:1px solid #cecece;padding-bottom: 10px">
        <div class="row">
            @foreach($recipes as $index=>$recipe)
                <div class="card" style="margin: 10px 0 0 10px; cursor: default">
                    <div class="row">
                        <div class="col-xs-1">
                            <a onclick="openCategoryOverlay('categoryModal-{{$recipe->id_Recipe}}')"
                               style="cursor: pointer">
                                <div style="padding: 8px 0 0 20px"><i class="fas fa-folder-plus fa-2x"></i></div>
                            </a>
                            <a onclick="return confirm('Do you want to remove item from favorite recipe list?')"
                               href="{{route('Recipe.fav.delete',$recipe->id_Recipe)}}">
                                <div style="padding: 8px 0 0 20px"><i class="fas fa-trash-alt fa-2x"></i></div>
                            </a>
                        </div>
                        <a href="{{route('Recipe',$recipe->id_Recipe)}}"
                           style="color:inherit;font-size:20px;padding: 10px 20px 0 5px">
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
                                    <p style="margin:auto">{{$recipe->Name}}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div id="categoryModal-{{$recipe->id_Recipe}}" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Pick category for {{$recipe->name}}</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <form action="{{route('Favorites.AddToCategory',$recipe->id_Recipe)}}" class="d-flex">
                                    <select id="id_Category" class="form-control" name="id_Category" required>
                                        @foreach($categories as $category)
                                            @isset($favRecipes[$index]->Category)
                                                <option value="{{$category->id_Category}}"
                                                    {{ $category->id_Category==$favRecipes[$index]->Category->id_Category ? 'selected' : '' }}>{{$category->Name}}</option>
                                            @else
                                                @if($loop->first)
                                                    <option value="" selected>Choose..</option>
                                                @endif
                                                <option value="{{$category->id_Category}}">{{$category->Name}}</option>
                                            @endisset

                                        @endforeach
                                    </select>
                                    <button class="btn-primary" type="submit" style="width: 50px"><i
                                            class="fas fa-check"></i></button>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div style="float: right; margin-top: 10px">{{$recipes->appends($_GET)->links()}}</div>
    <div id="editCategoryModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Change category name</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{route('Favorites.Edit.Category',$recipe->id_Recipe)}}" class="d-flex" method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <input class="form-control" type="text" name="name" required id="editableName">
                        <input type="hidden" name="id" id="editableID">
                        <button class="btn-primary" type="submit" style="width: 50px"><i
                                class="fas fa-check"></i></button>
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
    function openCategoryOverlay(id) {
        $("#" + id).modal('toggle')
    }

    function openEditCategoryOverlay(id, name, e) {
        e.preventDefault()
        const modal = $('#editCategoryModal');
        modal.modal('toggle');
        $('#editableName').val(name);
        $('#editableID').val(id);
    }

    function deleteCategory(id, name, e) {
        e.preventDefault();
        let str = 'Do you want to delete category - :name?';
        str = str.replace(':name', name);
        if (confirm(str)) {
            let uri = "{{ route('Favorites.Delete.Category',['id_Category' => ':id'])}}"
            uri = uri.replace(':id', id);
            window.location.href = uri;
        }
    }

    $(document).ready(function () {
        const category = $('#cat'),
            submitCat = $('#submitCat');
        category.on('keyup', function () {
            if (category.val()) {
                submitCat.prop('disabled', false);
            } else {
                submitCat.prop('disabled', true);
            }
        });
    });
</script>
