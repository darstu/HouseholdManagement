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
    <?php
    $count = 0;
    ?>
    <a style="position: center" href="{{route('home')}}">
        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
        </svg>
    </a>
    <br>
    <h3 style="padding-left: 15px">Invites</h3>
    <br>
    @foreach($invites as $invite)
        <?php
        $count2 = 0;
        ?>
        @foreach($homes as $home)
            @if($invite->fk_household_id == $home->id_Home and $home->removed == 1)
                <?php
                $count2 = $count2 + 1;
                ?>
            @endif
        @endforeach
        @if($count2 == 0)
            @if($invite->fk_receiver_id == Auth::user()->id)
                <?php
                $count = $count + 1;
                ?>
                <div class="col-lg-7 col-sm-12">
                    <div class="card">
                        <table class="table table-hover table-responsive">
                            <thead>
                            <tr style="border-bottom: 0px">
                                <th style="border-bottom: 10px;">Household name</th>
                                <th style="border-bottom: 10px;">Household Owner</th>
                                <th style="border-bottom: 10px;">Message</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                @foreach($homes as $home)
                                    @if($invite->fk_household_id == $home->id_Home)
                                        <td>{{ $home->Name }}</td>
                                        @foreach($allmembers as $m)
                                            @if($m->household_id == $invite->fk_household_id and $m->owner == 1)
                                                @foreach($owner as $own)
                                                    @if($own->id == $m->users_id)
                                                        <td>{{ $own->name }}</td>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                                @if($invite->Message == null)
                                    <td>---------</td>
                                @else
                                    <td>{{ $invite->Message }}</td>
                                @endif

                                <?php
                                $call='accept'
                                ?>
                                <th style="width: 3%"><button class="btn btn-success"><a href="{{route('deleteInvite', [$invite->invite_id, $call])}}" style="color: white">Accept</a></button></th>
                                <?php
                                $call='delete'
                                ?>
                                <td style="width: 3%"><button class="btn btn-danger"><a onclick="return confirm('Do you really want to cancel this invite?')" href="{{route('deleteInvite', [$invite->invite_id, $call])}}" style="color: white" >Cancel</a></button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
            @endif
        @endif
    @endforeach
    @if($count < 1)
        <p>You don't have any invites.</p>
    @endif
@endsection
