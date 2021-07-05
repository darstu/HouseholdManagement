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
    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session()->get('error') }}
        </div>
    @endif
    <div class="managebar container-fluid">
        <a style="position: center" href="{{route('Household', ['id_house' => $house->id_Home])}}">
            <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor"
                 xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                      d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z"
                      clip-rule="evenodd"/>
            </svg>
        </a>
        <div class="row mt-4">
            <div class="col-8">
                <h1>Suppliers</h1>
            </div>
            <div class="col-4">
                <div class="btn-group float-right mt-2" role="group">
                    @if($permission_create->restricted == 1)
                    @else
                        <a class="btn btn-secondary " href="{{ route('CreateSupplier', ['Id' => $house->id_Home]) }}">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add new</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if($typesc == 0)
        @if($typ == 'removed')
            <p style="margin-top: -40px; padding-left: 15px">Household has no removed suppliers.</p>
        @elseif($typ != null)
            <p style="margin-top: -40px; padding-left: 15px">Household has no suppliers of this type.</p>
        @else
            <p style="margin-top: -40px; padding-left: 15px">Household has no suppliers.</p>
        @endif
    @else
        <div class="row" style="padding-top: 10px; padding-left: 15px; margin-top: -40px;">
            @if($typ == 'removed')
                <form method="POST"
                      action="{{Route('SortFilteredSuppliers', ['id_house' => $house->id_Home, 'type' => 'removed'])}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <span class="input-field">
                <label style="padding-left: 15px">Order by: </label>
                <select name="orderBy" id="orderBy" class="form-control" style="width: fit-content; display:inherit">
                    <option value="">-----------</option>
                    <option value="newest">Newest</option>
                    <option value="asc">Supplier Name</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="mygtukas"
                        style="display: inherit; margin-top: -5px">Sort</button>
            </span>
                    <br>
                </form>
                <div class="col-md">
                    <form style="float: right;"
                          action="{{ route('searchSupplier', ['Id' => $house->id_Home, 'filter_id' => 0]) }}"
                          method="GET">
                    @if($search != null)
                    <input  class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px" type="text" value="" name="search" placeholder="Search.." required/>
                        @else
                        <input class="searchIn"
                               style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey" type="text"
                               value="{{old('search')}}" name="search" placeholder="Search.." required/>
                        @endif
                        <button class="btn btn-secondary" style="height: 38px; vertical-align: bottom" type="submit"><i
                                class="fas fa-search"></i></button>
                    </form>
                </div>
        </div>
        @elseif($typ != null)
            <form method="POST"
                  action="{{Route('SortFilteredSuppliers', ['id_house' => $house->id_Home, 'type' => $typ->type_id])}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <span class="input-field">
                <label style="padding-left: 15px">Order by: </label>
                <select name="orderBy" id="orderBy" class="form-control" style="width: fit-content; display:inherit">
                    <option value="">-----------</option>
                    <option value="newest">Newest</option>
                    <option value="asc">Supplier Name</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="mygtukas"
                        style="display: inherit; margin-top: -5px">Sort</button>
            </span>
                <br>
            </form>
            <div class="col-md">
                <form style="float: right;"
                      action="{{ route('searchSupplier', ['Id' => $house->id_Home, 'filter_id' => $typ->type_id]) }}"
                      method="GET">
                @if($search != null)
                 <input  class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px" type="text" value="" name="search" placeholder="Search.." required/>
                    @else
                    <input class="searchIn"
                           style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey" type="text"
                           value="{{old('search')}}" name="search" placeholder="Search.." required/>
                    @endif
                    <button class="btn btn-secondary" style="height: 38px; vertical-align: bottom" type="submit"><i
                            class="fas fa-search"></i></button>
                </form>
            </div>
            </div>
        @else
            <div class="row mt-8 col-lg-12">
                <form method="POST" action="{{Route('SortSuppliers', ['id_house' => $house->id_Home])}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <span class="input-field">
                <label style="padding-left: 15px">Order by: </label>
                <select name="orderBy" id="orderBy" class="form-control" style="width: fit-content; display:inherit">
                    <option value="">-----------</option>
                    <option value="newest">Newest</option>
                    <option value="asc">Supplier Name</option>
                </select>
                <button type="submit" class="btn btn-secondary" id="mygtukas"
                        style="display: inherit; margin-top: -5px">Sort</button>
            </span>
                    <br>
                </form>
                <div class="col-md">
                    <form style="float: right;"
                          action="{{ route('searchSupplier', ['Id' => $house->id_Home, 'filter_id' => -1]) }}"
                          method="GET">
                        @if($search != null)
                            <input class="searchIn" style="height: 38px; border-style: none; border-bottom: 1px"
                                   type="text" value="" name="search" placeholder="Search.." required/>
                        @else
                            <input class="searchIn"
                                   style="height: 38px; border-style: none; border-bottom: 1px solid dimgrey"
                                   type="text"
                                   value="{{old('search')}}" name="search" placeholder="Search.." required/>
                        @endif
                        <button class="btn btn-secondary" style="height: 38px; vertical-align: bottom" type="submit"><i
                                class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
            @endif
            </div>
            <br>
            @if($typ == 'removed')
                <div class="row">
                    @foreach($homesupp as $supplier)
                        @if($supplier->removed == 1)
                            <div class="col-lg-4 col-sm-6">
                                <div class="card" style="max-width: 300px; margin-left: 10px">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $supplier->Name }} <a class="btn btn-primary" href="{{ route('activateSupplier', ['id_house' => $house->id_Home, 'Id' => $supplier->supplier_id]) }}">Activate</a></h5>
                                        @foreach($types as $type)
                                            @if($supplier->fk_type_id == $type->type_id)
                                                <p class="card-text" style="text-align: left">
                                                    Type: {{ $type->Name }} [ {{ $type->Description }} ]</p>
                                            @endif
                                        @endforeach
                                        @if($supplier->Address == null)
                                            <p class="card-text" style="text-align: left">Adresas: -------</p>
                                        @else
                                            <p class="card-text" style="text-align: left">
                                                Adresas: {{ $supplier->Address }}</p>
                                        @endif
                                        @if($supplier->City == null)
                                            <p class="card-text" style="text-align: left">City: -------</p>
                                        @else
                                            <p class="card-text" style="text-align: left">
                                                City: {{ $supplier->City }}</p>
                                        @endif
                                        <p class="card-text" style="text-align: left">Deactivated: {{$supplier->removed_date}}</p>
                                        <a class="btn btn-danger" href="{{ route('deleteSupplier', ['type_id' => $supplier->supplier_id]) }}">Delete</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @elseif($typ !=null)
                <div class="row">
                    @foreach($homesupp as $supplier)
                        @if($supplier->fk_type_id == $typ->type_id and $supplier->removed != 1)

                            <div class="col-lg-4 col-sm-6">
                                <div class="card" style="max-width: 300px; max-height: 300px; margin-left: 10px">
                                    <a href='{{route('Supplier', ['id_house' => $house->id_Home, 'Id' => $supplier->supplier_id])}}'>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $supplier->Name }}</h5>
                                            <p class="card-text" style="text-align: left">Type: {{ $typ->Name }}
                                                [ {{ $typ->Description }} ]</p>
                                            @if($supplier->Address == null)
                                                <p class="card-text" style="text-align: left">Adresas: -------</p>
                                            @else
                                                <p class="card-text" style="text-align: left">
                                                    Adresas: {{ $supplier->Address }}</p>
                                            @endif
                                            @if($supplier->City == null)
                                                <p class="card-text" style="text-align: left">City: -------</p>
                                            @else
                                                <p class="card-text" style="text-align: left">
                                                    City: {{ $supplier->City }}</p>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="row">
                    @foreach($homesupp as $supplier)
                        @if($supplier->removed == 0)
                            <div class="col-lg-4 col-sm-6">
                                <div class="card" style="width: 300px;">
                                    <a href='{{route('Supplier', ['id_house' => $house->id_Home, 'Id' => $supplier->supplier_id])}}'>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $supplier->Name }}</h5>
                                            @foreach($types as $type)
                                                @if($supplier->fk_type_id == $type->type_id)
                                                    <p class="card-text" style="text-align: left">
                                                        Type: {{ $type->Name }} [ {{ $type->Description }} ]</p>
                                                @endif
                                            @endforeach
                                            @if($supplier->Address == null)
                                                <p class="card-text" style="text-align: left">Adresas: -------</p>
                                            @else
                                                <p class="card-text" style="text-align: left">
                                                    Adresas: {{ $supplier->Address }}</p>
                                            @endif
                                            @if($supplier->City == null)
                                                <p class="card-text" style="text-align: left">City: -------</p>
                                            @else
                                                <p class="card-text" style="text-align: left">
                                                    City: {{ $supplier->City }}</p>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @endif
@endsection

<style>
    .hovering:hover {
        color: #0000fa;
        text-decoration: underline;
        text-underline-color: #0000fa;
        cursor: pointer;
    }
</style>


