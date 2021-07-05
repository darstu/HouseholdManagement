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
    <a style="position: center" href="{{route('Household', ['id_house' => $house->id_Home])}}">
        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
        </svg>
    </a>
    <br>
    <a class="btn btn-secondary " style="float: right" href="{{ route('inviteMember', ['Id' => $house->id_Home]) }}">
        <i class="fa fa-plus" aria-hidden="true"></i> Invite new member</a>
    @if($countmembers > 1)
    <div class="card"  style="width:fit-content; float: left; padding-left: 20px; padding-right: 20px; margin-left: 20px">
        <table class="table table-hover table-condensed">
            <thead>
            <tr style="border-bottom: 0px">
                <th style="width:2%;border-bottom: 10px;">ID</th>
                <th style="width:30%;border-bottom: 10px;">Member</th>
                <th style="width:30%;border-bottom: 10px; text-align: left;">Permissions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($allmembers as $member)
                @if($member->users_id != Auth::user()->id)
                    @foreach($users as $user)
                        @if($member->users_id == $user->id and $user->removed != 1)
                            <?php
                                $countingp = 0;
                            ?>
                            <tr>
                                <td style="width:2%">{{$user->id}}</td>
                                <td style="width:20%">{{$user->name}} {{$user->surname}}</td>
                                <td style="width:30%; text-align: left; padding: 15px">
                                    Read;
                                    @foreach($user_perm as $p)
                                        <?php
                                        $countingp = $countingp + 1;
                                        ?>
                                        @if($p->fk_permission_id == 7 and $p->restricted != 1 and $p->fk_user_id == $user->id)
                                            Create;
                                        @elseif($p->fk_permission_id == 8 and $p->restricted != 1 and $p->fk_user_id == $user->id)
                                            Edit;
                                        @elseif($p->fk_permission_id == 9 and $p->restricted != 1 and $p->fk_user_id == $user->id)
                                            Delete;
                                        @endif
                                    @endforeach
                                </td>
                                <td style="width:8%"><a class="btn btn-primary" href="{{ route('MembersEdit', ['Id' => $house->id_Home, 'user' => $user->id]) }}">
                                        Edit</a></td>
                            </tr>
                        @endif
                    @endforeach
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p>Namų ūkis neturi narių</p>
    @endif
@endsection
