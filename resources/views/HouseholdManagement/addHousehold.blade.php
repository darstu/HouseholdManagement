@extends('app')

@section('ActionMenu')
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="list-group list-group-flush">
            <div style="padding: 28px 16px 28px 16px" class="list-group-item list-group-item-action bg-light"> </div>
            <a style="padding: 16px 16px 16px 16px" href="{{ route('invites') }}" class="list-group-item list-group-item-action bg-light">Invites</a>
            <a style="padding: 16px 16px 16px 16px" href="{{ route('CreateHousehold') }}" class="list-group-item list-group-item-action bg-light">Create household</a>
        </div>
    </div>
@endsection

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
    <a style="position: center" href="{{route('home')}}">
        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
        </svg>
    </a>
    <br>
    <h3>Create household</h3>
    <br>
    <div class="card col-lg-6 col-sm-8" style="padding-top: 15px">
    <form role="form" method="POST" action="{{route('addHousehold')}}" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label for="inputName" class="lineForHouse col-4">Household Name *</label>
            <input type="text" class="form-control col-7" id="inputName" name="Name" value="{{old('Name')}}">
        </div>
        <div class="form-group row">
            <label for="inputAddress" class="lineForHouse col-4">Address *</label>
            <input type="text" class="form-control col-7" id="inputAddress" name="Address" value="{{old('Address')}}">
        </div>
        <div class="form-group row">
            <label for="inputPhone" class="lineForHouse col-4">Phone number</label>
            <input type="text" class="form-control col-7" id="inputPhone" name="Phone" value="{{old('Phone')}}">
        </div>
        <div class="form-group row">
            <label for="inputAlternativeAddress" class="lineForHouse col-4">Alternative address</label>
            <input type="text" class="form-control col-7" id="inputAlternativeAddress" name="Alternative_address" value="{{old('AlternativeAddress')}}">
        </div>
        <div class="form-group row">
            <label for="inputCity" class="lineForHouse col-4">City *</label>
            <input type="text" class="form-control col-7" id="inputCity" name="City" value="{{old('City')}}" >
        </div>
        <div style="padding-left: 15px">
            <button type="submit" class="btn btn-info">Save Household</button>
        </div>
    </form>
<br>
        <p class="requiredfield">* Required fields</p>
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
