@extends('header')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-5 offset-lg-3 mt-5  p-5 bg-light card ">
                <form action="{{ route('students.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="Enter Full Name">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                            id="exampleInputPassword1" placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Phone</label>
                        <input type="text" name="phone_number" minlength="11" value="{{ old('phone_number') }}"
                            class="form-control" id="exampleInputPassword1" placeholder="Enter Phone Number">
                    </div>
                    <button type="submit" style="float: right;" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 mt-5 mb-5 bg-light card">
                <table class="table table-striped" id="posTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Status</th>
                            <th scope="col">Approved By</th>
                            <th scope="col">Change Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="student">
                        @foreach ($students as $student)
                            <tr>
                                <th scope="row" class="id">{{ $student->id }} </th>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone_number }}</td>
                                <td class="statuses">{{ $student->status }}</td>
                                <td>{{ $student->approved_by }}</td>
                                <td><select class="sta" id="status">
                                        <option value="pending">Pending</option>
                                        <option value="Active">Active</option>
                                        <option value="Rejected">Rejected</option>
                                    </select>
                                </td>
                                <td class="d-flex">
                                    <a class="btn btn-primary mr-2" data-toggle="modal"
                                        data-target="#update{{ $student->id }}">Edit</a>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit" style="border:none;">Delete</button>
                                    </form>
                                </td>

                                <div class="modal" id="update{{ $student->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="exampleModalLabel1">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="exampleModalLabel1">Edit Modal
                                                </h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <form id="form" method="post"
                                                action="{{ route('students.update', $student->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <input hidden type="number" name="id" value="">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="recipient-name" class="control-label">Name</label>
                                                        <input required type="text" class="form-control" name="name"
                                                            value="{{ $student->name }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="message-text" class="control-label">Emai</label>
                                                        <input required class="form-control" type="email" name="email"
                                                            value="{{ $student->email }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="message-text" class="control-label">Phone
                                                            Number</label>
                                                        <input required class="form-control" type="number"
                                                            name="phone_number" value="{{ $student->phone_number }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="message-text" class="control-label">Status</label>
                                                        <select class="form-control" id="status_input" name="status">
                                                            <option value="{{ $student->status }}" selected>
                                                                {{ $student->status }}</option>
                                                            <option value="pending">Pending</option>
                                                            <option value="Approved">Approved</option>
                                                            <option value="Rejected">Rejected</option>
                                                        </select>
                                                    </div>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" name="submit"
                                                        class="btn btn-primary">Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            //Toastr Messages Display.
            @if (isset($errors))
                @foreach ($errors->all() as $message)
                    toastr.error("{{ $message }}");
                @endforeach
            @endif

            @if (Session::has('messages'))
                @foreach (Session::get('messages') as $key => $message)
                    @if ($key == 'danger')
                        toastr.error("{{ $message }}");
                    @elseif($key == 'success')
                        toastr.success("{{ $message }}");
                    @elseif($key == 'info')
                        toastr.info("{{ $message }}");
                    @elseif($key == 'warning')
                        toastr.warning("{{ $message }}");
                    @endif
                @endforeach
            @endif

            //change Status Function
            $(".sta").change(function() {

                const currentRow = $(this).closest("tr");
                var status = currentRow.find(".sta").val();
                var id = currentRow.find(".id").text();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                //Ajax Call
                $.ajax({
                    type: 'POST',
                    url: "{{ route('change.status') }}",
                    data: {
                        status: status,
                        id: id,
                    },
                    success: function(student) {
                        currentRow.find(".statuses").text(student.status);
                        toastr.success("Status Updated Successfully");
                    },
                });
            });
        });
    </script>
@stop
