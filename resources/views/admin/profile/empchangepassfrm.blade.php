<input type="hidden" name="emp_id" id="emp_id" value="{{$user->id}}">
<div class="form-group">
    <label for="password">New Password <strong class="text-danger">*</strong></label>
    <input type="password" name="password" id="password" class="form-control rounded-0 register-pswd" required="" minlength="6">
</div>
<div class="form-group">
    <label for="confirmPassword">Confirm Password <strong class="text-danger">*</strong></label>
    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control register-cnfm-pswd rounded-0" required="" data-parsley-equalto="#password">
</div>
<div class="form-group custom-control custom-checkbox">
    <input type="checkbox" class="custom-control-input" id="exampleCheck2" value="2" onclick="myFunction(this.value)" >
    <label class="custom-control-label" for="exampleCheck2">Show Password</label>
</div>

@props(['btnclass' => 'btn-primary', 'row'=> '4'])
<x-submitbutton :btnclass="$btnclass" :row="$row"/>