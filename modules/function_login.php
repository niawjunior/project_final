<style >
     .checkbox.checkbox-circle label::before {
    border-radius: 50%; }
</style>
<form id="signin" class="navbar-form navbar-right" role="form" method="post" action="check_login.php">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input  size="15" id="username" type="text" class="form-control" name="username" value="<?echo $_SESSION["user"];?>" placeholder="Username or Email" required="">
    </div>
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
        <input size="15" id="password" type="password" class="form-control" name="password" value="<?echo $_SESSION["pass"];?>" placeholder="Password" required="">
    </div>


 <div class="checkbox checkbox-circle">
                                    <label>
<input type="checkbox" name="b1" value="B1"/><?php if (isset ($b1) && $b1=="B1")?>
                                </label>
                            </div>


    <button type="submit" class="btn btn-warning">Login</button>
    <a href="register.php"><button type="button" class="btn btn-primary">Register</button>
    </a>
</form>