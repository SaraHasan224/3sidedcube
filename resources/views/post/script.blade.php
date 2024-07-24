@section('scripts')
    <script>
        $(document).ready(function () {
            App.Post.initializeDataTable();
            $(":input").inputmask();
        })
    </script>
@endsection
