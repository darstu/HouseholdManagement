<li>{{ $child_category->Warehouse_name }}{{ $child_category->total_quantity}}</li>

@if ($child_category->categories2)
    <ul>
        @foreach ($child_category->categories2 as $childCategory)
            @include('ResourceManagement/child_category2', ['child_category' => $childCategory])
        @endforeach
    </ul>
@endif
