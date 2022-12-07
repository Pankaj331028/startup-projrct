@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Dashboard
    </div>

    <div class="card-body">
        <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
            <div class="card-header">Header</div>
            <div class="card-body">
                <h5 class="card-title">Dark card title</h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 g-4">
            <div class="col">
              <div class="card" class="bg-primary">
                <img src="https://dummyimage.com/600x400/dfdfdf/000000.png&text=IMAGE%20001" class="card-img-top" alt="https://dummyimage.com/600x400/dfdfdf/000000.png&text=IMAGE%20001">
                <div class="card-body">
                  <h5 class="card-title">Card title</h5>
                  <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.</p>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card">
                <img src="https://dummyimage.com/600x400/dfdfdf/000000.png&text=IMAGE%20002" class="card-img-top" alt="https://dummyimage.com/600x400/dfdfdf/000000.png&text=IMAGE%20002">
                <div class="card-body">
                  <h5 class="card-title">Card title</h5>
                  <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.</p>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card">
                <img src="https://dummyimage.com/600x400/dfdfdf/000000.png&text=IMAGE%20003" class="card-img-top" alt="https://dummyimage.com/600x400/dfdfdf/000000.png&text=IMAGE%20003">
                <div class="card-body">
                  <h5 class="card-title">Card title</h5>
                  <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.</p>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card">
                <img src="https://dummyimage.com/600x400/dfdfdf/000000.png&text=IMAGE%20004" class="card-img-top" alt="https://dummyimage.com/600x400/dfdfdf/000000.png&text=IMAGE%20004">
                <div class="card-body">
                  <h5 class="card-title">Card title</h5>
                  <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.</p>
                </div>
              </div>
            </div>
          </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
    <script>
        var table;
        $(function () {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

            table = $('.datatable-Cities:not(.ajaxTable)').DataTable({ buttons: dtButtons });
        })
</script>
@endsection
