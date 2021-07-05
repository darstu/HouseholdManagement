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
                <h3 style="margin-left: 14px;">Edit batch</h3>
            </div>
            <div class="row mt-4">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{route('saveEditBatch', ['Id' => $house->id_Home, 'batch_id' => $batch->id_Stock_batch])}}" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
{{--{{$batch->id_Stock_batch}}--}}

                        <div class="form-group">
                            <label for="number">Number</label>
                            <input type="text" value= "{{$batch->number}}" name="number" class="form-control" id="number">
                        </div>

                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <input type="text" class="form-control" name="comment" value= "{{$batch->comment}}" id="comment">
                        </div>

                        <div class="form-group">
                            <label for="fk_Stock_card">Stock card</label>
                            <select id="fk_Stock_card" name="fk_Stock_card" class="form-control">
                                <option value="{{$batch->fk_Stock_card}}"> {{$batch->Stock_Card->Name}}</option>
                                @foreach($cards as $c)
                                    <option value="{{$c->id_Stock_card}}">{{$c->Name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <button type="submit" class="btn btn-secondary ">Edit Batch</button>
                    </form>
                </div>

            </div>
        </div>

    </div>


@endsection

<script>

</script>
