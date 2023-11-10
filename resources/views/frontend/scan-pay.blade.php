@extends('frontend.layouts.app')

@section('title')
    Scan And Pay
@endsection
@section('content')
    <div class="scan">
        <div class=" card">
            <div class=" card-body text-center">
                @error('phone')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                @include('frontend.layouts.flash')
                <div class=" text-center m-4">
                    <img width="220px" src="{{ asset('img/scan-and-pay.png') }}" alt="">
                </div>
                <p><strong>Click button, Scan QR to pay</strong></p>
                {{-- <button class=" btn btn-violet btn-sm">Scan</button> --}}

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-violet btn-sm" data-bs-toggle="modal" data-bs-target="#scanModal">
                    Scan
                </button>

                <!-- Modal -->
                <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <video id="scanner" width="100%" height="300px"></video>
                            </div>
                            {{-- <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {

            const modal = new bootstrap.Modal('#scanModal');
            var videoElem = document.getElementById('scanner')
            const qrScaner = new QrScanner(videoElem, function(result) {
                if (result) {
                    qrScaner.stop();
                    console.log(result);
                    modal.hide();

                    var phone = result;
                    window.location.replace(`scan-pay-transfer?phone=${phone}`)

                    // $.ajax({
                    //     url: `scan-pay-transfer?phone=${result}`,
                    //     type: 'GET',
                    //     success: function() {
                    //         // window.location.replace()
                    //         url: `scan-pay-transfer?phone=${result}`
                    //     }
                    // })
                }
            });

            $('#scanModal').on('shown.bs.modal', function(event) {
                qrScaner.start();
            })

            $('#scanModal').on('hidden.bs.modal', function(event) {
                qrScaner.stop();
            })

            // var videoElem = document.getElementById('#video');
            // const qrScanner = new QrScanner(
            //     videoElem,
            //     result => console.log(result),
            // );


            // $('#scanModel').on('shown.bs.modal', function(event) {
            //     qrScanner.start();
            // })
        })
    </script>
@endsection
