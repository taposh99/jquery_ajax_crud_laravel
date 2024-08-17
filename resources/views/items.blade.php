<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel AJAX CRUD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Laravel 10 AJAX CRUD</h2>
        <button class="btn btn-success mb-2" id="btn-add">Add New Item</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Division</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="item-list">
                @foreach($items as $item)
                    <tr id="item-{{ $item->id }}">
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->division }}</td>
                        <td>
                            <button class="btn btn-info btn-view" data-id="{{ $item->id }}"><i
                                    class="fas fa-eye"></i></button>
                            <button class="btn btn-primary btn-edit" data-id="{{ $item->id }}"><i
                                    class="fas fa-edit"></i></button>
                            <button class="btn btn-danger btn-delete" data-id="{{ $item->id }}"><i
                                    class="fas fa-trash-alt"></i></button>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal" id="itemModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Item Form</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="itemForm">
                        <input type="hidden" id="item_id">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="form-group">
                            <label for="division">Division:</label>
                            <select class="form-control" id="division" required>
                                <option value="Dhaka">Dhaka</option>
                                <option value="Chittagong">Chittagong</option>
                                <option value="Khulna">Khulna</option>
                                <!-- Add more divisions as needed -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    // <!-- View Modal -->
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">View Item Details</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="view_name"></span></p>
                    <p><strong>Division:</strong> <span id="view_division"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#btn-add').click(function () {
                $('#itemModal').modal('show');
                $('#itemForm').trigger("reset");
                $('#item_id').val('');
            });

            $('#itemForm').on('submit', function (e) {
                e.preventDefault();
                let id = $('#item_id').val();
                let name = $('#name').val();
                let division = $('#division').val();
                let url = id ? `/update/${id}` : '/store';
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: name,
                        division: division,
                    },
                    success: function (response) {
                        $('#itemModal').modal('hide');
                        location.reload();
                        console.log(response);
                    }
                });
            });

            $('.btn-edit').click(function () {
                let id = $(this).data('id');
                $.get(`/edit/${id}`, function (data) {
                    $('#item_id').val(data.id);
                    $('#name').val(data.name);
                    $('#division').val(data.division);
                    $('#itemModal').modal('show');
                });
            });

            $('.btn-view').click(function () {
                let id = $(this).data('id');
                $.get(`/show/${id}`, function (data) {
                    $('#view_name').text(data.name);
                    $('#view_division').text(data.division);
                    $('#viewModal').modal('show');
                });
            });


            $('.btn-delete').click(function () {
                let id = $(this).data('id');
                if (confirm('Are you sure you want to delete this item?')) {
                    $.ajax({
                        url: `/delete/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },
                        success: function (response) {
                            $(`#item-${id}`).remove();
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>