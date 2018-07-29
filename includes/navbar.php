<?php
  require_once "session.php";
?>

<script type="text/javascript">
  window.onload=function() {
    document.getElementById("roleSelect").onchange=function() {
      if(this.options[this.selectedIndex].value=='default') {
        document.getElementById("btnSwitch").disabled=true;
      }
      else {
        document.getElementById("btnSwitch").disabled=false;
      }
    }
  }
</script>

<?php
  if( $_SERVER["HTTP_HOST"] == "localhost" ) {
    $switchRolesScript = "http://localhost/includes/switchRoles.php";
    $logOutScript = "http://localhost/includes/logout.php";
    $faqLink = "http://localhost/faq.php";
  }
  else {
    $switchRolesScript = "http://projects.cs.dal.ca/aio/includes/switchRoles.php";
    $logOutScript = "http://projects.cs.dal.ca/aio/includes/logout.php";
    $faqLink = "http://projects.cs.dal.ca/aio/faq.php";
  }
?>

<nav class="navbar bg-secondary">
  <span class="navbar-brand">FCS - AIO Portal</span>
  <form class="form-inline pull-right" method="post"action=<?php echo "\"$switchRolesScript\""; ?>>
    <?php 
      if( sizeof($_SESSION['roles']) > 2 ) {
    ?>
    <select class="form-control" id="roleSelect" name="newRole">
      <option id="default" value="default">Switch Roles</option>
      <?php
        foreach( $_SESSION['roles'] as $role ) {
          if( $role != $_SESSION['role'] ) {
            echo "<option value='" . $role . "'>" . $display_names[$role] . "</option>";
          }
        }
      ?>
    </select>
    <button class="btn btn-success" type="submit" id="btnSwitch" disabled>Switch</button>
    <?php
      }
      elseif( sizeof($_SESSION['roles']) == 2 ) {
        $other_role = null;
        foreach( $_SESSION['roles'] as $role ) {
          if( $role != $_SESSION['role'] ) {
            $other_role = $role;
          }
        }
        echo "<button class=\"btn btn-success\" type=\"submit\" formaction=\"$switchRolesScript\" name=\"newRole\" value=\"$other_role\"> Switch to $display_names[$other_role]</button>";
      }
    ?>
    
    <button class="btn btn-danger" style="margin-left: 10pt;" formaction=<?php echo "\"$logOutScript\""; ?>>Logout</button>
    <button class="btn btn-default" style="margin-left: 10pt;" formaction=<?php echo "\"$faqLink\""; ?>>FAQ</button>
  </form>
</nav>