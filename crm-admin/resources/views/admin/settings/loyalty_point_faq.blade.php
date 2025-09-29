@extends('admin.inc.layout')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4 text-primary">
        <span class="text-muted fw-light">Setting /</span> Loyalty Points FAQ's
    </h4>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">
            <form action="{{ route('setting.loyalty_point_faq_store') }}" method="POST">
                @csrf
                <div id="faqContainer">
                    @if (!empty($data) && count($data) > 0)
                        @foreach ($data as $faq)
                            <div class="faq-block p-3 mb-3 border rounded position-relative">
                                <input type="hidden" name="ids[]" value="{{ $faq->id ?? '' }}">
                
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label>Question:</label>
                                        {{-- <form action="{{ route('setting.loyalty_point_faq_delete') }}" method="POST" class="d-inline-block">
                                            @csrf --}}
                                            <span onclick="deleteFaq({{ $faq->id }})"class="btn btn-sm btn-danger delete-icon">
                                                <i class="fa fa-trash"></i>
                                            </span>
                                        {{-- </form> --}}
                                    </div>
                                    <input type="text" name="questions[]" class="form-control" value="{{ $faq->question ?? '' }}" required>
                                </div>
                
                                <div class="mb-2">
                                    <label>Answer:</label>
                                    <textarea name="answers[]" class="form-control" rows="3" required>{{ $faq->answer ?? '' }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Show one blank input block when there’s no data --}}
                        <div class="faq-block p-3 mb-3 border rounded position-relative">
                            <input type="hidden" name="ids[]" value="">
                
                            <div class="mb-2">
                                <label>Question:</label>
                                <input type="text" name="questions[]" class="form-control" required>
                            </div>
                
                            <div class="mb-2">
                                <label>Answer:</label>
                                <textarea name="answers[]" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                    @endif
                </div>
                

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <button type="button" class="custom-add-btn" onclick="addMore()"></button>
                    <button type="submit" class="btn btn-primary custom-btn">Submit</button>
                </div>
                <div class="div">
                    <iframe src="https://adventuresoverland.com/staging-crm/crm-admin/embed" width="400" height="300" frameborder="0"></iframe>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addMore() {
        const container = document.getElementById('faqContainer');
        const block = document.createElement('div');
        block.classList.add('faq-block', 'p-3', 'mb-3', 'border', 'rounded', 'position-relative', 'custom-faq-block');

        block.innerHTML = `
            <input type="hidden" name="ids[]" value="">
            <div class="mb-2">
                <label>Question:</label>
                <input type="text" name="questions[]" class="form-control custom-input" required>
            </div>
            <div class="mb-2">
                <label>Answer:</label>
                <textarea name="answers[]" class="form-control custom-textarea" rows="3" required></textarea>
            </div>
            <button type="button" class="btn btn-sm btn-danger remove-btn" onclick="removeBlock(this)">Remove</button>
        `;

        container.appendChild(block);
    }

    function deleteFaq(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This FAQ will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                   
                    url: "{{ route('setting.loyalty_point_faq_delete') }}",
                    type: "GET",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    success: function (response) {
                        $('#faq-block-' + id).remove();
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'FAQ has been deleted successfully.',
                            icon: 'success',
                            showConfirmButton: false,    // hides the OK button
                            timer: 2000                  // auto-closes after 2 seconds
                        });
                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                      
                    },
                    error: function () {
                        Toast.fire({
                            icon: "error",
                            title: "Something went wrong!"
                        });
                    }
                });
            }
        });
    }




    function removeBlock(button) {
        button.closest('.faq-block').remove();
    }
</script>


@endsection
