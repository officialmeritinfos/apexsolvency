@extends('user.base')
@section('content')
    @inject('injected','App\Defaults\Custom')

    @if($user->isVerified==2 || $user->isVerified==3)
        <!-- DataTales Example -->
        <div class="card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{$pageName}}
                </h6>
            </div>
            <div class="card-body row">
                <div class="col-md-12 mx-auto">
                    <form method="post" action="{{route('kyc.update')}}" class="g-5"
                    enctype="multipart/form-data">
                        @csrf
                        @include('templates.notification')

                        <div class="row mt-3">
                            <div class="form-group col-md-6 mt-3">
                                <label for="inputAddress2">Date of Birth</label>
                                <input type="date" class="form-control" id="inputAddress2"
                                       placeholder="Enter your contact number" name="dob"
                                       value="{{ $user->dateOfBirth ?? '2000-09-02' }}">
                            </div>
                            <div class="form-group col-md-6 mt-3">
                                <label for="inputAddress2">Country</label>
                                <input type="text" class="form-control" id="inputAddress2"
                                       placeholder="Enter country" name="country" value="{{$user->country}}">
                            </div>
                            <div class="form-group col-md-6 mt-3">
                                <label for="inputAddress2">ID Type</label>
                                <select class="form-control" id="inputAddress2" name="idType">
                                    <option value="">Select an option</option>
                                    <option>Drivers License</option>
                                    <option>National ID Card</option>
                                    <option>International Passport</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 mt-3">
                                <label for="inputAddress2">ID Number</label>
                                <input type="text" class="form-control" id="inputAddress2"
                                       placeholder="Enter ID Number" name="idNumber"/>
                            </div>
                            <div class="form-group col-md-6 mt-3">
                                <label for="inputAddress2">ID Image(front)</label>
                                <input type="file" class="form-control" id="inputAddress2"
                                       placeholder="Enter ID Number" name="frontImage"/>
                            </div>
                            <div class="form-group col-md-6 mt-3">
                                <label for="inputAddress2">ID Image(Back)<sup>(optional)</sup> </label>
                                <input type="file" class="form-control" id="inputAddress2"
                                       placeholder="Enter ID Number" name="backImage"/>
                            </div>
                            <div class="form-group col-md-12 mt-3">
                                <label for="inputAddress2">Selfie<i class="ri-information-fill" data-bs-toggle="tooltip"
                                    title="A selfie image"></i> </label>
                                <input type="file" class="form-control" id="inputAddress2" name="selfie"/>
                            </div>
                            <div class="form-group col-md-12 mt-3">
                                <label for="inputAddress2">Membership ID<i class="ri-information-fill" data-bs-toggle="tooltip"
                                    title="Membership ID issued by {{$siteName}}"></i> </label>
                                <input type="file" class="form-control" id="inputAddress2" name="membership"/>
                            </div>
                        </div>
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary">Submit KYC</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    @elseif($user->isVerified==4)
        <div class="ui-kit-card mb-24">
            <h3>KYC Under Review</h3>
            <div class="alert alert-primary" role="alert">
                <h4 class="alert-heading">KYC Under Review</h4>
                We are currently reviewing your KYC Submission
            </div>
        </div>
    @else
        <div class="ui-kit-card mb-24">
            <h3>KYC Under Review</h3>
            <div class="alert alert-success" role="alert">
                <h4 class="alert-heading">KYC Verified</h4>
                Your KYC submission has been verified.
            </div>
        </div>
    @endif
@endsection
