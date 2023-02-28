@extends('layouts.admin.admin-default')
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
@section('content')
@include('includes.admin.navbar')
<main class="content-wrapper">
    <div class="container-fluid py-3">
        <div class="form-section">
            <form>
                <div class="form-wrapper py-3">
                    <input type="text" class="form-control">
                    <button>Add</button>
                </div>
            </form>
        </div>
        <!-- table -->
        <div class="heading-top">
            <h1>Tags</h1>
        </div>
        <div class="client-table pt-2">
            <table id="detail-table" style="width:100%">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Tags Name</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="name-person" data-column="Name">01</td>
                        <td data-column="No of Plots">Tag Name</td>
                        <td data-column="Manager">
                            <div class="action-btn">
                                <h1>EDit</h1>
                                <h1>Delte</h1>
                            </div>
                        </td>
                    </tr>



                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection
@section('admininsertjavascript')
<script>
    $('body').addClass('bg-clr')
</script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#detail-table').DataTable({
            "ordering": false,
            "info": false,
            "searching": false,
            "lengthChange": false,
            "pageLength": 12,
            language: {
                'paginate': {
                    'previous': '<i class="fa fa-chevron-left p-left" aria-hidden="true"></i>',
                    'next': '<i class="fa fa-chevron-right p-right" aria-hidden="true"></i>'
                }
            }
        });
    });
</script>
<script>
    $('.sidenav  li:nth-of-type(1)').addClass('active');
</script>
@endsection