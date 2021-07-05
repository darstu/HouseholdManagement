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
    <a style="position: center" href="{{route('home')}}">
        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
        </svg>
    </a>
    <br>
    <h3 style="padding-left: 15px"> Household "{{ $house->Name }}" information:</h3>
        <div class="col-lg-6 col-sm-8">
            <div class="card">
                <table class="table table-condensed">
                    <tr style="border-bottom: 0px">
                        <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 30px">Household Name:</th>
                        <td>{{ $house->Name }}</td>
                    </tr>
                    <tr style="border-bottom: 0px">
                        <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 30px">Created:</th>
                        <td>{{ $house->created_date }}</td>
                    </tr>
                    <tr>
                        <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 30px">Household owner:</th>
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
                    <tr>
                        <th style="width:50%;border-bottom: 10px; text-align: left; padding-left: 30px">Address:</th>
                        <td>{{ $house->Address }}</td>
                    </tr>
                    <tr>
                        <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 30px">Alternative Address:</th>
                        @if($house->Alternative_address == null)
                            <td>----</td>
                        @else
                            <td>{{ $house->Alternative_address }}</td>
                        @endif
                    </tr>
                    <tr>
                        <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 30px">City:</th>
                        <td>{{ $house->City }}</td>
                    </tr>
                    <tr>
                        <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 30px">Phone:</th>
                        @if($house->Phone == null)
                            <td>----</td>
                        @else
                            <td>{{ $house->Phone }}</td>
                        @endif
                    </tr>
                    <tr>
                            <th style="width:10%;border-bottom: 10px; text-align: left; padding-left: 30px">Members:</th>
                            <td>{{ $memc }}</td>
                    </tr>
                </table>
            </div>
        </div>
@endsection

