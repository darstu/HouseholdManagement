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
    <a style="position: center" href="{{ route('manageHouseholdMembers', ['Id' => $house->id_Home]) }}">
        <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
        </svg>
    </a>
    <br>
    <h3 style="margin-left: 25px">{{$userinfo->name}} {{$userinfo->surname}} permissions:
        <button class="btn btn-danger" style="float: right" type="delete"><a onclick="return confirm('Do you really want to remove this member from household?')" href="{{route('removeMember', ['Id' => $house->id_Home, 'user' => $userinfo->id])}}" style="color: white" >Remove Member</a></button></h3>

    <div class="row col-12">
        <div class="row col-4" style="float: left">
            <div>
                <div class="card"  style="margin-top: 2%; margin-left: 4%; margin-right: 4%; margin-bottom: 2%; float: left">
                    <table>
                        @foreach($user_perm as $perm)
                            @if($perm->fk_user_id == $userinfo->id)
                                @foreach($perms as $p)
                                    @if($p->permission_id == $perm->fk_permission_id)
                                        <tr>
                                            @if($p->name == "restrict_create")
                                                <td style="text-align: left; padding: 15px">Create</td>
                                            @elseif($p->name == "restrict_edit")
                                                <td style="text-align: left; padding: 15px">Edit</td>
                                            @elseif($p->name == "restrict_delete")
                                                <td style="text-align: left; padding: 15px">Delete</td>
                                            @endif
                                            @if($perm->restricted == 1)
                                                <td style="padding: 15px"><a class="btn btn-primary" href="{{ route('confirmAddPermission', ['Id' => $house->id_Home, 'user' => $userinfo->id, 'permission' => $p->permission_id]) }}">
                                                        Enable</a></td>
                                            @else
                                                <td style="padding: 15px"><a class="btn btn-danger" href="{{ route('confirmRemovePermission', ['Id' => $house->id_Home, 'user' => $userinfo->id, 'permission' => $p->permission_id]) }}">
                                                        Disable</a></td>
                                            @endif</tr>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="row" style="float: right">
            <div class="card col-5" style=" padding-top: 10px; margin-left: 2%; text-align: left; width: fit-content">
                <h4>Locked storage locations:</h4>
                <?php
                $countplaces = 0;
                ?>
                @foreach($locked as $loc)
                    @foreach($warehouses as $warehouse)
                        @if($loc->warehouse_id == $warehouse->id_Warehouse_place and $warehouse->removed == 0)
                            <?php
                            $countplaces = $countplaces + 1;
                            ?>
                            <p><a class="btn btn-danger" style="margin-right: 10px" href="{{ route('unlockWarehouse', ['Id' => $house->id_Home, 'user' => $userinfo->id, 'warehouse' => $warehouse->id_Warehouse_place]) }}">
                                    Unlock</a>{{$warehouse->Warehouse_name}}</p>
                        @endif
                    @endforeach
                @endforeach
                @if($countplaces == 0)
                    <p>No storage locations locked for this member.</p>
                @endif
            </div>
            <div class="col-5" style=" text-align: left; width: fit-content;">
                <form role="form" method="POST" action="{{ url('lockWarehouse', ['Id' => $house->id_Home, 'user' => $userinfo->id]) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <label style="margin-left: 15px; text-align: left; font-weight: bold" for="inputType">Storage to lock:</label>
                    <select style="margin-left: 20px; margin-bottom: 10px" id="inputType" class="form-control" name="warehouse_id">
                        <option value="0">----------</option>
                        @foreach($warehouses as $warehouse)
                            @if($warehouse->removed == 0)
                                <?php
                                $c = 0;
                                ?>
                                @foreach($locked as $loc)
                                    @if($warehouse->id_Warehouse_place == $loc->warehouse_id)
                                        <?php
                                        $c = $c + 1;
                                        ?>
                                    @endif
                                @endforeach
                                @if($c == 0)
                                    <option value="{{$warehouse->id_Warehouse_place}}">{{$warehouse->Warehouse_name}}</option>
                                @endif
                            @endif
                        @endforeach
                    </select>
                    <div style="padding-left: 15px; margin-left: 5px">
                        <button style="float: left" type="submit" class="btn btn-secondary">Add locked storage</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
