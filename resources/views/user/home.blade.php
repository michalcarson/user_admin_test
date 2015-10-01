@extends('site')

@section('site-content')

<!--
 User should see a form allowing them to update their email, password, some text describing their hobbies, and upload an avatar
-->

<?php $user = Auth::user(); ?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">User Profile</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="profile-form" class="form-horizontal" role="form" method="POST" action="/user/{{ $user->id }}" enctype="multipart/form-data">

                        @include('user.profile')

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary" id="profile-form-submit">
                                    Update Profile
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
