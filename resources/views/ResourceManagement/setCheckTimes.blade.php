<style>

</style>

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
    <div class="container-fluid"  style="padding-bottom: 50px">
        <div class="row mt-2">
            <div class="col" style="max-width: 10px">
                <a style="position: center" href="{{URL::previous()}}">
                    <svg class="bi bi-chevron-compact-left" width="1.5em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M9.224 1.553a.5.5 0 01.223.67L6.56 8l2.888 5.776a.5.5 0 11-.894.448l-3-6a.5.5 0 010-.448l3-6a.5.5 0 01.67-.223z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="container-fluid" style="padding-bottom: 20px">
            <div class="row mt-4">
                <h2 class="page-title" style="margin-left: 14px;">Set checking times</h2>
            </div>
            @if($time != null)
            <div class="row mt-4">
                <div class="col-sm-10 col-md-10">
                    <form role="form" method="POST" action="{{route('saveCheckTimes', ['Id' => $house->id_Home])}}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="form-row">
                            <legend class="lgnd" style="" for="Quantity_check_time">Quantities checking times:</legend>
                            <br>

                            <div class="clearfix visible-xs"></div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Quantity_check_time1">Monday</label>
                                <input style="" type="time" class="form-control" id="Quantity_check_time1"  name="Quantity_check_time1" value="{{$time->Quantity_check_time1}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Quantity_check_time2">Tuesday</label>
                                <input style="" type="time" class="form-control" id="Quantity_check_time2"  name="Quantity_check_time2" value="{{$time->Quantity_check_time2}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Quantity_check_time3">Wednesday</label>
                                <input style="" type="time" class="form-control" id="Quantity_check_time3"  name="Quantity_check_time3" value="{{$time->Quantity_check_time3}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Quantity_check_time4">Thursday</label>
                                <input style="" type="time" class="form-control" id="Quantity_check_time4"  name="Quantity_check_time4" value="{{$time->Quantity_check_time4}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Quantity_check_time5">Friday</label>
                                <input style="" type="time" class="form-control" id="Quantity_check_time5"  name="Quantity_check_time5" value="{{$time->Quantity_check_time5}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Quantity_check_time6">Saturday</label>
                                <input style="" type="time" class="form-control" id="Quantity_check_time6"  name="Quantity_check_time6" value="{{$time->Quantity_check_time6}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Quantity_check_time7">Sunday</label>
                                <input style="" type="time" class="form-control" id="Quantity_check_time7"  name="Quantity_check_time7" value="{{$time->Quantity_check_time7}}">
                            </div>
                        </div>
                        <br>
                        <hr>

                        <div class="form-row">
                            <legend class="lgnd" style=" " for="Status_check_time">Expiration dates checking time:</legend>
                            <br>
                    <div class="clearfix visible-xs"></div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Expiration_check_time1">Monday</label>
                                <input style="" type="time" class="form-control" id="Expiration_check_time1"  name="Expiration_check_time1" value="{{$time->Expiration_check_time1}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Expiration_check_time2">Tuesday</label>
                                <input style="" type="time" class="form-control" id="Expiration_check_time2"  name="Expiration_check_time2" value="{{$time->Expiration_check_time2}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Expiration_check_time3">Wednesday</label>
                                <input style="" type="time" class="form-control" id="Expiration_check_time3"  name="Expiration_check_time3" value="{{$time->Expiration_check_time3}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Expiration_check_time4">Thursday</label>
                                <input style="" type="time" class="form-control" id="Expiration_check_time4"  name="Expiration_check_time4" value="{{$time->Expiration_check_time4}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Expiration_check_time5">Friday</label>
                                <input style="" type="time" class="form-control" id="Expiration_check_time5"  name="Expiration_check_time5" value="{{$time->Expiration_check_time5}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Expiration_check_time6">Saturday</label>
                                <input style="" type="time" class="form-control" id="Expiration_check_time6"  name="Expiration_check_time6" value="{{$time->Expiration_check_time6}}">
                            </div>
                            <div class="checkingtime form-group col-sm-4 col-md-3" style="margin-bottom: 0;!important;">
                                <label for="Expiration_check_time7">Sunday</label>
                                <input style="" type="time" class="form-control" id="Expiration_check_time7"  name="Expiration_check_time7" value="{{$time->Expiration_check_time7}}">
                            </div>
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label for="Status_check_time">Expiration/state checking time</label>--}}
{{--                            <select id="Status_check_time" name="Status_check_time" class="form-control">--}}
{{--                                @if($time->id_Time!= '')--}}
{{--                                    <option value="{{$time->Status_check_time}}">{{$time->Time_Option2->name}}</option>--}}
{{--                                @else--}}
{{--                                    <option value="{{$time->Status_check_time}}"></option>--}}
{{--                                @endif--}}
{{--                                @foreach($time_options2 as $tm)--}}
{{--                                    <option value="{{$tm->id_time_option}}">{{ $tm->name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="min">Min kept value</label>--}}
{{--                            <input type="number" step="0.01" name="min" min="0.00" class="form-control" id="min" placeholder="0.00">--}}
{{--                        </div>--}}

{{--                        <div class="form-group">--}}
{{--                            <label for="max">Max kept value</label>--}}
{{--                            <input type="number" step="0.01" name="max" min="0.00" class="form-control" id="max" placeholder="0.00">--}}
{{--                        </div>--}}
<br>
                        <button type="submit" class="btn btn-secondary ">Save</button>
                    </form>
                </div>

            </div>
                @endif
        </div>

    </div>


@endsection

<script>

</script>
