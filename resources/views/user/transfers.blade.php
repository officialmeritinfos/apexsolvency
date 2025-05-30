@extends('user.base')
@section('content')
    @inject('injected','App\Defaults\Custom')

    <div class="today-card-area pt-24">
        <div class="container-fluid">
            @include('templates.notification')
            <div class="row justify-content-center">
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="single-today-card d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="today">Profit</span>
                            <h6>${{ number_format($user->profit, 2) }}</h6>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <img src="{{ asset('dashboard/user/images/icon/discount.png') }}" alt="Images">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="single-today-card d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="today">Bonus</span>
                            <h6>${{ number_format($user->bonus, 2) }}</h6>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <img src="{{ asset('dashboard/user/images/icon/discount.png') }}" alt="Images">
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 mb-4">
                    <div class="single-today-card d-flex align-items-center">
                        <div class="flex-grow-1">
                            <span class="today">Referral Bonus</span>
                            <h6>${{ number_format($user->refBal, 2) }}</h6>
                        </div>
                        <div class="flex-shrink-0 align-self-center">
                            <img src="{{ asset('dashboard/user/images/icon/discount.png') }}" alt="Images">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- External Transfer (Profit to another user) -->
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card border-top border-0 border-4 border-primary mb-4">
                <div class="card-body p-5">
                    <div class="card-title d-flex align-items-center">
                        <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                        <h5 class="mb-0 text-primary">{{ $pageName }}</h5>
                    </div>
                    <hr>
                    <form class="row g-3" method="post" action="{{ route('transfer.new') }}">
                        @csrf
                        <div class="form-group col-md-12">
                            <label>Recipient Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Enter recipient username">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Amount ($)</label>
                            <input type="number" class="form-control" name="amount" placeholder="Enter amount">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Account Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter account password">
                        </div>
                        <div class="form-group col-md-12">
                            <p>Transfer Charges: {{ $web->transferCharge }}%</p>
                        </div>
                        @if ($user->canLoan == 1)
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Proceed</button>
                            </div>
                        @else
                            <div class="text-center text-danger">
                                <p>Transfer is disabled. Please contact support for more information.</p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Internal Transfers (Bonus/RefBal to Profit) -->
    <div class="row">
        <div class="col-xl-4 mx-auto">
            <div class="card border-top border-0 border-4 border-warning mb-4">
                <div class="card-body p-4">
                    <h5 class="text-warning mb-3">Transfer Bonus to Profit</h5>
                    <form method="post" action="{{ route('transfer.bonus-to-profit') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Amount ($)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="Enter bonus amount">
                        </div>
                        <button type="submit" class="btn btn-outline-warning w-100">Transfer Bonus</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mx-auto">
            <div class="card border-top border-0 border-4 border-info mb-4">
                <div class="card-body p-4">
                    <h5 class="text-info mb-3">Transfer Referral Balance to Profit</h5>
                    <form method="post" action="{{ route('transfer.refbal-to-profit') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Amount ($)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="Enter referral balance amount">
                        </div>
                        <button type="submit" class="btn btn-outline-info w-100">Transfer Balance</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfers Table -->
    <div class="container-fluid mt-5">
        <div class="ui-kit-cards grid mb-24">
            <h3>Recent Transfers</h3>
            <div class="latest-transaction-area">
                <div class="table-responsive h-auto" data-simplebar>
                    <table class="table align-middle mb-0">
                        <thead>
                        <tr>
                            <th>RECIPIENT USERNAME</th>
                            <th>SENDER USERNAME</th>
                            <th>AMOUNT</th>
                            <th>SENT AT</th>
                            <th>STATUS</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transfers as $account)
                            <tr>
                                <td>{{ $account->recipientHolder }}</td>
                                <td>{{ $injected->getInvestorUsername($account->sender) }}</td>
                                <td>${{ number_format($account->amount, 2) }}</td>
                                <td>{{ $account->created_at }}</td>
                                <td>
                                    @switch($account->status)
                                        @case(1)
                                            <span class="badge bg-success">Completed</span>
                                            @break
                                        @case(2)
                                            <span class="badge bg-info">Pending</span>
                                            @break
                                        @case(4)
                                            <span class="badge bg-primary">Ongoing</span>
                                            @break
                                        @default
                                            <span class="badge bg-danger">Cancelled</span>
                                    @endswitch
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
