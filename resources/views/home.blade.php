@extends('app')
@section('ActionMenu')
    <div class="bg-light border-right" id="sidebar-wrapper">
        <div class="list-group list-group-flush">
            <div style="padding: 28px 16px 28px 16px" class="list-group-item list-group-item-action bg-light"></div>
            <a style="padding: 16px 16px 16px 16px" href="{{ route('invites') }}"
               class="list-group-item list-group-item-action bg-light">Invites</a>
            <a style="padding: 16px 16px 16px 16px" href="{{ route('CreateHousehold') }}"
               class="list-group-item list-group-item-action bg-light">Create household</a>
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
    <br>
    <h2> Households </h2>
    @if($house_count < 1)
        <p>You don't have any households yet.</p>
    @else
        <table id="myTable" class="col-sm-6 col-lg-4 table table-bordered" style="width: 80%">
            <thead>
            <tr>
                <th>Household Name</th>
                <th>Household owner</th>
            </tr>
            </thead>
            <tbody>
            @foreach($member as $mem)
                @foreach($houses as $house)
                    @if($mem->household_id == $house->id_Home and $house->removed == 0)
                        <tr class="trhover"
                            onClick="location.href='{{route('Household', ['id_house' => $house->id_Home])}}'">
                            <td>{{ $house->Name }}</td>
                            @foreach($allmembers as $m)
                                @if($m->household_id == $house->id_Home and $m->owner == 1)
                                    @foreach($owner as $own)
                                        @if($own->id == $m->users_id)
                                            <td>{{ $own->name }}</td>
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </table>
    @endif
@endsection

<style>
    .trhover:hover {
        text-decoration: underline;
        cursor: pointer;
        font-weight: bold;
        background-color: #ebebee;
    }
</style>
