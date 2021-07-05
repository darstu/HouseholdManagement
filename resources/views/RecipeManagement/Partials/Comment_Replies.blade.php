@foreach($comments as $comment)
    <div class="display-comment">
        <div class="container" style="border:1px solid black">
            <strong>{{ $comment->User->name }} </strong>
            <strong>/ {{ $comment->Date_created }}</strong>
            <p>{{ $comment->Text }}</p>
            <a style="cursor: pointer" onclick="showReply('replyComment-{{$comment->id_Comment}}')"><i
                    class="fas fa-reply"></i></a>
            @if(Auth::id()===$comment->User->id)
                <a style="cursor: pointer" onclick="showHide('editComment-{{$comment->id_Comment }}')"> <i
                        class="far fa-edit"></i> </a>
                <a onclick="return confirm('Do you want to remove comment?')"
                   href="{{route('Comment.Delete',$comment->id_Comment)}}"><i
                        class="fas fa-trash-alt"></i></a>
            @endif
        </div>
        <form method="post"
              action="{{ route('Recipe.add.comment',['id_Recipe'=>$id_Recipe,'id_Comment'=>$comment->id_Comment]) }}"
              id="replyComment-{{$comment->id_Comment}}" style="display: none; margin-top: 10px">
            @csrf
            <input onclick="handler(event)" type="text" placeholder="Text" name="Text">

            <button type="submit" class="btn btn-secondary">Add Comment</button>
        </form>
        <form method="post"
              action="{{ route('Comment.update',$comment->id_Comment) }}"
              id="editComment-{{ $comment->id_Comment }}"
              style="display: none; margin-top: 10px">
            @csrf
            <input onclick="handler(event)" type="text" placeholder="Text" name="Text" value="{{$comment->Text}}">
            <button type="submit" class="btn btn-secondary">Update Comment</button>
        </form>
        @include('RecipeManagement.Partials.Comment_Replies', ['comments' => $comment->reply])
    </div>
@endforeach
<script>
    function showReply(id) {
        if (id.indexOf('replyComment') >= 0) {
            $("#" + id).toggle();
        }
        var check = id.split('-').pop();
        if ($("#editComment-" + check).is(':visible')) {
            $("#editComment-" + check).toggle();
        }
    }

    function showHide(id) {
        if (id.indexOf('editComment') >= 0) {
            $("#" + id).toggle();
        }
        var check = id.split('-').pop();
        if ($("#editComment-" + check).is(':visible')) {
            $("#replyComment-" + check).toggle();
        }
    }
</script>
<link href="{{ asset('css/overlay.css') }}" rel="stylesheet">
<style>

    .display-comment {
        padding-left: 20px;
    }
</style>
