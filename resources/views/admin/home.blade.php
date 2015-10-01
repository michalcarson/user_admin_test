@extends('site')

@section('site-content')

        <!--
 An admin flag on the users table should determine whether a user is an
admin or not. If they are, upon logging in using the existing form, they
should instead view a table of all users (using columns for email,
avatar and hobbies).
* They should be able to click each user to go to an edit form - all the
same stuff the user can change, plus changing their admin status.
* they should also be able to delete a user from the table. The delete
could be enhanced with javascript, so the delete is done using ajax, and
the row in the table fading out.
-->

<?php $user = Auth::user(); ?>
<div class="container">
    <table class="table table-hover" id="user-table">
        <thead>
        <tr>
            <th></th>
            <th>Email</th>
            <th>Avatar</th>
            <th>Hobbies</th>
        </tr>
        </thead>
        <tbody id="user-tbody"></tbody>
        <tfoot>
        <tr>
            <td colspan="4"><a class="btn btn-danger" href="#" id="delete-btn">Delete</a></td>
        </tr>
        </tfoot>
    </table>
</div>

<div class="modal fade" id="user-edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">User Profile</h4>
            </div>
            <div class="modal-body">
                <form id="profile-form" class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">
                    @include('user.profile')
                    <div class="form-group">
                        <label class="col-md-4 control-label">Administrator</label>
                        <div class="col-md-6" style="text-align: left">
                            <input type="checkbox" class="form-control" name="admin" value="1">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="id">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="user-edit-modal-save">Save changes</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('site-script')
    <script src="/js/admin.user.js"></script>
@endsection
