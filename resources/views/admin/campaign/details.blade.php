@extends('admin.layouts.admin')

@section('content')

<section class="content mt-3" id="contentContainer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Campaign Details</h3>
                    </div>
                    <div class="card-body">
                        @if($data->isNotEmpty())
                             <div class="mb-4">
                                <h4 class="mb-2"><strong>Title:</strong> {{ $data->first()->campaign->title }}</h4>
                                <p class="mb-2"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($data->first()->campaign->start_date)->format('d-m-Y') }}</p>
                                <p class="mb-2"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($data->first()->campaign->end_date)->format('d-m-Y') }}</p>
                            </div>

                            <table class="table table-bordered" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Supplier Name</th>
                                        <th>Products</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $campaignRequest)
                                        <tr>
                                            <td>{{ $campaignRequest->supplier->name ?? 'Admin' }}</td>
                                            <td>
                                                <ul class="mb-0">
                                                    @foreach($campaignRequest->campaignRequestProducts as $product)
                                                        <li>{{ $product->product->name }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No campaign details available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('script')

<script>
    $(document).ready(function () {
        $("#dataTable").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#campaignTable_wrapper .col-md-6:eq(0)');
    });
</script>

@endsection