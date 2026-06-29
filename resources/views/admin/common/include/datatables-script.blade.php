@php $table_keys = $table->keys(); @endphp

<script>
    /*
$(function () {
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: "{{ $dataTableURL }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: true},
            @foreach ($table_keys as $var)
                @if ($var == 'action')
                    {data: 'action', name: 'action', orderable: false, searchable: true},
                @else
                    {data: '{{ $var }}', name: '{{ $var }}' },
                @endif
            @endforeach
        ]
    });  
});
*/
$(function () {
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        ajax: "{{ $dataTableURL }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false }, // 🔴 fixed here
            @foreach ($table_keys as $var)
                @if ($var == 'action')
                    { data: 'action', name: 'action', orderable: false, searchable: false }, // 🔴 also fixed here
                @else
                    { data: '{{ $var }}', name: '{{ $var }}' },
                @endif
            @endforeach
        ]
    });  
});

</script>