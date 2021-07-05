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
    <a style="position: center" href="{{URL::previous()}}">
        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
        </svg>
    </a>
    <br>
    <h4>Add resource type</h4>
    <br>
    <div class="card col-lg-6 col-sm-8" style="padding-top: 20px">
        <form role="form" method="POST" action="{{route('addStockType', ['Id' => $house->id_Home, 'title' => $title])}}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <label for="inputName" class="lineForHouse col-4">Name *</label>
                <input type="text" class="form-control col-7" id="inputName" name="Type_name" value="{{old('Type_name')}}">
            </div>
            <div class="form-group row">
                <label for="inputDescription" class="lineForHouse col-4">Description</label>
                <input type="text" class="form-control col-7" id="inputDescription" name="Type_description" value="{{old('Type_description')}}">
            </div>
            <p class="requiredfield">* Required fields</p>
            <div style="padding-left: 15px">
                <button type="submit" class="btn btn-info">Add type</button>
            </div>
        </form>
    </div>
@endsection
<style>
    .lineForHouse {
        font-weight: bold;
        text-align: left;
        float: left;
        margin-left: 15px;
    }
    .requiredfield {
        text-align: left;
        float: left;
        margin-left: 15px;
    }
</style>
