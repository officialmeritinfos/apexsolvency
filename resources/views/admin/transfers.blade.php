@extends('admin.base')
@section('content')
    @inject('injected','App\Defaults\Custom')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Type</th>
                        <th>Reference</th>
                        <th>Amount</th>
                        <th>Date Initiated</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($deposits as $deposit)
                        @inject('option','App\Defaults\Custom')
                        <tr>
                            <td>{{$option->getInvestor($deposit->sender)}}</td>
                            <td>{{$option->getInvestor($deposit->recipient)}}</td>
                            <td>{{ $deposit->recipientHolder }}</td>
                            <td>{{$deposit->reference}}</td>
                            <td>{{number_format($deposit->amount,2)}}</td>
                            <td>{{$deposit->created_at}}</td>
                            <td>
                                @switch($deposit->status)
                                    @case(1)
                                        <span class="badge badge-success">Completed</span>
                                        @break
                                    @case(2)
                                        <span class="badge badge-info">Pending</span>
                                        @break
                                    @case(3)
                                        <span class="badge badge-danger">Cancelled</span>
                                        @break
                                    @default
                                        <span class="badge badge-dark">Partial Payment</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Reference</th>
                        <th>Amount</th>
                        <th>Date Initiated</th>
                        <th>Status</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endsection
