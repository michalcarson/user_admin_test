
<input type="hidden" name="_token" value="{{ csrf_token() }}">

<div class="form-group">
    <label class="col-md-4 control-label">E-Mail Address</label>
    <div class="col-md-6">
        <input type="email" class="form-control" name="email" value="{{ $user->email }}">
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label">Avatar</label>
    <div class="col-md-6">
        <img id="user-avatar-img" src="{{ $user->avatar }}">
        <input type="file" name="avatar" value="{{ $user->avatar }}">
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label">Hobbies</label>
    <div class="col-md-6">
        <textarea class="form-control" name="hobbies">{{ $user->hobbies }}</textarea>
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label">Password</label>
    <div class="col-md-6">
        <input type="password" class="form-control" name="password">
    </div>
</div>

<div class="form-group">
    <label class="col-md-4 control-label">Confirm Password</label>
    <div class="col-md-6">
        <input type="password" class="form-control" name="password_confirmation">
    </div>
</div>
