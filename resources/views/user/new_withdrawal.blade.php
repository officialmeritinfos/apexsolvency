@extends('user.base')

@section('content')

    <div class="row">
        <div class="col-xl-7 mx-auto">
            {{--            <h6 class="mb-0 text-uppercase">{{$pageName}}</h6>--}}
            <hr/>
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-5">
                    <div class="card-title d-flex align-items-center">
                        <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                        </div>
                        <h5 class="mb-0 text-primary">{{$pageName}}</h5>
                    </div>
                    <hr>
                    <form class="row g-3" method="post" action="{{route('withdraw.new')}}">
                        @csrf
                        @include('templates.notification')
                        <div class="form-group col-md-12">
                            <label for="inputAddress2">Amount ($)</label>
                            <input type="number" class="form-control" id="inputAddress2"
                                   placeholder="Enter Amount to Withdraw" name="amount">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="inputAddress2">Asset</label>
                            <select type="number" class="form-control" id="inputAddress2"
                                    name="asset">
                                <option value="">Select an Asset</option>
                                @foreach($coins as $coin)
                                    <option value="{{$coin->asset}}">{{$coin->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12 mx-auto">
                            <label for="inputAddress2">Wallet Address</label>
                            <input type="text" class="form-control" id="inputAddress2"
                                   placeholder="Enter Address" name="wallet">
                        </div>
                        <div class="form-group col-md-12 mx-auto">
                            <label for="inputAddress2">Account</label>
                            <select type="number" class="form-control" id="inputAddress2"
                                    name="account">
                                <option value="">Select a Account</option>
                                <option value="1"> Profit Balance</option>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Withdraw</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
